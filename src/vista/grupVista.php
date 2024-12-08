<?php
session_start();
require_once  './../grup.php';

$accion = $_POST['accio'];

// Elige la accion a realizar
switch ($accion) {
    case 'crear':
        crearGrup();
        break;
    case 'editar':
        editarGrup();
        break;
    case 'eliminar':
        eliminarGrup();
        break;
}


function crearGrup() {
    // Crea la organizacion
    $res = Grup::crear(
        $_POST['nom'],
        $_POST['privilegis'],
        $_SESSION['nomOrg']
    );

    // Redirecciona a la pagina 
    header("Location: ./../../gestUsForm.php");
    exit();
}


function editarGrup() {
    $res = Grup::editar(
        $_POST['nom'],
        $_POST['privilegis'],
        $_SESSION['nomOrg']
    );
    
    header("Location: ./../../gestUsForm.php");
    exit();
}

function eliminarGrup() {
    $res = Grup::eliminar($_POST['grup'], $_SESSION['nomOrg']);

    header("Location: ./../../gestUsForm.php");
    exit();
}

?>