<!-- @Author: Blanca Atienzar Martinez (HTML, CSS y funcionalidad de SaaS) -->
<?php
session_start();
require_once './../componentes.php';

$accion = $_POST['accio'];

// Elige la accion a realizar
switch ($accion) {
    case 'crearS':
        crearSaaS();
        break;
    case 'eliminarS':
        eliminarSaaS();
        break;
    case 'editarS':
        editarSaaS();
        break;
}

function crearSaaS() {
    $component = $_POST['component'];
    $tipo = isset($_POST['tipo']) ? $_POST['tipo'] : null;
    $tipotipo = isset($_POST['tipotipo']) ? $_POST['tipotipo'] : null;
    $gb = isset($_POST['gb']) ? $_POST['gb'] : null;
    $precio = isset($_POST['precio']) ? $_POST['precio'] : null;
    echo "Debug: crearSaaS - Componente: $componente, Tipo: $tipo, Precio: $precio, GB: $gb<br>";
    
    $res = Componentes::crearComponentesSaas($component, $tipo, $tipotipo, $gb, $precio);

    // Redirecciona a la pagina 
    header("Location: ./../../servicesSaaSComponentesform.php");
    exit();
}

function eliminarSaaS() {
    $selectedRow = $_POST['selectedRow1'];
    list($componente, $tipo, $precio, $gb) = explode('|', $selectedRow);
    echo "Debug: eliminarSaaS - Componente: $componente, Tipo: $tipo, Precio: $precio, GB: $gb<br>";


    $res = Componentes::eliminarComponentesSaas($componente, $tipo, $gb);

    // Redirecciona a la pagina 
    header("Location: ./../../servicesSaaSComponentesform.php");
    exit();
}

function editarSaaS() {
    $selectedRow = explode('|', $_POST['selectedRow1']);
    $componente = $selectedRow[0];
    $tipo = $selectedRow[1];
    $gb = $selectedRow[3];
    $precioCambiar = $_POST['precio1'];
    echo "<p>Debug: editarSaaS - Componente: $componente, Tipo: $tipo, Precio: $precioCambiar, GB: $gb<br></p>";


    $res = Componentes::editarComponentesSaas($componente, $tipo, $gb, $precioCambiar);

    
    // Redirecciona a la pagina 
    header("Location: ./../../servicesSaaSComponentesform.php");
    exit();
}
?>