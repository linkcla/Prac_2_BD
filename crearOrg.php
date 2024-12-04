<?php session_start();
require_once "conexion.php";

$con = Conexion::getConnection();
$nom = $_POST['nom'];
$direc = $_POST['direccio'];
$tlf = $_POST['tlf'];

$query = "SELECT nom FROM organitzacio WHERE nom = '{$nom}' ";
$result = mysqli_query($con, $query);

if (!$result) {
    $msg = "Error al intentar obtenir dades sobre organitzacions: " . mysqli_error($con);
    $_SESSION["error_msg"] = $msg;
    header("Location: ./gestOrgForm.php");
    die($msg);
}

// Comprovar si la organització ja existeix.
if (mysqli_num_rows($result) > 0) {
    $msg = "La organización ya existe";
    $_SESSION["error_msg"] = $msg;
    header("Location: ./gestOrgForm.php");
    die($msg);
}

$query = "INSERT INTO organitzacio (nom, adreca, telefon) VALUES ('{$nom}', '{$direc}', '{$tlf}')";
$result = mysqli_query($con, $query);

if (!$result) {
    $msg = "Error al insertar l'organizació: " . mysqli_error($con);
    $_SESSION["error_msg"] = $msg;
    header("Location: ./gestOrgForm.php");
    die($msg);
}

$msg = "Organització inserida correctament";
$_SESSION["success_msg"] = $msg;
header("Location: ./gestOrgForm.php");
die($msg);
?>