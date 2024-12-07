<?php
session_start();
require_once "conexion.php";
$conn = Conexion::getConnection();


$usuari = $_POST['usuari'];
$grup = $_POST['grup'];
$nomOrg = $_SESSION['nomOrg']; 


$updateUsuari = "UPDATE USUARI SET nomOrg = '{$nomOrg}', grup = '{$grup}' WHERE email = '{$usuari}'";
if (mysqli_query($conn, $updateUsuari)) {
    $_SESSION['success_msg'] = "Usuari afegit correctament a l'organització.";
} else {
    $_SESSION['error_msg'] = "Error al afegir l'usuari: " . mysqli_error($conn);
}

header("Location: gestUsForm.php");
exit();

