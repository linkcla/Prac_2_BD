<!-- @Author: Pau Toni Bibiloni Martínez -->

<?php
session_start();
include "conexion.php";
$conn = Conexion::getConnection();

$action = isset($_POST['action']) ? $_POST['action'] : null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'delete_components') {
    $selectedRam = isset($_POST['selectedRam']) ? $_POST['selectedRam'] : [];
    $selectedDiscDur = isset($_POST['selectedDiscDur']) ? $_POST['selectedDiscDur'] : [];
    $selectedCpu = isset($_POST['selectedCpu']) ? $_POST['selectedCpu'] : [];
    $selectedSo = isset($_POST['selectedSo']) ? $_POST['selectedSo'] : [];

    if (empty($selectedRam) && empty($selectedDiscDur) && empty($selectedCpu) && empty($selectedSo)) {
        $_SESSION["warning_msg"] = "Debes seleccionar al menos un componente.";
    } else {
        $error_ocurred = false;
        // Iniciar transacción
        mysqli_begin_transaction($conn);

        // Eliminar RAM seleccionada
        foreach ($selectedRam as $ram) {
            list($tipus, $GB) = explode(',', $ram);
            $tipus = mysqli_real_escape_string($conn, $tipus);
            $GB = mysqli_real_escape_string($conn, $GB);
            $deleteRamQuery = "DELETE FROM RAM WHERE tipus = '$tipus' AND GB = '$GB'";
            if (!mysqli_query($conn, $deleteRamQuery)) {
                $error_ocurred = true;
                break;
            }
        }

        // Eliminar Disco Duro seleccionado
        if (!$error_ocurred) {
            foreach ($selectedDiscDur as $discDur) {
                list($tipus, $GB) = explode(',', $discDur);
                $tipus = mysqli_real_escape_string($conn, $tipus);
                $GB = mysqli_real_escape_string($conn, $GB);
                $deleteDiscDurQuery = "DELETE FROM DISC_DUR WHERE tipus = '$tipus' AND GB = '$GB'";
                if (!mysqli_query($conn, $deleteDiscDurQuery)) {
                    $error_ocurred = true;
                    break;
                }
            }
        }

        // Eliminar CPU seleccionada
        if (!$error_ocurred) {
            foreach ($selectedCpu as $cpu) {
                list($model, $nNuclis) = explode(',', $cpu);
                $model = mysqli_real_escape_string($conn, $model);
                $nNuclis = mysqli_real_escape_string($conn, $nNuclis);
                $deleteCpuQuery = "DELETE FROM CPU WHERE model = '$model' AND nNuclis = '$nNuclis'";
                if (!mysqli_query($conn, $deleteCpuQuery)) {
                    $error_ocurred = true;
                    break;
                }
            }
        }

        // Eliminar SO seleccionado
        if (!$error_ocurred) {
            foreach ($selectedSo as $so) {
                $nom = mysqli_real_escape_string($conn, $so);
                $deleteSoQuery = "DELETE FROM SO WHERE nom = '$nom'";
                if (!mysqli_query($conn, $deleteSoQuery)) {
                    $error_ocurred = true;
                    break;
                }
            }
        }

        if ($error_ocurred) {
            // Revertir la transacción si hubo error
            mysqli_rollback($conn);
            $_SESSION["error_msg"] = "Error al eliminar los componentes seleccionados.";
        } else {
            // Confirmar la transacción si todo salió bien
            mysqli_commit($conn);
            $_SESSION["success_msg"] = "Los componentes seleccionados se han eliminado correctamente.";
        }
    }
} else {
    $_SESSION["error_msg"] = "Acción no válida.";
}

// Redirigir siempre después de procesar
header("Location: ./servicesPaaSPersonalDeleteStockComponentesform.php");
exit();
?>