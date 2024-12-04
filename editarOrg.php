<?php
session_start();
require_once "conexion.php";
$con = Conexion::getConnection();

$nom = $_POST['nom'];
$adreca = $_POST['adreca'];
$tlf = $_POST['tlf'];

// Miram si han agut canvis en les dades.
if ($adreca == $_POST['adrecaAnterior'] && $tlf == $_POST['telefonAnterior']) {
    $msg = "No s'ha modificat cap dada.";
    $_SESSION['noMod'] = $msg;
    header("Location: ./gestOrgForm.php");
    die($msg);
} else {
    $query = "UPDATE organitzacio SET adreca = '{$adreca}', telefon = '{$tlf}' WHERE nom = '{$nom}'";
    $result = mysqli_query($con, $query);
    if (!$result) {
        $msg = "Error al intentar modificar les dades sobr l'organització: " . $nom;
        $_SESSION["error_msg"] = $msg;
        header("Location: ./gestOrgForm.php");
        die($msg);
    }
    
    // Si arriba aquí ha anat be.
    $msg = "Dades de l'organització {$nom} modificades correctament.";
    $_SESSION["success_msg"] = $msg;
    header("Location: ./gestOrgForm.php");
    die($msg);
}
?>