<!-- @Author: Blanca Atienzar Martinez (HTML, CSS y funcionalidad de SaaS) -->
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
}
?>