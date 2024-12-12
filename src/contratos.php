<?php
require_once 'conexio.php';

class Contratos {
    public static function actualizarEstadoSaaS($idContracte, $estatName) {
        $conn = Conexion::getConnection();

        //Comprovar si la persona que administra el contrato existeix
        $emailCreador = isset($_POST['email']) ? $_POST['email'] : null;

        if($emailCreador == null) {
            $_SESSION["error_msg"] = "Error al intentar actualizar el contrato, no se ha registrado ningun usuario del personal.";
            return false;
        }

        $selectQuery= "SELECT emailP FROM PERSONAL_ADMINISTRA_CONTR WHERE emailP = '$emailCreador'";
        $result= mysqli_query($conn, $selectQuery);
        if(mysqli_num_rows($result) == 0) {
            $insertQuery = "INSERT INTO PERSONAL_ADMINISTRA_CONTR (emailP, idContracte) VALUES ('$emailCreador', '$idContracte');";
            if(mysqli_query($conn, $insertQuery) == false) {
                $_SESSION["error_msg"] = "Error al insertar la la persona que esta administrando el contrato en este momento.";
                return false;
            };
    
        }

        $update_check_estat_Query = "UPDATE CONTRACTE SET estat = '$estatName' WHERE idContracte = '$idContracte' ";
        if(mysqli_query($conn, $update_check_estat_Query) == false) {
            $_SESSION["error_msg"] = "Error al actualizar el contrato.";
            return false;
        };
        $_SESSION["success_msg"] = "Contrato actualizado.";
        return true;        
    }

    public static function actualizarDuradaSaaS($idContracte, $durada) {
        $conn = Conexion::getConnection();

        //Comprovar si la persona que administra el contrato existeix
        $emailCreador = $_SESSION["email"];
        $selectQuery= "SELECT emailP FROM PERSONAL_ADMINISTRA_CONTR WHERE emailP = '$emailCreador'";
        $result= mysqli_query($conn, $selectQuery);
        if(mysqli_num_rows($result) == 0) {
            $insertQuery = "INSERT INTO PERSONAL_ADMINISTRA_CONTR (emailP, idContracte) VALUES ('$emailCreador', '$idContracte');";
            if(mysqli_query($conn, $insertQuery) == false) {
                $_SESSION["error_msg"] = "Error al insertar la la persona que esta administrando el contrato en este momento.";
                return false;
            };
    
        }

        //Comprovar si la durada existeix
        $selectQuery= "SELECT mesos FROM DURADA WHERE mesos = '$durada'";
        $result= mysqli_query($conn, $selectQuery);
        if(mysqli_num_rows($result) == 0) {
            $insertQuery = "INSERT INTO DURADA (mesos) VALUES ('$durada');";
            if(mysqli_query($conn, $insertQuery) == false) {
                $_SESSION["error_msg"] = "Error al insertar la durada.";
                return false;
            };
        }

        //Actualitzar la durada del contracte
        $update_checkQuery = "UPDATE CONTRACTE SET mesos = '$durada' WHERE idContracte = '$idContracte'";
        if(mysqli_query($conn, $update_checkQuery) == false) {
            $_SESSION["error_msg"] = "Error al actualizar el contrato.";
            return false;
        };
        
        $_SESSION["success_msg"] = "Contrato actualizado.";
        return true;        
    }



}
?>