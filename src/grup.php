<?php
require_once 'conexio.php';

class Grup {

    public static function crear($nomGrup, $privilegis, $nomOrg) {
        $conn = Conexion::getConnection();

        // Verificar que el grup no existesqui
        $checkGrup = "SELECT nom FROM GRUP WHERE nom = '{$nomGrup}' AND nomOrg = '{$nomOrg}'";
        $result = mysqli_query($conn, $checkGrup);
        if (mysqli_num_rows($result) > 0) {
            $_SESSION['error_msg'] = "El grupo ya estaba creado";
            return false;
        }

        $insertGrup = "INSERT INTO GRUP (nom, nomOrg) VALUES ('{$nomGrup}', '{$nomOrg}')";
        if (!mysqli_query($conn, $insertGrup)) {
            $_SESSION['error_msg'] = "Error al insertar el grupo.";
            return false;
        }

        // Insertar els privilegis al grup
        $insertPrivDeGrup = "INSERT INTO PRIV_DE_GRUP (tipusPriv, nomG, nomOrg) VALUES ";
        foreach ($privilegis as $privilegi) $insertPrivDeGrup .= " ('$privilegi', '$nomGrup', '$nomOrg'),";
        // Quitamos la última coma
        $insertPrivDeGrup = rtrim($insertPrivDeGrup, ',');

        if (!mysqli_query($conn, $insertPrivDeGrup)) {
            $_SESSION['error_msg'] = "Error al insertar el privilegio.";
            return false;
        }

        // Exito
        $_SESSION['success_msg'] = "Grup i privilegis creats correctament";
        return true;
    }

    
    public static function editar($nom, $privilegis, $nomOrg) {
        $conn = Conexion::getConnection();
        
        // Eliminar los privilegios actuales del grupo
        $deletePrivilegis = "DELETE FROM PRIV_DE_GRUP WHERE nomG = '$nom' AND nomOrg = '$nomOrg'";
        if (!mysqli_query($conn, $deletePrivilegis)) {
            $_SESSION['error_msg'] = "Error al eliminar los privilegios actuales.";
            return false;
        }

        $insertPrivDeGrup = "INSERT INTO PRIV_DE_GRUP (tipusPriv, nomG, nomOrg) VALUES";
        foreach ($privilegis as $privilegi) $insertPrivDeGrup .= " ('$privilegi', '$nom', '$nomOrg'),";
        // Quitamos la última coma
        $insertPrivDeGrup = rtrim($insertPrivDeGrup, ',');

        if (!mysqli_query($conn, $insertPrivDeGrup)) {
            $_SESSION['error_msg'] = "Error al insertar el privilegio.";
            return false;
        }
        
        // Exito
        $_SESSION['success_msg'] = "Dades del grup {$nom} modificades correctament.";
        return true;
    }

    public static function eliminar($nom, $nomOrg) {
        $conn = Conexion::getConnection();
        
        // Revisar que el grup no tengui usuaris asociats.
        $checkUsuaris = "SELECT email FROM USUARI WHERE grup = '$nom'";
        $resultUsuaris = mysqli_query($conn, $checkUsuaris);
        if (mysqli_num_rows($resultUsuaris) > 0) {
            $_SESSION['error_msg'] = "No se puede eliminar el grupo porque hay usuarios asociados a él.";
            return false;
        }

        // Eliminar los privilegios del grupo en la tabla PRIV_DE_GRUP
        $deletePrivilegis = "DELETE FROM PRIV_DE_GRUP WHERE nomG = '$nom' AND nomOrg = '$nomOrg'";
        if (!mysqli_query($conn, $deletePrivilegis)) {
            $_SESSION['error_msg'] = "Error al eliminar los privilegios del grupo.";
            return false;
        }

        $deleteGrup = "DELETE FROM GRUP WHERE nom = '$nom' AND nomOrg = '$nomOrg'";
        if (!mysqli_query($conn, $deleteGrup)) {
            $_SESSION['error_msg'] = "Error al eliminar el grupo.";
            return false;
        }

        $_SESSION['success_msg'] = "Grupo eliminado correctamente.";
        return true;
    }

}
?>