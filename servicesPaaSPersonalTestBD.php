<!-- @Author: Pau Toni Bibiloni Martínez -->

<?php 
session_start();
include "conexion.php";
$conn = Conexion::getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['crear_test'])) {
        $nombreTest = $_POST['nombre_test'];
        $descripcionTest = $_POST['descripcion_test'];
        $fechaCreacion = date('Y-m-d');
        $idConfigProducte = $_POST['idConfigProducte'];

        // Verificar si todos los campos están rellenados
        if (empty($nombreTest) || empty($descripcionTest) || empty($idConfigProducte)) {
            $_SESSION["warning_msg"] = "Hay que rellenar todos los campos.";
        } else {
            // Verificar si hay productos PaaS disponibles
            $query = "SELECT * FROM PAAS";
            $result = mysqli_query($conn, $query);

            if (mysqli_num_rows($result) == 0) {
                $_SESSION["error_msg"] = "No hay productos PaaS disponibles para realizar el test.";
            } else {
                // Verificar si el test ya existe
                $query = "SELECT * FROM TEST WHERE nom = '$nombreTest'";
                $result = mysqli_query($conn, $query);

                if (mysqli_num_rows($result) > 0) {
                    $_SESSION["error_msg"] = "El test con ese nombre ya existe.";
                } else {
                    // Verificar si el producto PaaS ya tiene un test asociado
                    $query = "SELECT * FROM ESTAT WHERE idConfigProducte = $idConfigProducte";
                    $result = mysqli_query($conn, $query);

                    if (mysqli_num_rows($result) > 0) {
                        $_SESSION["error_msg"] = "El producto PaaS seleccionado ya tiene un test asociado.";
                    } else {
                        // Insertar nuevo test
                        $query = "INSERT INTO TEST (nom, descripcio, dataCreacio) VALUES ('$nombreTest', '$descripcionTest', '$fechaCreacion')";
                        if (mysqli_query($conn, $query)) {
                            // Insertar estado inicial del test
                            $query = "INSERT INTO ESTAT (estat, nomT, idConfigProducte) VALUES ('Pendent', '$nombreTest', $idConfigProducte)";
                            if (mysqli_query($conn, $query)) {
                                $_SESSION["success_msg"] = "Test creado exitosamente.";
                            } else {
                                $_SESSION["error_msg"] = "Error al crear el estado del test.";
                            }
                        } else {
                            $_SESSION["error_msg"] = "Error al crear el test.";
                        }
                    }
                }
            }
        }
    } elseif (isset($_POST['actualizar_estado'])) {
        $nombreTest = $_POST['nombre_test_seleccionado'];
        $nuevoEstado = $_POST['nuevo_estado'];

        // Actualizar estado del test
        $query = "UPDATE ESTAT SET estat = '$nuevoEstado' WHERE nomT = '$nombreTest'";
        if (mysqli_query($conn, $query)) {
            $_SESSION["success_msg"] = "Estado del test actualizado exitosamente.";
        } else {
            $_SESSION["error_msg"] = "Error al actualizar el estado del test.";
        }
    } elseif (isset($_POST['eliminar_test'])) {
        $nombreTest = $_POST['nombre_test_seleccionado'];

        // Eliminar test
        $query = "DELETE FROM ESTAT WHERE nomT = '$nombreTest'";
        mysqli_query($conn, $query);

        $query = "DELETE FROM TEST WHERE nom = '$nombreTest'";
        if (mysqli_query($conn, $query)) {
            $_SESSION["success_msg"] = "Test eliminado exitosamente.";
        } else {
            $_SESSION["error_msg"] = "Error al eliminar el test.";
        }
    }
    header("Location: servicesPaaSPersonalTestform.php");
    exit();
}
?>