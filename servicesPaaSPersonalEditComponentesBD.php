<!-- @Author: Pau Toni Bibiloni Martínez -->

<?php
session_start();
include "conexion.php";

class EditComponentes {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getComponentes() {
        $componentes = [];
        $query = "SELECT 'RAM' AS tipo, tipus AS nombre, GB AS gb_componente, preu FROM RAM
                  UNION ALL
                  SELECT 'DISC_DUR' AS tipo, tipus AS nombre, GB AS gb_componente, preu FROM DISC_DUR
                  UNION ALL
                  SELECT 'CPU' AS tipo, model AS nombre, nNuclis AS gb_componente, preu FROM CPU
                  UNION ALL
                  SELECT 'SO' AS tipo, nom AS nombre, '' AS gb_componente, preu FROM SO";
        $result = mysqli_query($this->conn, $query);
        while ($row = mysqli_fetch_assoc($result)) {
            $componentes[] = $row;
        }
        return $componentes;
    }

    public function getComponentesByTipo($tipo) {
        $componentes = [];
        $query = "";
        switch ($tipo) {
            case 'RAM':
                $query = "SELECT tipus AS nombre, GB AS gb_componente, preu FROM RAM WHERE (tipus, GB) NOT IN (SELECT tipusRAM, GBRam FROM PAAS)";
                break;
            case 'DISC_DUR':
                $query = "SELECT tipus AS nombre, GB AS gb_componente, preu FROM DISC_DUR WHERE (tipus, GB) NOT IN (SELECT tipusDD, GBDD FROM PAAS)";
                break;
            case 'CPU':
                $query = "SELECT model AS nombre, nNuclis AS gb_componente, preu FROM CPU WHERE (model, nNuclis) NOT IN (SELECT modelCPU, nNuclis FROM PAAS)";
                break;
            case 'SO':
                $query = "SELECT nom AS nombre, '' AS gb_componente, preu FROM SO WHERE nom NOT IN (SELECT nomSO FROM PAAS)";
                break;
        }
        $result = mysqli_query($this->conn, $query);
        while ($row = mysqli_fetch_assoc($result)) {
            $componentes[] = $row;
        }
        return $componentes;
    }

    public function updatePrecio($tipo, $nombre, $gb_componente, $precio) {
        if (empty($precio) || !is_numeric($precio)) {
            $_SESSION["warning_msg"] = "Precio inválido.";
            return false;
        }

        $query = "";
        switch ($tipo) {
            case 'RAM':
                if (empty($gb_componente)) {
                    $_SESSION["warning_msg"] = "Especificación inválida para RAM.";
                    return false;
                }
                $query = "UPDATE RAM SET preu = $precio WHERE tipus = '$nombre' AND GB = $gb_componente";
                break;
            case 'DISC_DUR':
                if (empty($gb_componente)) {
                    $_SESSION["warning_msg"] = "Especificación inválida para DISC DUR.";
                    return false;
                }
                $query = "UPDATE DISC_DUR SET preu = $precio WHERE tipus = '$nombre' AND GB = $gb_componente";
                break;
            case 'CPU':
                if (empty($gb_componente)) {
                    $_SESSION["warning_msg"] = "Especificación inválida para CPU.";
                    return false;
                }
                $query = "UPDATE CPU SET preu = $precio WHERE model = '$nombre' AND nNuclis = '$gb_componente'";
                break;
            case 'SO':
                $query = "UPDATE SO SET preu = $precio WHERE nom = '$nombre'";
                break;
        }
        return mysqli_query($this->conn, $query);
    }
}

$conn = Conexion::getConnection();
$editComponentes = new EditComponentes($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_precio'])) {
    $tipo = $_POST['component'];
    $nombre = $_POST['nombre'];
    $gb_componente = $_POST['gb_componente'];
    $precio = $_POST['precio'];
    if ($editComponentes->updatePrecio($tipo, $nombre, $gb_componente, $precio)) {
        $_SESSION["success_msg"] = "Precio actualizado exitosamente.";
    } else {
        if (!isset($_SESSION["warning_msg"])) {
            $_SESSION["error_msg"] = "Error al actualizar el precio.";
        }
    }
    header("Location: servicesPaaSPersonalEditComponentesform.php");
    exit();
}
?>