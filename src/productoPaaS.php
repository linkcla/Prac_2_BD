<!-- @Author: Pau Toni Bibiloni Martínez -->
 
<?php
require_once 'conexio.php';

class PaaS {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    //---------------------------------------------FUNCIONES DE CREATE_PAAS-----------------------------------------
    public function crearPaaS($tipoRam, $gbRam, $tipoDiscDur, $gbDiscDur, $modelCpu, $nNuclisCpu, $nomSo, $tipoIpv, $direccionIpv, $nombreProducto, $descripcionProducto) {
        // Consultas para obtener opciones y precios de los componentes seleccionados
        $res = mysqli_query($this->conn, "SELECT preu FROM RAM WHERE tipus = '$tipoRam' AND GB = '$gbRam'");
        $precioRam = $res->fetch_assoc()['preu'] ?? '';
    
        $res = mysqli_query($this->conn, "SELECT preu FROM DISC_DUR WHERE tipus = '$tipoDiscDur' AND GB = '$gbDiscDur'");
        $precioDiscDur = $res->fetch_assoc()['preu'] ?? '';
    
        $res = mysqli_query($this->conn, "SELECT preu FROM CPU WHERE model = '$modelCpu' AND nNuclis = '$nNuclisCpu'");
        $precioCpu = $res->fetch_assoc()['preu'] ?? '';
    
        $res = mysqli_query($this->conn, "SELECT preu FROM SO WHERE nom = '$nomSo'");
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
                if (mysqli_query($this->conn, $queryProducte)) {
                    // Obtener el id del producto insertado
                    $idConfig = mysqli_insert_id($this->conn);
    
                    // Insertar los datos del PaaS
                    $queryPaas = "INSERT INTO PAAS (idConfig, iPv4, iPv6, tipusRAM, GBRam, tipusDD, GBDD, modelCPU, nNuclis, nomSO) 
                    VALUES ($idConfig, '$iPv4', '$iPv6', '$tipoRam', $gbRam, '$tipoDiscDur', $gbDiscDur, '$modelCpu', '$nNuclisCpu', '$nomSo')";
    
                    // Si se ha insertado correctamente el PaaS
                    if (mysqli_query($this->conn, $queryPaas)) {
                        // Cuando se inicia sesion, en la clase loginform.php se guarda el email en la variable $_SESSION['email']
                        $emailP = $_SESSION['email'];
    
                        // Verificar si el email existe en la tabla 'personal'
                        $queryEmail = "SELECT email FROM PERSONAL WHERE email = '$emailP'";
                        $res = mysqli_query($this->conn, $queryEmail);
    
                        // Si el email existe en la tabla 'personal'
                        if ($res->num_rows > 0) {
                            // Insertar los datos de la creación del producto
                            $queryPersonalCreaProducte = "INSERT INTO PERSONAL_CREA_PRODUCTE (emailP, idConfigProducte) VALUES ('$emailP', $idConfig)";
                            
                            // Si se ha insertado correctamente la creación del producto
                            if (mysqli_query($this->conn, $queryPersonalCreaProducte)) {
                                return "<div class='alert alert-success' role='alert'>PaaS creado exitosamente.</div>";
                            } else {
                                return "<p>Error al registrar la creación del producto: " . mysqli_error($this->conn) . "</p>";
                            }
                        } else {
                            return "<div class='alert alert-danger' role='alert'>Error: El email no existe en la tabla 'personal'.</div>";
                        }
                    } else {
                        return "<p>Error al crear el PaaS: " . mysqli_error($this->conn) . "</p>";
                    }
                } else {
                    return "<p>Error al crear el producto: " . mysqli_error($this->conn) . "</p>";
                }
            } else {
                return "<div class='alert alert-warning' role='alert'>Todos los campos son obligatorios.</div>";
            }
        }
    }

    //---------------------------------------------FUNCIONES DE DELETE_PAAS-----------------------------------------
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
}