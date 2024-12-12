<!-- @Author: Hai Zi Bibiloni Trobat -->
<?php
session_start();
require_once  './../usuari.php';

$accion = $_POST['accio'];

// Elige la accion a realizar
switch ($accion) {
    case 'crear':
        crearUsuario();
        break;
    case 'editarPersonal':
        editarPersonal();
        break;
    case 'editarUsuari':
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

    $res = Usuari::crear($nom, $cognom, $email, $contrasenya);

    header("Location: ./../../loginform.php");
    exit();
}

function editarUsuario() {
    $email = $_POST['email'];
    $nom = isset($_POST['nom']) ? $_POST['nom'] : null;
    $cognom = isset($_POST['cognom']) ? $_POST['cognom'] : null;
    $contrasenya = isset($_POST['contrasenya']) ? $_POST['contrasenya'] : null;
    
    $res = Usuari::editarU($email, $nom, $cognom, $contrasenya);
    
    header("Location: ./../../servicesUsuariform.php");
    exit();
}

function editarPersonal() {
    $email = $_POST['email'];
    $nom = $_POST['nom'];
    $cognom = $_POST['cognom'];
    $contrasenya = $_POST['contrasenya'];
    
    $res = Usuari::editarP($email, $nom, $cognom, $contrasenya);
    
    header("Location: ./../../servicesPersonalForm.php");
    exit();
}

function eliminarUsuario() {
    $email = $_SESSION['email'];

    $res = Usuari::eliminar($email);

    header("Location: ./../../loginform.php");
    exit();
}


?>