<!-- Author: Marc -->
<!-- 
    Aqui poden accedir el Personal i l'usuari que tengui pertanyi al grup d'administrador en la seva organització.
    Descripció: Aquest fitxer s'encarrega d'eliminar un usuari d'una organització.
    Un cop eliminat, s'eliminen els el grup al que pertanyia en aquella organització.
-->
<?php
session_start();
require_once "conexion.php";
$conn = Conexion::getConnection();


// Agafar l'email de l'usuari a eliminar
$email = $_POST['selectedRow'];

// Actualitzar la taula usuari per a que no pertanyi a cap organització
$query = "UPDATE usuari SET nomOrg = NULL WHERE email = '{$email}'";
$result = mysqli_query($conn, $query);

if (!$result) {
    $msg = "Error al intentar eliminar l'usuari de l'organització";
    $_SESSION['error_msg'] = $msg;
    header("Location: gestUsForm.php");
    die($msg);
}

// Actualitzar els permisos del grup al que pertanyia l'usuari
$query = "UPDATE usuari SET grup = NULL WHERE email = '{$email}'";
$result = mysqli_query($conn, $query);
if (!$result) {
    $msg = "Error al intentar eliminar els permisos de l'usuari";
    $_SESSION['error_msg'] = $msg;
    header("Location: gestUsForm.php");
    die($msg);
}


$msg = "Usuari eliminat de l'organització correctament.";
$_SESSION['success_msg'] = $msg;
header("Location: gestUsForm.php");
die($msg);

?>