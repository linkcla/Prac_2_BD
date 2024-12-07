<!-- @Author: Pau Toni Bibiloni Martínez -->

<?php
session_start();
include "conexion.php";

class TestManager {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

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

$conn = Conexion::getConnection();
$testManager = new TestManager($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['crear_test'])) {
        $nombreTest = $_POST['nombre_test'];
        $descripcionTest = $_POST['descripcion_test'];
        $idConfigProducte = $_POST['idConfigProducte'];
        $emailP = $_SESSION['email'];
        $testManager->createTest($nombreTest, $descripcionTest, $idConfigProducte, $emailP);

    } elseif (isset($_POST['actualizar_estado'])) {
        $nombreTest = $_POST['nombre_test_seleccionado'];
        $nuevoEstado = $_POST['nuevo_estado'];
        $testManager->updateTestStatus($nombreTest, $nuevoEstado);

    } elseif (isset($_POST['eliminar_test'])) {
        $nombreTest = $_POST['nombre_test_seleccionado'];
        $testManager->deleteTest($nombreTest);
    }
    header("Location: servicesPaaSPersonalTestform.php");
    exit();
}
?>
