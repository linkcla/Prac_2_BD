<!-- @Author: Pau Toni Bibiloni Martínez -->

<?php
include_once "conexion.php";

class PaaSFuncionalidades {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Funciones de delete_paas
    public function deletePaaS($selectedRows) {
        if ($selectedRows && is_array($selectedRows)) {
            $error_ocurred = false;

            // Iniciar transacción
            mysqli_begin_transaction($this->conn);

            foreach ($selectedRows as $id) {
                // Sanitiza el ID
                $id = mysqli_real_escape_string($this->conn, $id);

                // Obtener el idConfig del PaaS
                $query = "SELECT idConfig FROM PAAS WHERE idConfig = '$id'";
                $result = mysqli_query($this->conn, $query);
                if ($result && $row = mysqli_fetch_assoc($result)) {
                    $idConfig = $row['idConfig'];

                    // Eliminar registros de PERSONAL_CREA_PRODUCTE
                    $deletePersonalCreaProducteQuery = "DELETE FROM PERSONAL_CREA_PRODUCTE WHERE idConfigProducte = '$idConfig'";
                    if (!mysqli_query($this->conn, $deletePersonalCreaProducteQuery)) {
                        $error_ocurred = true;
                        break;
                    }

                    // Eliminar registro de PAAS
                    $deletePaasQuery = "DELETE FROM PAAS WHERE idConfig = '$id'";
                    if (!mysqli_query($this->conn, $deletePaasQuery)) {
                        $error_ocurred = true;
                        break;
                    }

                    // Eliminar registro de PRODUCTE
                    $deleteProducteQuery = "DELETE FROM PRODUCTE WHERE idConfig = '$idConfig'";
                    if (!mysqli_query($this->conn, $deleteProducteQuery)) {
                        $error_ocurred = true;
                        break;
                    }
                } else {
                    $error_ocurred = true;
                    break;
                }
            }

            if ($error_ocurred) {
                // Revertir la transacción si hubo error
                mysqli_rollback($this->conn);
                $_SESSION["error_msg"] = "Error al eliminar el PaaS seleccionado.";
            } else {
                // Confirmar la transacción si todo salió bien
                mysqli_commit($this->conn);
                $_SESSION["success_msg"] = "El PaaS seleccionado se eliminó correctamente.";
            }
        } else {
            $_SESSION["warning_msg"] = "Debes seleccionar un PaaS.";
        }
    }

