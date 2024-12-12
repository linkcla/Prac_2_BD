<!-- @Author: Pau Toni Bibiloni Martínez -->

<?php
include_once "conexion.php";

class PaaSFuncionalidades {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    //-------------------------------------FUNCIONES DE CREATE_NEW_RAM--------------------------------------
    public function crearNuevaRAM($tipo) {
        if ($tipo) {
            // Verificar si el tipo de RAM ya existe
            $checkQuery = "SELECT * FROM RAM WHERE tipus = '$tipo'";
            $checkResult = mysqli_query($this->conn, $checkQuery);
            
            if (mysqli_num_rows($checkResult) > 0) {
                echo "<div class='alert alert-danger' role='alert'>El tipo de RAM ya existe.</div>";

            } else {
                $query = "INSERT INTO RAM (tipus, GB, preu) VALUES ('$tipo', 0, 0)";
                if (mysqli_query($this->conn, $query)) {
                    echo "<div class='alert alert-success' role='alert'>Nuevo tipo de RAM añadido exitosamente.</div>";

                } else {
                    echo "Error al añadir el nuevo tipo de RAM: " . mysqli_error($this->conn);
                }
            }
        } else {
            echo "<div class='alert alert-warning' role='alert'>El campo Tipo de RAM es obligatorio.</div>";
        }
    }

    //-----------------------------------------FUNCIONES DE CREATE_PAAS---------------------------------------
    public function crearPaaS($conn, $tipoRam, $gbRam, $tipoDiscDur, $gbDiscDur, $modelCpu, $nNuclisCpu, $nomSo, $tipoIpv, $direccionIpv, $nombreProducto, $descripcionProducto) {
        // Consultas para obtener opciones y precios de los componentes seleccionados
        $res = mysqli_query($conn, "SELECT preu FROM RAM WHERE tipus = '$tipoRam' AND GB = '$gbRam'");
        $precioRam = $res->fetch_assoc()['preu'] ?? '';
    
        $res = mysqli_query($conn, "SELECT preu FROM DISC_DUR WHERE tipus = '$tipoDiscDur' AND GB = '$gbDiscDur'");
        $precioDiscDur = $res->fetch_assoc()['preu'] ?? '';
    
        $res = mysqli_query($conn, "SELECT preu FROM CPU WHERE model = '$modelCpu' AND nNuclis = '$nNuclisCpu'");
        $precioCpu = $res->fetch_assoc()['preu'] ?? '';
    
        $res = mysqli_query($conn, "SELECT preu FROM SO WHERE nom = '$nomSo'");
        $precioSo = $res->fetch_assoc()['preu'] ?? '';
    
        // Variables para almacenar las direcciones iPv4 e iPv6
        $iPv4 = $tipoIpv === 'iPv4' ? $direccionIpv : '';
        $iPv6 = $tipoIpv === 'iPv6' ? $direccionIpv : '';
    
        // Validación de los campos de iPv4 e iPv6
        if ($tipoIpv === 'iPv4' && !preg_match('/^[0-9.]+$/', $direccionIpv)) {
            return "<div class='alert alert-danger' role='alert'>Dirección IPv4 no válida.</div>";
        } elseif ($tipoIpv === 'iPv6' && !preg_match('/^[0-9.]+$/', $direccionIpv)) {
            return "<div class='alert alert-danger' role='alert'>Dirección IPv6 no válida.</div>";
        } else {
            // Validación de los campos obligatorios
            if ($tipoRam && $gbRam && $tipoDiscDur && $gbDiscDur && $modelCpu && $nNuclisCpu && $nomSo && $tipoIpv && $direccionIpv && $nombreProducto && $descripcionProducto) {
                
                // Insertar los datos del producto
                $queryProducte = "INSERT INTO PRODUCTE (nom, descripcio) VALUES ('$nombreProducto', '$descripcionProducto')";
    
                // Si se ha insertado correctamente el producto
                if (mysqli_query($conn, $queryProducte)) {
                    // Obtener el id del producto insertado
                    $idConfig = mysqli_insert_id($conn);
    
                    // Insertar los datos del PaaS
                    $queryPaas = "INSERT INTO PAAS (idConfig, iPv4, iPv6, tipusRAM, GBRam, tipusDD, GBDD, modelCPU, nNuclis, nomSO) 
                    VALUES ($idConfig, '$iPv4', '$iPv6', '$tipoRam', $gbRam, '$tipoDiscDur', $gbDiscDur, '$modelCpu', '$nNuclisCpu', '$nomSo')";
    
                    // Si se ha insertado correctamente el PaaS
                    if (mysqli_query($conn, $queryPaas)) {
                        // Cuando se inicia sesion, en la clase loginform.php se guarda el email en la variable $_SESSION['email']
                        $emailP = $_SESSION['email'];
    
                        // Verificar si el email existe en la tabla 'personal'
                        $queryEmail = "SELECT email FROM PERSONAL WHERE email = '$emailP'";
                        $res = mysqli_query($conn, $queryEmail);
    
                        // Si el email existe en la tabla 'personal'
                        if ($res->num_rows > 0) {
                            // Insertar los datos de la creación del producto
                            $queryPersonalCreaProducte = "INSERT INTO PERSONAL_CREA_PRODUCTE (emailP, idConfigProducte) VALUES ('$emailP', $idConfig)";
                            
                            // Si se ha insertado correctamente la creación del producto
                            if (mysqli_query($conn, $queryPersonalCreaProducte)) {
                                return "<div class='alert alert-success' role='alert'>PaaS creado exitosamente.</div>";
                            } else {
                                return "<p>Error al registrar la creación del producto: " . mysqli_error($conn) . "</p>";
                            }
                        } else {
                            return "<div class='alert alert-danger' role='alert'>Error: El email no existe en la tabla 'personal'.</div>";
                        }
                    } else {
                        return "<p>Error al crear el PaaS: " . mysqli_error($conn) . "</p>";
                    }
                } else {
                    return "<p>Error al crear el producto: " . mysqli_error($conn) . "</p>";
                }
            } else {
                return "<div class='alert alert-warning' role='alert'>Todos los campos son obligatorios.</div>";
            }
        }
    }

