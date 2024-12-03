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
            $_SESSION["error_msg"] = "Error al eliminar los registros seleccionados.";
        } else {
            // Confirmar la transacción si todo salió bien
            mysqli_commit($conn);
            $_SESSION["success_msg"] = "Los registros seleccionados se eliminaron correctamente.";
        }
    } else {
        $_SESSION["error_msg"] = "No se seleccionaron registros para eliminar.";
    }
} else {
    $_SESSION["error_msg"] = "Acción no válida.";
}

// Redirigir siempre después de procesar
header("Location: ./servicesSaaSDeleteform.php");
exit();