    // Funciones de delete_stock_componentes
    public function deleteStockComponentes($selectedRam, $selectedDiscDur, $selectedCpu, $selectedSo) {
        if (empty($selectedRam) && empty($selectedDiscDur) && empty($selectedCpu) && empty($selectedSo)) {
            $_SESSION["warning_msg"] = "Debes seleccionar al menos un componente.";
        } else {
            $error_ocurred = false;
            // Iniciar transacción
            mysqli_begin_transaction($this->conn);

            // Eliminar RAM seleccionada
            foreach ($selectedRam as $ram) {
                list($tipus, $GB) = explode(',', $ram);
                $tipus = mysqli_real_escape_string($this->conn, $tipus);
                $GB = mysqli_real_escape_string($this->conn, $GB);
                $deleteRamQuery = "DELETE FROM RAM WHERE tipus = '$tipus' AND GB = '$GB'";
                if (!mysqli_query($this->conn, $deleteRamQuery)) {
                    $error_ocurred = true;
                    break;
                }
            }

            // Eliminar Disco Duro seleccionado
            if (!$error_ocurred) {
                foreach ($selectedDiscDur as $discDur) {
                    list($tipus, $GB) = explode(',', $discDur);
                    $tipus = mysqli_real_escape_string($this->conn, $tipus);
                    $GB = mysqli_real_escape_string($this->conn, $GB);
                    $deleteDiscDurQuery = "DELETE FROM DISC_DUR WHERE tipus = '$tipus' AND GB = '$GB'";
                    if (!mysqli_query($this->conn, $deleteDiscDurQuery)) {
                        $error_ocurred = true;
                        break;
                    }
                }
            }

            // Eliminar CPU seleccionada
            if (!$error_ocurred) {
                foreach ($selectedCpu as $cpu) {
                    list($model, $nNuclis) = explode(',', $cpu);
                    $model = mysqli_real_escape_string($this->conn, $model);
                    $nNuclis = mysqli_real_escape_string($this->conn, $nNuclis);
                    $deleteCpuQuery = "DELETE FROM CPU WHERE model = '$model' AND nNuclis = '$nNuclis'";
                    if (!mysqli_query($this->conn, $deleteCpuQuery)) {
                        $error_ocurred = true;
                        break;
                    }
                }
            }

            // Eliminar SO seleccionado
            if (!$error_ocurred) {
                foreach ($selectedSo as $so) {
                    $nom = mysqli_real_escape_string($this->conn, $so);
                    $deleteSoQuery = "DELETE FROM SO WHERE nom = '$nom'";
                    if (!mysqli_query($this->conn, $deleteSoQuery)) {
                        $error_ocurred = true;
                        break;
                    }
                }
            }

            if ($error_ocurred) {
                // Revertir la transacción si hubo error
                mysqli_rollback($this->conn);
                $_SESSION["error_msg"] = "Error al eliminar los componentes seleccionados.";
            } else {
                // Confirmar la transacción si todo salió bien
                mysqli_commit($this->conn);
                $_SESSION["success_msg"] = "Los componentes seleccionados se eliminaron correctamente.";
            }
        }
    }

    // Funciones de edit_stock_componentes
    public function getComponentesByTipo($tipo) {
        $componentes = [];
        $query = "";
        switch ($tipo) {
            case 'RAM':
                $query = "SELECT tipus AS nombre, GB AS gb_componente, preu FROM RAM WHERE (tipus, GB) NOT IN (SELECT tipusRAM, GBRam FROM PAAS)";
                break;
            case 'DISC_DUR':
                $query = "SELECT tipus AS nombre, GB AS gb_componente, preu FROM DISC_DUR WHERE (tipus, GB) NOT IN (SELECT tipusDD, GBDD FROM PAAS)";
                break;
            case 'CPU':
                $query = "SELECT model AS nombre, nNuclis AS gb_componente, preu FROM CPU WHERE (model, nNuclis) NOT IN (SELECT modelCPU, nNuclis FROM PAAS)";
                break;
            case 'SO':
                $query = "SELECT nom AS nombre, '' AS gb_componente, preu FROM SO WHERE nom NOT IN (SELECT nomSO FROM PAAS)";
                break;
        }
        $result = mysqli_query($this->conn, $query);
        while ($row = mysqli_fetch_assoc($result)) {
            $componentes[] = $row;
        }
        return $componentes;
    }

    public function updatePrecio($tipo, $nombre, $gb_componente, $precio) {
        if (empty($precio) || !is_numeric($precio)) {
            $_SESSION["warning_msg"] = "Precio inválido.";
            return false;
        }

        $query = "";
        switch ($tipo) {
            case 'RAM':
                if (empty($gb_componente)) {
                    $_SESSION["warning_msg"] = "Especificación inválida para RAM.";
                    return false;
                }
                $query = "UPDATE RAM SET preu = $precio WHERE tipus = '$nombre' AND GB = $gb_componente";
                break;
            case 'DISC_DUR':
                if (empty($gb_componente)) {
                    $_SESSION["warning_msg"] = "Especificación inválida para DISC DUR.";
                    return false;
                }
                $query = "UPDATE DISC_DUR SET preu = $precio WHERE tipus = '$nombre' AND GB = $gb_componente";
                break;
            case 'CPU':
                if (empty($gb_componente)) {
                    $_SESSION["warning_msg"] = "Especificación inválida para CPU.";
                    return false;
                }
                $query = "UPDATE CPU SET preu = $precio WHERE model = '$nombre' AND nNuclis = $gb_componente";
                break;
            case 'SO':
                $query = "UPDATE SO SET preu = $precio WHERE nom = '$nombre'";
                break;
        }xml_error_string

        if (mysqli_query($this->conn, $query)) {
            $_SESSION["success_msg"] = "Precio actualizado correctamente.";
            return true;
        } else {
            $_SESSION["error_msg"] = "Error al actualizar el precio: " . mysqli_error($this->conn);
            return false;
        }
    }

