<!-- @Author: Blanca Atienzar Martinez (HTML, CSS y funcionalidad de SaaS) -->

<?php
session_start();
include "conexion.php";

$conn = Conexion::getConnection();


$valorSeleccionat = $_POST['selectedRow'];
list(
    $idConfig,
    $domini,
    $dataCreacio,
    $tipusMCMS,
    $tipusCDN,
    $tipusSSL,
    $tipusSGBD,
    $ram,
    $disc,
    $testNoms,
    $testEstats
) = explode('|', $valorSeleccionat);
    $error_ocurred = false;

    // Comprobar que el estado de los contratos asociados a "Cancelat"
    $updateQuery = "SELECT idContracte FROM CONTRACTE WHERE idConfigProducte='$idConfig'";
    $result = mysqli_query($conn, $updateQuery);
    if (mysqli_num_rows($result) > 0) {
        $message = "Error al eliminar el producto. El producto esta contratado.";
        $_SESSION["error_msg"] = $message;
        header("Location: ./servicesSaaSViewform.php");
        die($message);
    }

    // Eliminar registro de SAAS
    $deleteQuery = "DELETE FROM SAAS WHERE idConfig = '$idConfig'";
    if (!mysqli_query($conn, $deleteQuery)) {
        $message = "Error al eliminar el producto.";
        $_SESSION["error_msg"] = $message;
        header("Location: ./servicesSaaSViewform.php");
        die($message);
    }

     // Eliminar registro de personal_crea_producte
     $deleteQuery = "DELETE FROM PERSONAL_CREA_PRODUCTE WHERE idConfigProducte = '$idConfig'";
     if (!mysqli_query($conn, $deleteQuery)) {
         $message = "Error al eliminar el producto de personal_crea_producte.";
         $_SESSION["error_msg"] = $message;
         header("Location: ./servicesSaaSViewform.php");
         die($message);
     }

    // Eliminar registro de estat
    $deleteQuery = "DELETE FROM ESTAT WHERE idConfigProducte = '$idConfig'";
    if (!mysqli_query($conn, $deleteQuery)) {
        $message = "Error al eliminar el producto de estat.";
        $_SESSION["error_msg"] = $message;
        header("Location: ./servicesSaaSViewform.php");
        die($message);
    }

    // Eliminar registro de producte
    $deleteQuery = "DELETE FROM PRODUCTE WHERE idConfig = '$idConfig'";
    if (!mysqli_query($conn, $deleteQuery)) {
        $message = "Error al eliminar el producto de producte.";
        $_SESSION["error_msg"] = $message;
        header("Location: ./servicesSaaSViewform.php");
        die($message);
    }

    // Confirmar la transacción si todo salió bien
    mysqli_commit($conn);
    $message = "Se ha eliminado correctamente";
    $_SESSION["success_msg"] =  $message;
    header("Location: ./servicesSaaSViewform.php");
    die($message);

?>

