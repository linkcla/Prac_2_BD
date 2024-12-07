<!-- @Author: Blanca Atienzar Martinez (HTML, CSS y funcionalidad de SaaS) -->

<?php session_start() ;
include "conexion.php";
$conn = Conexion::getConnection();              




        //Datos antiguos
        $idConfig = $_POST['idConfig'];

        $dominio = $_POST['dominio'];
        $sql = "SELECT domini FROM SAAS WHERE domini = '$dominio' AND idConfig != '$idConfig'";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0 ) {
            $msg = "El dominio ya existe. Por favor, elige otro ";
            $_SESSION["error_msg"] = $msg;
            header("Location: ./servicesSaaSViewform.php");
            die($msg);
        }


        //Datos a actualizar
        $tipoCMS = $_POST['tipo_cms'];
        $tipoCDN = $_POST['tipo_cdn'];
        $tipoSSL = $_POST['tipo_ssl'];
        $tipoSGBD = $_POST['tipo_sgbd'];

        $tipoGbRam = $_POST['tipo_gb_ram'];
        list($tipoRam, $gbRam) = explode('|', $tipoGbRam);

        $tipoGbDiscDur = $_POST['tipo_gb_disc_dur'];
        list($tipoDiscDur, $gbDiscDur) = explode('|', $tipoGbDiscDur);

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
            $msg = "Error al intentar modificar les dades ";
            $_SESSION["error_msg"] = $msg;
            header("Location: ./servicesSaaSViewform.php");
            die($msg);
        }
        
        // Si arriba aquí ha anat be.
        $msg = "Dades modificades correctament.";
        $_SESSION["success_msg"] = $msg;
        header("Location: ./servicesSaaSViewform.php");
        die($msg);
       
        
    



?>