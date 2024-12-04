<?php
session_start();
require_once "conexion.php";
$con = Conexion::getConnection();
$valorSeleccionat = $_POST['selectedRow'];
list($nom, $adreca, $telefon) = explode("|", $valorSeleccionat);
$query = "DELETE FROM organitzacio WHERE nom = '{$nom}'";
$result = mysqli_query($con, $query);
if (!$result) {
    $msg = "Error al intentar eliminar l'organització: " . $nom;
    $_SESSION["error_msg"] = $msg;
    header("Location: ./gestOrgForm.php");
    die($msg);
}

// Si arriba aquí ha anat be.
$msg = "Organització {$nom} eliminada correctament.";
$_SESSION["success_msg"] = $msg;
header("Location: ./gestOrgForm.php");
die($msg);
?>