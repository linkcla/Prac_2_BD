<?php
require_once 'conexio.php';

class SaaS {

    public static function crear($dominio,$nom, $descripcion, $tipoCMS, $tipoCDN, $tipoSSL, $tipoSGBD, $currentDate, $tipoRam, $gbRam, $tipoDiscDur, $gbDiscDur, $emailCreador, $selectedRows) {
        $conn = Conexion::getConnection();
        
        $sql = "SELECT domini FROM SAAS WHERE domini = '$dominio'";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            $_SESSION["error_msg"] = "El dominio ya existe. Por favor, elige otro ";
            return false;
        }
        
        
        //Crea el producto
        $insert_query_producte = "INSERT INTO PRODUCTE (idConfig, nom, descripcio) VALUES (NULL, '$nom', '$descripcion');";
        
        $result = mysqli_query($conn, $insert_query_producte);

        if (!$result) {
            $_SESSION["error_msg"] = "Error al intentar crear el producto ";
            return false;
        }
        
        $idConf = mysqli_insert_id($conn);

        $insert_query_saas = "INSERT INTO SAAS (idConfig, domini, dataCreacio, tipusMCMS, tipusCDN, tipusSSL, tipusSGBD, tipusRam, GBRam, tipusDD, GBDD) VALUES 
        ('$idConf', '$dominio', '$currentDate', '$tipoCMS', '$tipoCDN', '$tipoSSL', '$tipoSGBD', '$tipoRam', '$gbRam', '$tipoDiscDur', '$gbDiscDur');";
        $result_saas = mysqli_query($conn, $insert_query_saas);

        // Persona que ha creado el producto
        $insert_query_saas = "INSERT INTO PERSONAL_CREA_PRODUCTE (emailP, idConfigProducte) VALUES ('$emailCreador', '$idConf');";
        $result_saas = mysqli_query($conn, $insert_query_saas);
        if (!$result_saas) {
            $_SESSION["error_msg"] = "Error al intentar crear el producto, no se ha podido añadir la persona que lo ha creado";
            return false;
        }

        //Añadir test
        if ($selectedRows) {
            foreach ($selectedRows as $id) {
                // Sanitiza el ID
                $id = mysqli_real_escape_string($conn, $id);

                // Insertar el test en la tabla ESTAT
                $insertQuery = "INSERT INTO ESTAT(estat, nomT, idConfigProducte) VALUES ('Pendent', '$id', '$idConf')";
                $result_tests = mysqli_query($conn, $insertQuery);
                if (!$result_tests) {
                    $_SESSION["error_msg"] = "Error al intentar crear el producto, no se ha podido añadir el test";
                    return false;
                }
            }
        }

        // Si todo ha ido bien
        $_SESSION["success_msg"] = "Producto creado correctamente.";
        return true;
    }

    
    public static function editar($idConfig, $dominio, $tipoCMS, $tipoCDN, $tipoSSL, $tipoSGBD, $tipoRam, $gbRam, $tipoDiscDur, $gbDiscDur) {
        $conn = Conexion::getConnection();
        
        $sql = "SELECT domini FROM SAAS WHERE domini = '$dominio' AND idConfig != '$idConfig'";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0 ) {
            $_SESSION["error_msg"] = "El dominio ya existe. Por favor, elige otro ";
            return false;
        }

        $update_query = "UPDATE SAAS 
        SET 
        domini = '$dominio',
        tipusMCMS = '$tipoCMS ',
        tipusCDN = '$tipoCDN ',
        tipusSSL = '$tipoSSL ',
        tipusSGBD = '$tipoSGBD ',
        tipusRAM = '$tipoRam ',
        GBRAM = '$gbRam',
        tipusDD = '$tipoDiscDur',
        GBDD = '$gbDiscDur'
        WHERE idConfig = '$idConfig'";
        $result = mysqli_query($conn, $update_query);

        if (!$result) {
            $_SESSION["error_msg"] = "Error al intentar modificar les dades ";
            return false;
        }
        
        // Si arriba aquí ha anat be.
        $_SESSION["success_msg"] = "Dades modificades correctament.";
        return true;
        
    }

    public static function eliminar($idConfig) {
        $conn = Conexion::getConnection();

        // Comprobar que el estado de los contratos asociados a "Cancelat"
        $updateQuery = "SELECT idContracte FROM CONTRACTE WHERE idConfigProducte='$idConfig'";
        $result = mysqli_query($conn, $updateQuery);
        if (mysqli_num_rows($result) > 0) {
            $_SESSION["error_msg"] = "Error al eliminar el producto. El producto esta contratado.";
            return false;
        }

        // Eliminar registro de SAAS
        $deleteQuery = "DELETE FROM SAAS WHERE idConfig = '$idConfig'";
        if (!mysqli_query($conn, $deleteQuery)) {
            $_SESSION["error_msg"] = "Error al eliminar el producto.";
            return false;
        }

        // Eliminar registro de personal_crea_producte
        $deleteQuery = "DELETE FROM PERSONAL_CREA_PRODUCTE WHERE idConfigProducte = '$idConfig'";
        if (!mysqli_query($conn, $deleteQuery)) {
            $_SESSION["error_msg"] = "Error al eliminar el producto de personal_crea_producte.";
            return false;
        }

        // Eliminar registro de estat
        $deleteQuery = "DELETE FROM ESTAT WHERE idConfigProducte = '$idConfig'";
        if (!mysqli_query($conn, $deleteQuery)) {
            $_SESSION["error_msg"] = "Error al eliminar el producto de estat.";
            return false;
        }

        // Eliminar registro de producte
        $deleteQuery = "DELETE FROM PRODUCTE WHERE idConfig = '$idConfig'";
        if (!mysqli_query($conn, $deleteQuery)) {
            $_SESSION["error_msg"] = "Error al eliminar el producto de producte.";
            return false;
        }

        // Confirmar la transacción si todo salió bien
        mysqli_commit($conn);
        $_SESSION["success_msg"] = "Se ha eliminado correctamente";
        return true;
    }

}
?>