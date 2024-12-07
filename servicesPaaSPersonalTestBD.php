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
        $emailP = $_SESSION['email']; // Obtener el email del personal desde la sesión

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
                                } else {
                                    mysqli_rollback($conn);
                                    $_SESSION["error_msg"] = "Error al registrar la realización del test.";
                                }
                            } else {
                                mysqli_rollback($conn);
                                $_SESSION["error_msg"] = "Error al crear el estado del test.";
                            }
                        } else {
                            mysqli_rollback($conn);
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

        // Iniciar transacción
        mysqli_begin_transaction($conn);

        // Eliminar registros de PERSONAL_REALITZA_TEST
        $query = "DELETE FROM PERSONAL_REALITZA_TEST WHERE nomT = '$nombreTest'";
        if (!mysqli_query($conn, $query)) {
            mysqli_rollback($conn);
            $_SESSION["error_msg"] = "Error al eliminar los registros de PERSONAL_REALITZA_TEST.";
            header("Location: servicesPaaSPersonalTestform.php");
            exit();
        }

        // Eliminar registros de ESTAT
        $query = "DELETE FROM ESTAT WHERE nomT = '$nombreTest'";
        if (!mysqli_query($conn, $query)) {
            mysqli_rollback($conn);
            $_SESSION["error_msg"] = "Error al eliminar los registros de ESTAT.";
            header("Location: servicesPaaSPersonalTestform.php");
            exit();
        }

        // Eliminar registros de TEST
        $query = "DELETE FROM TEST WHERE nom = '$nombreTest'";
        if (mysqli_query($conn, $query)) {
            mysqli_commit($conn);
            $_SESSION["success_msg"] = "Test eliminado exitosamente.";
        } else {
            mysqli_rollback($conn);
            $_SESSION["error_msg"] = "Error al eliminar el test.";
        }
    }
    header("Location: servicesPaaSPersonalTestform.php");
    exit();
}
?>
