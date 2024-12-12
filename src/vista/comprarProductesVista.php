<!-- @Author: Hai Zi Bibiloni Trobat -->
<?php
session_start();
require_once  './../comprarProducte.php';

$accion = $_POST['accio'];

// Elige la accion a realizar
switch ($accion) {
    case 'comprarPaaS':
        comprarP();
        break;
    case 'comprarSaaS':
        comprarS();
        break;
}


function comprarP() {
    $ipType = $_POST['ip']; // 'IPv4' o 'IPv6'
    $ipValue = $_POST['ipValue']; 
    $tipusRAM = $_POST['tipusRAM'];
    $gbRAM = $_POST['gbRAM'];
    $tipusDD = $_POST['tipusDD'];
    $gbDD = $_POST['gbDD'];
    $modelCPU = $_POST['modelCPU'];
    $nNuclis = $_POST['nNuclis'];
    $nomSO = $_POST['nomSO'];
    $emailU = $_SESSION['email']; 
    $nomOrg = $_SESSION['nomOrg']; 
    $estat = "Actiu";
    $mesos = $_POST['mesos']; 
    $dataInici = date("Y-m-d");

    $res = Usuari::comprarP($ipType, $ipValue, $tipusRAM, $gbRAM, $tipusDD, $gbDD, $modelCPU, $nNuclis, $nomSO, $emailU, $nomOrg, $estat, $mesos, $dataInici);

    header("Location: ./../../comprarProductesform.php");
    exit();
}

function comprarS() {
    $tipusMCMS = $_POST['tipusMCMS'];
    $tipusCDN = $_POST['tipusCDN'];
    $tipusSSL = $_POST['tipusSSL'];
    $tipusSGBD = $_POST['tipusSGBD'];
    $tipusRAM = $_POST['tipusRAM'];
    $gbRAM = $_POST['gbRAM'];
    $tipusDD = $_POST['tipusDD'];
    $gbDD = $_POST['gbDD'];
    $domini = $_POST['domini'];
    $emailU = $_SESSION['email']; 
    $nomOrg = $_SESSION['nomOrg']; 
    $estat = "Actiu";
    $mesos = $_POST['mesos']; 
    $dataInici = date("Y-m-d");
    
    $res = Usuari::comprarS($tipusMCMS, $tipusCDN, $tipusSSL, $tipusSGBD, $tipusRAM, $gbRAM, $tipusDD, $gbDD, $domini, $emailU, $nomOrg, $estat, $mesos, $dataInici);
    
    header("Location: ./../../comprarProductesform.php");
    exit();
}
?>