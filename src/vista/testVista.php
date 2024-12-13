<!-- @Author: Blanca Atienzar Martinez -->
<?php
session_start();
require_once  './../test.php';

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
    $testName = $_POST['testName'];
    $testDescription = $_POST['testDescription'];
    $currentDate = date('Y-m-d H:i:s');

    $res = Test::crearTestSaas($testName, $testDescription, $currentDate);

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
