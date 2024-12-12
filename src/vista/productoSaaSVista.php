<!-- @Author: Blanca Atienzar Martinez (HTML, CSS y funcionalidad de SaaS) -->
<?php
session_start();
require_once  './../productoSaaS.php';

$accion = $_POST['accio'];

// Elige la accion a realizar
switch ($accion) {
    case 'crear':
        crearSaaS();
        break;
    case 'editar':
        editarSaaS();
        break;
    case 'eliminar':
        eliminarSaaS();
        break;
    case 'actualizar':
        actualizarEstadoSaaS();
        break;
    case 'eliminarTest':
        eliminarTestSaaS();
        break;
    case 'añadirTest':
        añadirTestSaaS();
        break;
}


function crearSaaS() {
    // Crea la organizacion

    $tipoGbRam = $_POST['tipo_gb_ram'] ;
    list($tipoRam, $gbRam) = explode('|', $tipoGbRam);

    $tipoGbDiscDur = $_POST['tipo_gb_disc_dur'] ;
    list($tipoDiscDur, $gbDiscDur) = explode('|', $tipoGbDiscDur);

    $testSelectedRows = isset($_POST['selectedRowstest']) ? $_POST['selectedRowstest'] : null;

    $res = SaaS::crear(
        $_POST['nombre'] ,
        $_POST['descipcio'] ,
        $_POST['tipo_cms'] ,
        $_POST['tipo_cdn'] ,
        $_POST['tipo_ssl'] ,
        $_POST['tipo_sgbd'] ,
        date('Y-m-d H:i:s'),
        $tipoRam,	
        $gbRam,	
        $tipoDiscDur,	
        $gbDiscDur,
        $_SESSION["email"],
        $testSelectedRows
    );

    // Redirecciona a la pagina 
    header("Location: ./../../servicesSaaSViewform.php");
    exit();
}


function editarSaaS() {
    $tipoGbRam = $_POST['tipo_gb_ram'];
    list($tipoRam, $gbRam) = explode('|', $tipoGbRam);

    $tipoGbDiscDur = $_POST['tipo_gb_disc_dur'];
    list($tipoDiscDur, $gbDiscDur) = explode('|', $tipoGbDiscDur);

    $res = SaaS::editar(
        $_POST['idConfig'],
        $tipoCMS = $_POST['tipo_cms'],
        $tipoCDN = $_POST['tipo_cdn'],
        $tipoSSL = $_POST['tipo_ssl'],
        $tipoSGBD = $_POST['tipo_sgbd'],
        $tipoRam,
        $gbRam,
        $tipoDiscDur,
        $gbDiscDur
    );
    
    header("Location: ./../../servicesSaaSViewform.php");
    exit();
}

function eliminarSaaS() {
    $valorSeleccionat = $_POST['selectedRow'];
    list(
        $idConfig,
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

    $res = SaaS::eliminar($idConfig);

    header("Location: ./../../servicesSaaSViewform.php");
    exit();
}

function actualizarEstadoSaaS() {
    list($idConfig, $testNom) = explode('|', $_POST['selectedRow']);
    $estatName = $_POST['nomsestats'];  

    $res = SaaS::actualizarEstado($idConfig, $testNom, $estatName);

    header("Location: ./../../servicesSaaSTestform.php");
    exit();
}

function eliminarTestSaaS(){
    list($idConfig, $testNom) = explode('|', $_POST['selectedRow']);
    $res = SaaS::eliminarTestProducto($idConfig, $testNom);

    header("Location: ./../../servicesSaaSTestform.php");
    exit();
}
function añadirTestSaaS(){
    $idConfig = $_POST['idConfigs'];
    $testNom = $_POST['noms']; 
   
    $res = SaaS::añadirTestProducto($idConfig, $testNom);

    header("Location: ./../../servicesSaaSTestform.php");
    exit();
}

?>