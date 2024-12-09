<!-- @Author: Blanca Atienzar Martinez (HTML, CSS y funcionalidad de SaaS) -->
<?php
session_start();
require_once  './../componentes.php';

$accion = $_POST['accio'];

// Elige la accion a realizar
switch ($accion) {
    case 'crearS':
        crearSaaS();
        break;
    case 'eliminarS':
        eliminarSaaS();
        break;
}


function crearSaaS() {
    

    // Redirecciona a la pagina 
    header("Location: ./../../servicesSaaSTestform.php");
    exit();
}

function eliminarSaaS() {
    $testName = $_POST['noms']; 

    $res = Test::eliminarTestSaas($testName);

    // Redirecciona a la pagina 
    header("Location: ./../../servicesSaaSTestform.php");
    exit();
}

?>