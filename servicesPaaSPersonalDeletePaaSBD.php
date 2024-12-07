<!-- @Author: Pau Toni Bibiloni Martínez -->

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

            // Obtener el idConfig del PaaS
            $query = "SELECT idConfig FROM PAAS WHERE idConfig = '$id'";
            $result = mysqli_query($conn, $query);
            if ($result && $row = mysqli_fetch_assoc($result)) {
                $idConfig = $row['idConfig'];

                // Eliminar registros de PERSONAL_CREA_PRODUCTE
                $deletePersonalCreaProducteQuery = "DELETE FROM PERSONAL_CREA_PRODUCTE WHERE idConfigProducte = '$idConfig'";
                if (!mysqli_query($conn, $deletePersonalCreaProducteQuery)) {
                    $error_ocurred = true;
                    break;
                }

                // Eliminar registro de PAAS
                $deletePaasQuery = "DELETE FROM PAAS WHERE idConfig = '$id'";
                if (!mysqli_query($conn, $deletePaasQuery)) {
                    $error_ocurred = true;
                    break;
                }

                // Eliminar registro de PRODUCTE
                $deleteProducteQuery = "DELETE FROM PRODUCTE WHERE idConfig = '$idConfig'";
                if (!mysqli_query($conn, $deleteProducteQuery)) {
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
            mysqli_rollback($conn);
            $_SESSION["error_msg"] = "Error al eliminar el PaaS seleccionado.";
        } else {
            // Confirmar la transacción si todo salió bien
            mysqli_commit($conn);
            $_SESSION["success_msg"] = "El PaaS seleccionado se eliminó correctamente.";
        }
    } else {
        $_SESSION["warning_msg"] = "Debes seleccionar un PaaS.";
    }
} else {
    $_SESSION["error_msg"] = "Acción no válida.";
}

// Redirigir siempre después de procesar
header("Location: ./servicesPaaSPersonalDeletePaaSform.php");
exit();
?>