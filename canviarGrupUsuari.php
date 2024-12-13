<!-- Author: Marc -->
<?php

session_start();
require_once "./src/conexio.php";
$conn = Conexion::getConnection();

$email = $_POST['email'];
$grup = $_POST['grup'];

$query = "UPDATE usuari SET grup = '{$grup}' WHERE email = '{$email}'";
$result = mysqli_query($conn, $query);
if (!$result) {
    $msg = "Error al intentar canviar el grup de l'usuari";
    $_SESSION['error_msg'] = $msg;
    header("Location: gestUsForm.php");
    die($msg);
}

$msg = "Grup de l'usuari canviat correctament.";
$_SESSION['success_msg'] = $msg;
header("Location: gestUsForm.php");
die($msg);
?>