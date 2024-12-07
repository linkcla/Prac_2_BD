<?php
session_start();
require_once "conexion.php";
$conn = Conexion::getConnection();


$nomGrup = $_POST['nom'];
$privilegis = isset($_POST['privilegis']) ? $_POST['privilegis'] : [];



// Eliminar los privilegios actuales del grupo
$deletePrivilegis = "DELETE FROM PRIV_DE_GRUP WHERE nomG = '$nomGrup'";
if (!mysqli_query($conn, $deletePrivilegis)) {
    $msg= "Error al eliminar los privilegios actuales: " . mysqli_error($conn);
    $_SESSION['error_msg'] = $msg;
    header("Location: editarGrupForm.php?nom=$nomGrup");
    exit();
}

// Insertar los nuevos privilegios en la tabla PRIV_DE_GRUP
foreach ($privilegis as $privilegi) {
    $insertPrivDeGrup = "INSERT INTO PRIV_DE_GRUP (tipusPriv, nomG) VALUES ('$privilegi', '$nomGrup')";
    if (!mysqli_query($conn, $insertPrivDeGrup)) {
        $msg = "Error al insertar el privilegio: " . mysqli_error($conn);
        $_SESSION['error_msg'] = $msg;
        header("Location: editarGrupForm.php?nom=$nomGrup");
        exit();
    }
}


$_SESSION['success_msg'] = "Privilegios actualizados correctamente.";
header("Location: gestUsForm.php");
exit();
