<!-- @Author: Blanca Atienzar Martinez (HTML, CSS y funcionalidad de SaaS) -->

<?php
session_start();
include "conexion.php";

$conn = Conexion::getConnection();

$selectedRows = isset($_POST['selectedRows']) ? $_POST['selectedRows'] : null;
$action = isset($_POST['action']) ? $_POST['action'] : null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'delete') {
    if ($selectedRows && is_array($selectedRows)) {
        $error_ocurred = false;

        // Iniciar transacción
        mysqli_begin_transaction($conn);

        foreach ($selectedRows as $id) {
            // Sanitiza el ID
            $id = mysqli_real_escape_string($conn, $id);

            // Actualizar el estado de los contratos asociados a "Cancelat"
            $updateQuery = "UPDATE CONTRACTE SET estat = 'Cancel·lat' WHERE idConfigProducte = '$id'";
            if (!mysqli_query($conn, $updateQuery)) {
                $error_ocurred = true;
                break;
            }

            // Eliminar registro de SAAS
            $deleteQuery = "DELETE FROM SAAS WHERE idConfig = '$id'";
            if (!mysqli_query($conn, $deleteQuery)) {
                $error_ocurred = true;
                break;
            }
        }

        if ($error_ocurred) {
            // Revertir la transacción si hubo error
            mysqli_rollback($conn);
            $message = "Error al eliminar los registros seleccionados.";
            $_SESSION["error_msg"] = $message;
            header("Location: ./servicesSaaSDeleteform.php");
            die($message);
        } else {
            // Confirmar la transacción si todo salió bien
            mysqli_commit($conn);
            $message = "Se ha eliminado correctamente";
            $_SESSION["success_msg"] =  $message;
            header("Location: ./servicesSaaSDeleteform.php");
            die($message);
        }
    } else {
        $message = "No se seleccionaron registros para eliminar.";
        $_SESSION["error_msg"] =   $message;
        header("Location: ./servicesSaaSDeleteform.php");
        die($message);
    }
} else {
    $message = "Acción no válida.";
    $_SESSION["error_msg"] = $message;
    header("Location: ./servicesSaaSDeleteform.php");
    die($message);

}


?>

