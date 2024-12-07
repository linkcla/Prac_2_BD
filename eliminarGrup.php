<?php
session_start();
require_once "conexion.php";
$conn = Conexion::getConnection();

$nomGrup = $_POST['grup'];

// Verificar si hay usuarios asociados al grupo
$checkUsuaris = "SELECT email FROM USUARI WHERE grup = '$nomGrup'";
$resultUsuaris = mysqli_query($conn, $checkUsuaris);

if (mysqli_num_rows($resultUsuaris) > 0) {
    $_SESSION['error_msg'] = "No se puede eliminar el grupo porque hay usuarios asociados a él.";
    header("Location: eliminarGrupForm.php");
    exit();
}

// Eliminar los privilegios del grupo en la tabla PRIV_DE_GRUP
$deletePrivilegis = "DELETE FROM PRIV_DE_GRUP WHERE nomG = '$nomGrup'";
if (!mysqli_query($conn, $deletePrivilegis)) {
    $msg = "Error al eliminar los privilegios del grupo: " . mysqli_error($conn);
    $_SESSION['error_msg'] = $msg;
    header("Location: eliminarGrupForm.php");
    exit();
}

// Eliminar el grupo en la tabla GRUP
$deleteGrup = "DELETE FROM GRUP WHERE nom = '$nomGrup'";
if (!mysqli_query($conn, $deleteGrup)) {
    $msg = "Error al eliminar el grupo: " . mysqli_error($conn);
    $_SESSION['error_msg'] = $msg;
    header("Location: eliminarGrupForm.php");
    exit();
}


$_SESSION['success_msg'] = "Grupo eliminado correctamente.";
header("Location: eliminarGrupForm.php");
exit();
