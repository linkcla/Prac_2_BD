<!-- @Author: Pau Toni Bibiloni Martínez -->
<!-- @Author: Blanca Atienzar Martínez -->

<?php
require_once 'conexio.php';

class Contratos {
    public static function actualizarEstadoSaaS($idContracte, $estatName) {
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

    public function actualizarContratoPaaS($conn, $idContracte, $nuevoEstat, $nuevosMesos) {
        if ($this->validarMesosPaaS($nuevosMesos)) {
            $this->insertarDuradaPaaS($conn, $nuevosMesos);
            return $this->actualizarContractePaaS($conn, $idContracte, $nuevoEstat, $nuevosMesos);
        } else {
            $_SESSION["error_msg"] = "La duración debe ser un número positivo y al menos 3 meses.";
            return false;
        }
    }

    private function validarMesosPaaS($mesos) {
        return is_numeric($mesos) && $mesos >= 3;
    }

    private function insertarDuradaPaaS($conn, $mesos) {
        $selectQuery = "SELECT mesos FROM DURADA WHERE mesos = '$mesos'";
        $result = mysqli_query($conn, $selectQuery);
        if (mysqli_num_rows($result) == 0) {
            $insertQuery = "INSERT INTO DURADA (mesos) VALUES ('$mesos')";
            if (mysqli_query($conn, $insertQuery) == false) {
                $_SESSION["error_msg"] = "Error al insertar la durada.";
                return false;
            }
        }
        return true;
    }

    private function actualizarContractePaaS($conn, $idContracte, $estat, $mesos) {
        $updateQuery = "UPDATE CONTRACTE SET estat = '$estat', mesos = '$mesos' WHERE idContracte = '$idContracte'";
        if (mysqli_query($conn, $updateQuery) == false) {
            $_SESSION["error_msg"] = "Error al actualizar el contrato.";
            return false;
        }
        $_SESSION["success_msg"] = "Contrato actualizado.";
        return true;
    }

    public function obtenerContratosPaaS($conn) {
        $cadenaContracte = "SELECT c.idContracte, c.dataInici, c.estat, c.nom, c.emailU, c.idConfigProducte, c.mesos
                            FROM CONTRACTE c
                            JOIN PAAS s ON c.idConfigProducte = s.idConfig";
        return mysqli_query($conn, $cadenaContracte);
    }

}
?>