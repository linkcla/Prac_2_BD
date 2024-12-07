<!-- @Author: Pau Toni Bibiloni Martínez -->

<?php
session_start();
include "conexion.php";
$conn = Conexion::getConnection();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $idConfig = $_POST['idConfig'];
    $iPv4 = $_POST['iPv4'];
    $iPv6 = $_POST['iPv6'];
    $nomSO = $_POST['nomSO'];
    $tipusRAM = $_POST['tipusRAM'];
    $GBRam = $_POST['GBRam'];
    $tipusDD = $_POST['tipusDD'];
    $GBDD = $_POST['GBDD'];
    $modelCPU = $_POST['modelCPU'];
    $nNuclis = $_POST['nNuclis'];

    // Validar que solo uno de los campos de dirección IP esté lleno
    if (!empty($iPv4) && !empty($iPv6)) {
        $_SESSION["error_msg"] = "Solo se puede tener una dirección IPv4 o IPv6, no ambas.";
    // Validar que las direcciones IP solo contengan números y el carácter '.'
    } elseif (!empty($iPv4) && !preg_match('/^[0-9.]+$/', $iPv4)) {
        $_SESSION["error_msg"] = "La dirección IPv4 solo puede contener números y el carácter ' . '";
    } elseif (!empty($iPv6) && !preg_match('/^[0-9.]+$/', $iPv6)) {
        $_SESSION["error_msg"] = "La dirección IPv6 solo puede contener números y el carácter ' . '";
    // Actualizamos los atributos del PaaS en la base de datos
    } else {
        $updateQuery = "UPDATE PAAS SET iPv4='$iPv4', iPv6='$iPv6', nomSO='$nomSO', tipusRAM='$tipusRAM', GBRam='$GBRam', tipusDD='$tipusDD', GBDD='$GBDD', modelCPU='$modelCPU', nNuclis='$nNuclis' WHERE idConfig='$idConfig'";
        if (mysqli_query($conn, $updateQuery)) {
            $_SESSION["success_msg"] = "PaaS actualizado correctamente.";
            header("Location: servicesPaaSPersonalInicioEditform.php");
            exit();
        } else {
            $_SESSION["error_msg"] = "Error al actualizar PaaS: " . mysqli_error($conn);
        }
    }
    // Aquí se llega cuando hay un error con los iPv4 o iPv6 o con la actualización de la base de datos
    header("Location: servicesPaaSPersonalEditform.php?idConfig=$idConfig");
    exit();
}
?>