    //----------------------------------FUNCIONES DE CREATE_STOCK_COMPONENTES-----------------------------------
    public function crearComponente($conn, $component, $tipo, $gb, $nNuclis, $precio) {
        $query = "";
        $existsQuery = "";
    
        switch ($component) {
            case 'RAM':
                if ($gb) {
                    $existsQuery = "SELECT * FROM RAM WHERE tipus = '$tipo' AND GB = $gb";
                    $query = "INSERT INTO RAM (tipus, GB, preu) VALUES ('$tipo', $gb, $precio)";
                }
                break;
            case 'DISC_DUR':
                if ($gb) {
                    $existsQuery = "SELECT * FROM DISC_DUR WHERE tipus = '$tipo' AND GB = $gb";
                    $query = "INSERT INTO DISC_DUR (tipus, GB, preu) VALUES ('$tipo', $gb, $precio)";
                }
                break;
            case 'CPU':
                if ($nNuclis) {
                    $existsQuery = "SELECT * FROM CPU WHERE model = '$tipo' AND nNuclis = '$nNuclis'";
                    $query = "INSERT INTO CPU (model, nNuclis, preu) VALUES ('$tipo', '$nNuclis', $precio)";
                }
                break;
            case 'SO':
                $existsQuery = "SELECT * FROM SO WHERE nom = '$tipo'";
                $query = "INSERT INTO SO (nom, preu) VALUES ('$tipo', $precio)";
                break;
        }
    
        if ($existsQuery) {
            $existsResult = mysqli_query($conn, $existsQuery);
            if (mysqli_num_rows($existsResult) > 0) {
                echo "<div class='alert alert-danger' role='alert'>El componente ya existe.</div>";
            } else {
                if (mysqli_query($conn, $query)) {
                    echo "<div class='alert alert-success' role='alert'>Componente añadido exitosamente.</div>";
                } else {
                    echo "<p>Error al añadir el componente: " . mysqli_error($conn) . "</p>";
                }
            }
        }
    }
    

    //---------------------------------------FUNCIONES DE DELETE_PAAS---------------------------------------
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

    //----------------------------------------FUNCIONES DE DELETE_STOCK_COMPONENTES--------------------------------
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

