<?php
class Organizacion {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function crear($nom, $adreca, $tlf) {
        $query = "SELECT nom FROM organitzacio WHERE nom = '{$nom}'";
        $result = mysqli_query($this->conn, $query);

        if (!$result) {
            throw new Exception("Error al intentar obtenir dades sobre organitzacions: " . mysqli_error($this->conn));
        }

        if (mysqli_num_rows($result) > 0) {
            throw new Exception("La organización ya existe");
        }

        $query = "INSERT INTO organitzacio (nom, adreca, telefon) VALUES ('{$nom}', '{$adreca}', '{$tlf}')";
        $result = mysqli_query($this->conn, $query);

        if (!$result) {
            throw new Exception("Error al insertar l'organizació: " . mysqli_error($this->conn));
        }

        return "Organització inserida correctament";
    }

    public function editar($nom, $adreca, $tlf, $adrecaAnterior, $tlfAnterior) {
        if ($adreca == $adrecaAnterior && $tlf == $tlfAnterior) {
            return "No s'ha modificat cap dada.";
        }

        $query = "UPDATE organitzacio SET adreca = '{$adreca}', telefon = '{$tlf}' WHERE nom = '{$nom}'";
        $result = mysqli_query($this->conn, $query);

        if (!$result) {
            throw new Exception("Error al intentar modificar les dades sobr l'organització: " . $nom);
        }

        return "Dades de l'organització {$nom} modificades correctament.";
    }

    public function eliminar($nom) {
        $query = "DELETE FROM organitzacio WHERE nom = '{$nom}'";
        $result = mysqli_query($this->conn, $query);

        if (!$result) {
            throw new Exception("Error al intentar eliminar l'organització: " . $nom);
        }

        return "Organització {$nom} eliminada correctament.";
    }
}
?>