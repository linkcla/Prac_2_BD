<?php
require_once 'conexio.php';

class Organitzacio {

    public static function crear($nom, $adreca, $tlf) {
        $conn = Conexion::getConnection();

        $query = "SELECT nom FROM organitzacio WHERE nom = '{$nom}'";
        $result = mysqli_query($conn, $query);

        // Error en la conexion
        if (!$result) {
            $_SESSION['error_msg'] = "Error al intentar obtenir dades sobre organitzacions: " . mysqli_error($conn);
            return false;
        }

        // La organizaicon ya existe
        if (mysqli_num_rows($result) > 0) {
            $_SESSION['error_msg'] = "La organización ya existe";
            return false;
        }

        // Query para crear la organizacion
        $query = "INSERT INTO organitzacio (nom, adreca, telefon) VALUES ('{$nom}', '{$adreca}', '{$tlf}')";
        $result = mysqli_query($conn, $query);

        // Comprobar si se ha creado la organizacion
        if (!$result) {
            $_SESSION['error_msg'] = "Error al insertar l'organizació: " . mysqli_error($conn);
            return false;
        }
        
        // Exito
        $_SESSION['success_msg'] = "Organització creada correctament";
        return true;
    }

    
    public static function editar($nom, $adreca, $tlf, $adrecaAnterior, $tlfAnterior) {
        $conn = Conexion::getConnection();
        
        // Comprobar si ha habido cambiso en la direccion o telefono
        if ($adreca == $adrecaAnterior && $tlf == $tlfAnterior) {
            $_SESSION['noMod'] = "No s'ha modificat cap dada.";
            return false;
        }

        // Actualizar la informacion de la organizacion
        $query = "UPDATE organitzacio SET adreca = '{$adreca}', telefon = '{$tlf}' WHERE nom = '{$nom}'";
        $result = mysqli_query($conn, $query);

        // Comprobar si se modificado la organizacion
        if (!$result) {
            $_SESSION['error_msg'] = "Error al intentar modificar les dades sobre l'organització: " . $nom;
        }
        
        // Exito
        $_SESSION['success_msg'] = "Dades de l'organització {$nom} modificades correctament.";
        return true;
    }

    public static function eliminar($nom) {
        $conn = Conexion::getConnection();
        
        // Eliminar la organizacion
        $query = "DELETE FROM organitzacio WHERE nom = '{$nom}'";
        $result = mysqli_query($conn, $query);

        // Comprobar si se ha eleminado la organizacion
        if (!$result) {
            $_SESSION['error_msg'] = "Error al intentar eliminar l'organització: " . $nom;
            return false;
        }

        // Exito
        $_SESSION['success_msg'] = "Organització {$nom} eliminada correctament.";
        return true;
    }

    public static function getOrganitzacions() {
        $conn = Conexion::getConnection();
        $query = "SELECT * FROM organitzacio";
        $result = mysqli_query($conn, $query);

        if (!$result) {
            $_SESSION['error_msg'] = "Error al obtener datos de las organizaciones: " . mysqli_error($conn);
            return false;
        }

        return $result;
    }
}
?>