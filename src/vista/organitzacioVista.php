<!-- @Author: Marc -->
<?php
session_start();
require_once  './../organitzacio.php';

$accion = $_POST['accio'];

// Elige la accion a realizar
switch ($accion) {
    case 'crear':
        crearOrganizacion();
        break;
    case 'editar':
        editarOrganizacion();
        break;
    case 'eliminar':
        eliminarOrganizacion();
        break;
}


function crearOrganizacion() {
    // Crea la organizacion
    $res = Organitzacio::crear(
        $_POST['nom'],
        $_POST['direccio'],
        $_POST['tlf']
    );

    // Redirecciona a la pagina 
    header("Location: ./../../gestOrgForm.php");
    exit();
}


function editarOrganizacion() {
    $res = Organitzacio::editar(
        $_POST['nom'],
        $_POST['adreca'],
        $_POST['tlf'],
        $_POST['adrecaAnterior'],
        $_POST['telefonAnterior']
    );
    
    header("Location: ./../../gestOrgForm.php");
    exit();
}

function eliminarOrganizacion() {
    $valorSeleccionat = $_POST['selectedRow'];
    list($nom, $adreca, $telefon) = explode("|", $valorSeleccionat);
    $res = Organitzacio::eliminar($nom);
    
    header("Location: ./../../gestOrgForm.php");
    exit();
}

?>