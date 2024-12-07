<?php
session_start();
require_once "conexion.php";
require_once "Organizacion.php";

$con = Conexion::getConnection();
$organizacion = new Organizacion($con);

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $action = $_POST['action'];

        switch ($action) {
            case 'crear':
                $nom = $_POST['nom'];
                $adreca = $_POST['adreca'];
                $tlf = $_POST['tlf'];
                $msg = $organizacion->crear($nom, $adreca, $tlf);
                $_SESSION["success_msg"] = $msg;
                break;

            case 'editar':
                $nom = $_POST['nom'];
                $adreca = $_POST['adreca'];
                $tlf = $_POST['tlf'];
                $adrecaAnterior = $_POST['adrecaAnterior'];
                $tlfAnterior = $_POST['telefonAnterior'];
                $msg = $organizacion->editar($nom, $adreca, $tlf, $adrecaAnterior, $tlfAnterior);
                $_SESSION["success_msg"] = $msg;
                break;

            case 'eliminar':
                $nom = $_POST['nom'];
                $msg = $organizacion->eliminar($nom);
                $_SESSION["success_msg"] = $msg;
                break;

            default:
                throw new Exception("Acción no válida");
        }
    }
} catch (Exception $e) {
    $_SESSION["error_msg"] = $e->getMessage();
}

header("Location: ./gestOrgForm.php");
exit();
?>