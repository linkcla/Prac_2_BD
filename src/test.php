<!-- @Author: Blanca Atienzar Martinez -->
<!-- @Author: Pau Toni Bibiloni Martínez -->

<?php
require_once 'conexio.php';

class Test {

    public static function crearTestSaas($testName, $testDescription, $currentDate) {
        $conn = Conexion::getConnection();

        $select_check_Query = "SELECT nom FROM TEST WHERE nom = '$testName'";
        $result_test = mysqli_query($conn, $select_check_Query);

        if(mysqli_num_rows($result_test) == 0) {
            // Insertar el nuevo test
            $insertQuery = "INSERT INTO TEST (nom, descripcio, dataCreacio) VALUES ('$testName', '$testDescription', '$currentDate')";
                            
            if(mysqli_query($conn, $insertQuery) == false) {
                $_SESSION["error_msg"] = "Error al crear el test.";
                return false;
            }
                            
            // Persona que ha creado el test
            $emailCreador = $_SESSION["email"];
            $insert_query_saas = "INSERT INTO PERSONAL_REALITZA_TEST (emailP, nomT) VALUES ('$emailCreador', '$testName');";
            $result_saas = mysqli_query($conn, $insert_query_saas);
            if (!$result_saas) {
                $_SESSION["error_msg"] = "Error al intentar crear el test, no se ha podido añadir la persona que lo ha creado";
                return false;
            }

            $_SESSION["success_msg"] = "Test creat.";
            return true;
        }else {
            $_SESSION["error_msg"] = "Error. Test ja creat.";
            return false;
        }
       
    }

    public static function eliminarTestSaas($testName) {
        $conn = Conexion::getConnection();

        // Verificar si el test existe
        $select_check_Query = "SELECT nom FROM TEST WHERE nom = '$testName'";
        $result_test = mysqli_query($conn, $select_check_Query);

        // Para Verificar si el test está en uso
        $select_check_estat_Query = "SELECT nomT FROM ESTAT WHERE nomT = '$testName'";
        $result_estat = mysqli_query($conn, $select_check_estat_Query);
                    
        if(mysqli_num_rows($result_test) != 0 && mysqli_num_rows($result_estat) == 0) {
            // Eliminar el  test
            $deleteQuery = "DELETE FROM PERSONAL_REALITZA_TEST WHERE nomT = '$testName'";
            if(mysqli_query($conn, $deleteQuery) == false) {
                $_SESSION["error_msg"] = "Error al eliminar el test.";
                return false;
            }

            $deleteQuery = "DELETE FROM TEST WHERE nom = '$testName'";
            if(mysqli_query($conn, $deleteQuery) == false) {
                $_SESSION["error_msg"] =  "Error al eliminar el test.";
                return false;
            }

            $_SESSION["success_msg"] = "Test eliminado.";
            return false;
        }else if(mysqli_num_rows($result_estat) != 0){
            $_SESSION["error_msg"] = "Error. Test en uso.";
            return false;
        }else {
            $_SESSION["error_msg"] = "Error. Test no eliminado.";
            return false;
        }
    }

    public static function createTestPaaS($nombreTest, $descripcionTest, $idConfigProducte, $emailP) {
        $conn = Conexion::getConnection();
        $fechaCreacion = date('Y-m-d');

        // Verificar si los campos están vacíos
        if (empty($nombreTest) || empty($descripcionTest) || empty($idConfigProducte)) {
            $_SESSION["warning_msg"] = "Hay que rellenar todos los campos.";
            return false;
        }

        // Verificar si hay productos PaaS disponibles
        $query = "SELECT * FROM PAAS";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) == 0) {
            $_SESSION["error_msg"] = "No hay productos PaaS disponibles para realizar el test.";
            return false;
        }

        // Verificar si el test ya existe
        $query = "SELECT * FROM TEST WHERE nom = '$nombreTest'";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {
            $_SESSION["error_msg"] = "El test con ese nombre ya existe.";
            return false;
        }

        // Verificar si el producto PaaS ya tiene un test asociado
        $query = "SELECT * FROM ESTAT WHERE idConfigProducte = $idConfigProducte";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {
            $_SESSION["error_msg"] = "El producto PaaS seleccionado ya tiene un test asociado.";
            return false;
        }

        // Iniciar transacción
        mysqli_begin_transaction($conn);

        // Insertar nuevo test
        $query = "INSERT INTO TEST (nom, descripcio, dataCreacio) VALUES ('$nombreTest', '$descripcionTest', '$fechaCreacion')";
        if (mysqli_query($conn, $query)) {
            // Insertar estado inicial del test
            $query = "INSERT INTO ESTAT (estat, nomT, idConfigProducte) VALUES ('Pendent', '$nombreTest', $idConfigProducte)";
            if (mysqli_query($conn, $query)) {
                // Insertar registro en PERSONAL_REALITZA_TEST
                $query = "INSERT INTO PERSONAL_REALITZA_TEST (emailP, nomT) VALUES ('$emailP', '$nombreTest')";
                if (mysqli_query($conn, $query)) {
                    mysqli_commit($conn);
                    $_SESSION["success_msg"] = "Test creado exitosamente.";
                    return true;
                } else {
                    mysqli_rollback($conn);
                    $_SESSION["error_msg"] = "Error al registrar la realización del test: " . mysqli_error($conn);
                    return false;
                }
            } else {
                mysqli_rollback($conn);
                $_SESSION["error_msg"] = "Error al crear el estado del test: " . mysqli_error($conn);
                return false;
            }
        } else {
            mysqli_rollback($conn);
            $_SESSION["error_msg"] = "Error al crear el test: " . mysqli_error($conn);
            return false;
        }
    }

    public static function updateTestStatusPaaS($nombreTest, $nuevoEstado) {
        $conn = Conexion::getConnection();
        $query = "UPDATE ESTAT SET estat = '$nuevoEstado' WHERE nomT = '$nombreTest'";
        if (mysqli_query($conn, $query)) {
            $_SESSION["success_msg"] = "Estado del test actualizado exitosamente.";
            return true;
        } else {
            $_SESSION["error_msg"] = "Error al actualizar el estado del test.";
            return false;
        }
    }

    public static function deleteTestPaaS($nombreTest) {
        $conn = Conexion::getConnection();
        // Iniciar transacción
        mysqli_begin_transaction($conn);

        // Eliminar registros de PERSONAL_REALITZA_TEST
        $query = "DELETE FROM PERSONAL_REALITZA_TEST WHERE nomT = '$nombreTest'";
        if (!mysqli_query($conn, $query)) {
            mysqli_rollback($conn);
            $_SESSION["error_msg"] = "Error al eliminar los registros de PERSONAL_REALITZA_TEST.";
            return false;
        }

        // Eliminar registros de ESTAT
        $query = "DELETE FROM ESTAT WHERE nomT = '$nombreTest'";
        if (!mysqli_query($conn, $query)) {
            mysqli_rollback($conn);
            $_SESSION["error_msg"] = "Error al eliminar los registros de ESTAT.";
            return false;
        }

        // Eliminar registros de TEST
        $query = "DELETE FROM TEST WHERE nom = '$nombreTest'";
        if (mysqli_query($conn, $query)) {
            mysqli_commit($conn);
            $_SESSION["success_msg"] = "Test eliminado exitosamente.";
            return true;
        } else {
            mysqli_rollback($conn);
            $_SESSION["error_msg"] = "Error al eliminar el test.";
            return false;
        }
    }

}
?>