    // Funciones de test_management
    public function createTest($nombreTest, $descripcionTest, $idConfigProducte, $emailP) {
        $fechaCreacion = date('Y-m-d');

        if (empty($nombreTest) || empty($descripcionTest) || empty($idConfigProducte)) {
            $_SESSION["warning_msg"] = "Hay que rellenar todos los campos.";
            return false;
        }

        // Verificar si hay productos PaaS disponibles
        $query = "SELECT * FROM PAAS";
        $result = mysqli_query($this->conn, $query);

        if (mysqli_num_rows($result) == 0) {
            $_SESSION["error_msg"] = "No hay productos PaaS disponibles para realizar el test.";
            return false;
        }

        // Verificar si el test ya existe
        $query = "SELECT * FROM TEST WHERE nom = '$nombreTest'";
        $result = mysqli_query($this->conn, $query);

        if (mysqli_num_rows($result) > 0) {
            $_SESSION["error_msg"] = "El test con ese nombre ya existe.";
            return false;
        }

        // Verificar si el producto PaaS ya tiene un test asociado
        $query = "SELECT * FROM ESTAT WHERE idConfigProducte = $idConfigProducte";
        $result = mysqli_query($this->conn, $query);

        if (mysqli_num_rows($result) > 0) {
            $_SESSION["error_msg"] = "El producto PaaS seleccionado ya tiene un test asociado.";
            return false;
        }

        // Iniciar transacción
        mysqli_begin_transaction($this->conn);

        // Insertar nuevo test
        $query = "INSERT INTO TEST (nom, descripcio, dataCreacio) VALUES ('$nombreTest', '$descripcionTest', '$fechaCreacion')";
        if (mysqli_query($this->conn, $query)) {
            // Insertar estado inicial del test
            $query = "INSERT INTO ESTAT (estat, nomT, idConfigProducte) VALUES ('Pendent', '$nombreTest', $idConfigProducte)";
            if (mysqli_query($this->conn, $query)) {
                // Insertar registro en PERSONAL_REALITZA_TEST
                $query = "INSERT INTO PERSONAL_REALITZA_TEST (emailP, nomT) VALUES ('$emailP', '$nombreTest')";
                if (mysqli_query($this->conn, $query)) {
                    mysqli_commit($this->conn);
                    $_SESSION["success_msg"] = "Test creado exitosamente.";
                    return true;
                } else {
                    mysqli_rollback($this->conn);
                    $_SESSION["error_msg"] = "Error al registrar la realización del test.";
                    return false;
                }
            } else {
                mysqli_rollback($this->conn);
                $_SESSION["error_msg"] = "Error al insertar el estado del test.";
                return false;
            }
        } else {
            mysqli_rollback($this->conn);
            $_SESSION["error_msg"] = "Error al insertar el test.";
            return false;
        }
    }

    public function updateTestStatus($nombreTest, $nuevoEstado) {
        $query = "UPDATE ESTAT SET estat = '$nuevoEstado' WHERE nomT = '$nombreTest'";
        if (mysqli_query($this->conn, $query)) {
            $_SESSION["success_msg"] = "Estado del test actualizado correctamente.";
        } else {
            $_SESSION["error_msg"] = "Error al actualizar el estado del test: " . mysqli_error($this->conn);
        }
    }
}
?>