<?php
session_start();
require_once  './../productoSaaS.php';

$accion = $_POST['accio'];

// Elige la accion a realizar
switch ($accion) {
    case 'crear':
        crearPaaS();
        break;
    case 'editar':
        editarPaaS();
        break;
    case 'eliminar':
        eliminarPaaS();
        break;
}


function crearPaaS() {

    $res = PaaS::crear(
        $_POST['tipo_ram'],
        $_POST['gb_ram'],
        $_POST['tipo_disc_dur'],
        $_POST['gb_disc_dur'],
        $_POST['model_cpu'],
        $_POST['n_nuclis_cpu'],
        $_POST['nom_so'],
        $_POST['tipo_ipv'],
        $_POST['direccion_ipv'],
        $_POST['nombre_producto'],
        $_POST['descripcion_producto']
        
    );

    // Redirecciona a la pagina 
    header("Location: ./../../servicesPaaSPersonalCreatePaaSform.php");
    exit();
}


function editarPaaS() {
    $tipoGbRam = $_POST['tipo_gb_ram'];
    list($tipoRam, $gbRam) = explode('|', $tipoGbRam);

    $tipoGbDiscDur = $_POST['tipo_gb_disc_dur'];
    list($tipoDiscDur, $gbDiscDur) = explode('|', $tipoGbDiscDur);

    $res = PaaS::editar(
        $_POST['idConfig'],
        $_POST['dominio'],
        $tipoCMS = $_POST['tipo_cms'],
        $tipoCDN = $_POST['tipo_cdn'],
        $tipoSSL = $_POST['tipo_ssl'],
        $tipoSGBD = $_POST['tipo_sgbd'],
        $tipoRam,
        $gbRam,
        $tipoDiscDur,
        $gbDiscDur
    );
    
    header("Location: ./../../servicesPaaSViewform.php");
    exit();
}

function eliminarPaaS() {
    $valorSeleccionat = $_POST['selectedRow'];
    list(
        $idConfig,
        $domini,
        $dataCreacio,
        $tipusMCMS,
        $tipusCDN,
        $tipusSSL,
        $tipusSGBD,
        $ram,
        $disc,
        $testNoms,
        $testEstats
    ) = explode('|', $valorSeleccionat);

    $res = PaaS::eliminar($idConfig);

    header("Location: ./../../servicesSaaSViewform.php");
    exit();
}

?>