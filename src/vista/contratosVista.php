<!-- @Author: Blanca Atienzar Martinez -->
<?php
session_start();
require_once  './../contratos.php';

$accion = $_POST['accio'];

// Elige la accion a realizar
switch ($accion) {
    case 'estado':
        actualizarEstadoSaaS();
        break;
    case 'durada':
        actualizarDuradaSaaS();
        break;
}

function actualizarEstadoSaaS() {
    $idContracte =  $_POST['selectedRow'];
    $estatName = $_POST['nomsestats'];  

    $res = Contratos::actualizarEstadoSaaS($idContracte, $estatName);

    header("Location: ./../../servicesSaaSContratosform.php");
    exit();
}

function actualizarDuradaSaaS() {
    $idContracte =  $_POST['selectedRow'];
    $durada = $_POST['duration'];  

    $res = Contratos::actualizarDuradaSaaS($idContracte, $durada);

    header("Location: ./../../servicesSaaSContratosform.php");
    exit();
}

?>
