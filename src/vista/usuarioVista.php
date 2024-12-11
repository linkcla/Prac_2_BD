<!-- @Author: Blanca Atienzar Martinez (HTML, CSS y funcionalidad de SaaS) -->
<?php
session_start();
require_once  './../usuario.php';

$accion = $_POST['accio'];

// Elige la accion a realizar
switch ($accion) {
    case 'crear':
        crearUsuario();
        break;
    case 'editar':
        editarUsuario();
        break;
    case 'eliminar':
        eliminarUsuario();
        break;
}


function crearUsuario() {
    $nom = $_POST['nom'];
    $cognom = $_POST['cognom'];
    $email = $_POST['email'];
    $contrasenya = $_POST['contrasenya'];

    $res = Usuario::crear($nom, $cognom, $email, $contrasenya);

    header("Location: ./../../loginform.php");
    exit();
}

function editarUsuario() {
    
    $res = Usuario::editar();
    
    header("Location: ./../../servicesUsuariform.php");
    exit();
}

function eliminarUsuario() {

    $res = Usuario::eliminar();

    header("Location: ./../../loginform.php");
    exit();
}


?>