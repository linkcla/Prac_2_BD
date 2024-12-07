<?php
session_start();
require_once "conexion.php";
$conn = Conexion::getConnection();

$_SESSION['nomOrg'] = $_POST['nomOrg'];
// Agafar l'email de l'usuari a eliminar
$email = $_POST['selectedRow'];
    
$query = "UPDATE usuari SET nomOrg = NULL WHERE email = '{$email}'";
$result = mysqli_query($conn, $query);

if (!$result) {
    $msg = "Error al intentar eliminar l'usuari de l'organització";
    $_SESSION['error_msg'] = $msg;
    header("Location: gestUsForm.php");
    die($msg);

}
$msg = "Usuari eliminat de l'organització correctament.";
$_SESSION['success_msg'] = $msg;
header("Location: gestUsForm.php");
die($msg);

?>