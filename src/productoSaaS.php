<!-- @Author: Blanca Atienzar Martinez (HTML, CSS y funcionalidad de SaaS) -->
<?php
require_once 'conexio.php';

class SaaS {

    public static function crear($nom, $descripcion, $tipoCMS, $tipoCDN, $tipoSSL, $tipoSGBD, $currentDate, $tipoRam, $gbRam, $tipoDiscDur, $gbDiscDur, $emailCreador, $selectedRows) {
        $conn = Conexion::getConnection();
        
        //Crea el producto
        $insert_query_producte = "INSERT INTO PRODUCTE (idConfig, nom, descripcio) VALUES (NULL, '$nom', '$descripcion');";
        
        $result = mysqli_query($conn, $insert_query_producte);

        if (!$result) {
            $_SESSION["error_msg"] = "Error al intentar crear el producto ";
            return false;
        }
        
        $idConf = mysqli_insert_id($conn);

        $insert_query_saas = "INSERT INTO SAAS (idConfig, dataCreacio, tipusMCMS, tipusCDN, tipusSSL, tipusSGBD, tipusRam, GBRam, tipusDD, GBDD) VALUES 
        ('$idConf', '$currentDate', '$tipoCMS', '$tipoCDN', '$tipoSSL', '$tipoSGBD', '$tipoRam', '$gbRam', '$tipoDiscDur', '$gbDiscDur');";
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

    
    public static function editar($idConfig, $tipoCMS, $tipoCDN, $tipoSSL, $tipoSGBD, $tipoRam, $gbRam, $tipoDiscDur, $gbDiscDur) {
        $conn = Conexion::getConnection();
        
        $update_query = "UPDATE SAAS 
        SET 
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

    public static function actualizarEstado($idConfig, $testNom, $estatName) {
        $conn = Conexion::getConnection();

        $update_check_estat_Query = "UPDATE ESTAT SET estat = '$estatName' WHERE idConfigProducte = '$idConfig' AND nomT = '$testNom'";
        if(mysqli_query($conn, $update_check_estat_Query) == false) {
            $_SESSION["error_msg"] = "Error al actualizar el estado.";
            return false;
        };
        $_SESSION["success_msg"] = "Estado actualizado.";
        return true;        
    }

    public static function eliminarTestProducto($idConfig, $testNom) {
        $conn = Conexion::getConnection();

        $deleteQuery = "DELETE FROM ESTAT WHERE idConfigProducte = '$idConfig' AND nomT = '$testNom'";
        if (!mysqli_query($conn, $deleteQuery)) {
            $_SESSION["error_msg"] = "Error al eliminar el producto de estat.";
            return false;
        }
        $_SESSION["success_msg"] = "Test eliminado del producto.";
        return true;
    }

    public static function añadirTestProducto($idConfig, $testName) {
        $conn = Conexion::getConnection();

        $selectQueryEstat = "SELECT estat FROM ESTAT WHERE idConfigProducte='$idConfig' AND nomT='$testName';";
        $result= mysqli_query($conn, $selectQueryEstat);
        if(mysqli_num_rows($result) > 0) {
            $_SESSION["error_msg"] = "Error al añadir el test. El producto ya tiene el test asignado.";
            return false;
        }

        $insertQueryEstat = "INSERT INTO ESTAT (estat, nomT, idConfigProducte) VALUES ('Pendent', '$testName', '$idConfig');";
        $result= mysqli_query($conn, $insertQueryEstat);
        if(!$result) {
            $_SESSION["error_msg"] = "Error al añadir el test.";
            return false;
        }
                    
        $_SESSION["success_msg"] = "Test asignado correctamente.";
        return true;
    }

}
?>