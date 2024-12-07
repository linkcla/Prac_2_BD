<?php
session_start();
require_once "conexion.php";
$conn = Conexion::getConnection();

$nomGrup = $_POST['nom'];
$privilegis = $_POST['privilegis'];

// Verificar que el grup no existesqui
$checkGrup = "SELECT nom FROM GRUP WHERE nom = '{$nomGrup}'";
$result = mysqli_query($conn, $checkGrup);

if (mysqli_num_rows($result) > 0) {
    $_SESSION['error_msg'] = "El grupo ya estaba creado";
    header("Location: gestUsForm.php");
    exit();
}

// Insertar el grup
$insertGrup = "INSERT INTO GRUP (nom) VALUES ('{$nomGrup}')";
if (!mysqli_query($conn, $insertGrup)) {
    $msg = "Error al insertar el grupo: " . mysqli_error($conn);
    $_SESSION['error_msg'] = $msg;
    header("Location: gestUsForm.php");
    exit();
}

// Insertar els privilegis al grup
foreach ($privilegis as $privilegi) {
    $insertPrivDeGrup = "INSERT INTO PRIV_DE_GRUP (tipusPriv, nomG) VALUES ('{$privilegi}', '{$nomGrup}')";
    if (!mysqli_query($conn, $insertPrivDeGrup)) {
        $msg = "Error al insertar el privilegio: " . mysqli_error($conn);
        $_SESSION['error_msg'] = $msg;
        header("Location: gestUsForm.php");
        exit();
    }
}

$msg = "Grupo y privilegios creados correctamente.";
$_SESSION['success_msg'] = $msg;

header("Location: gestUsForm.php");
exit();
?>