    //---------------------------------------------FUNCIONES DE EDIT_PAAS-----------------------------------------
    public function updatePaaS($idConfig, $iPv4, $iPv6, $nomSO, $tipusRAM, $GBRam, $tipusDD, $GBDD, $modelCPU, $nNuclis) {
        // Validar que solo uno de los campos de dirección IP esté lleno
        if (!empty($iPv4) && !empty($iPv6)) {
            $_SESSION["error_msg"] = "Solo se puede tener una dirección IPv4 o IPv6, no ambas.";
            return false;
        }
        // Validar que las direcciones IP solo contengan números y el carácter '.'
        if (!empty($iPv4) && !preg_match('/^[0-9.]+$/', $iPv4)) {
            $_SESSION["error_msg"] = "La dirección IPv4 solo puede contener números y el carácter ' . '";
            return false;
        }
        if (!empty($iPv6) && !preg_match('/^[0-9.]+$/', $iPv6)) {
            $_SESSION["error_msg"] = "La dirección IPv6 solo puede contener números y el carácter ' . '";
            return false;
        }

        // Actualizamos los atributos del PaaS en la base de datos
        $updateQuery = "UPDATE PAAS SET iPv4='$iPv4', iPv6='$iPv6', nomSO='$nomSO', tipusRAM='$tipusRAM', GBRam='$GBRam', tipusDD='$tipusDD', GBDD='$GBDD', modelCPU='$modelCPU', nNuclis='$nNuclis' WHERE idConfig='$idConfig'";
        if (mysqli_query($this->conn, $updateQuery)) {
            $_SESSION["success_msg"] = "PaaS actualizado correctamente.";
            return true;
        } else {
            $_SESSION["error_msg"] = "Error al actualizar PaaS: " . mysqli_error($this->conn);
            return false;
        }
    }

    //-----------------------------------------FUNCIONES DE EDIT_STOCK_COMPONENTES--------------------------------
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
        }

        if (mysqli_query($this->conn, $query)) {
            $_SESSION["success_msg"] = "Precio actualizado correctamente.";
            return true;
        } else {
            $_SESSION["error_msg"] = "Error al actualizar el precio: " . mysqli_error($this->conn);
            return false;
        }
    }

    //-----------------------------------FUNCIONES DE TEST_MANAGEMENT-----------------------------------
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
                $_SESSION["error_msg"] = "Error al crear el estado del test.";
                return false;
            }
        } else {
            mysqli_rollback($this->conn);
            $_SESSION["error_msg"] = "Error al crear el test.";
            return false;
        }
    }

    public function updateTestStatus($nombreTest, $nuevoEstado) {
        $query = "UPDATE ESTAT SET estat = '$nuevoEstado' WHERE nomT = '$nombreTest'";
        if (mysqli_query($this->conn, $query)) {
            $_SESSION["success_msg"] = "Estado del test actualizado exitosamente.";
            return true;
        } else {
            $_SESSION["error_msg"] = "Error al actualizar el estado del test.";
            return false;
        }
    }

    public function deleteTest($nombreTest) {
        // Iniciar transacción
        mysqli_begin_transaction($this->conn);

        // Eliminar registros de PERSONAL_REALITZA_TEST
        $query = "DELETE FROM PERSONAL_REALITZA_TEST WHERE nomT = '$nombreTest'";
        if (!mysqli_query($this->conn, $query)) {
            mysqli_rollback($this->conn);
            $_SESSION["error_msg"] = "Error al eliminar los registros de PERSONAL_REALITZA_TEST.";
            return false;
        }

        // Eliminar registros de ESTAT
        $query = "DELETE FROM ESTAT WHERE nomT = '$nombreTest'";
        if (!mysqli_query($this->conn, $query)) {
            mysqli_rollback($this->conn);
            $_SESSION["error_msg"] = "Error al eliminar los registros de ESTAT.";
            return false;
        }

        // Eliminar registros de TEST
        $query = "DELETE FROM TEST WHERE nom = '$nombreTest'";
        if (mysqli_query($this->conn, $query)) {
            mysqli_commit($this->conn);
            $_SESSION["success_msg"] = "Test eliminado exitosamente.";
            return true;
        } else {
            mysqli_rollback($this->conn);
            $_SESSION["error_msg"] = "Error al eliminar el test.";
            return false;
        }
    }
}
?>
