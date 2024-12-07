<!-- @Author: Blanca Atienzar Martinez (HTML, CSS y funcionalidad de SaaS) -->

<?php session_start() ;
include "conexion.php";
$conn = Conexion::getConnection();              

        $dominio = $_POST['dominio'];
        $sql = "SELECT domini FROM SAAS WHERE domini = '$dominio'";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            $msg = "El dominio ya existe. Por favor, elige otro ";
            $_SESSION["error_msg"] = $msg;
            header("Location: ./servicesSaaSCreateform.php");
            die($msg);
        }
        
        //Datos a actualizar
        $nom = $_POST['nombre'] ;
        $descripcion = $_POST['descipcio'] ;
        $tipoCMS = $_POST['tipo_cms'] ;
        $tipoCDN = $_POST['tipo_cdn'] ;
        $tipoSSL = $_POST['tipo_ssl'] ;
        $tipoSGBD = $_POST['tipo_sgbd'] ;
        $currentDate = date('Y-m-d H:i:s');

        $tipoGbRam = $_POST['tipo_gb_ram'] ;
        list($tipoRam, $gbRam) = explode('|', $tipoGbRam);

        $tipoGbDiscDur = $_POST['tipo_gb_disc_dur'] ;
        list($tipoDiscDur, $gbDiscDur) = explode('|', $tipoGbDiscDur);
        
        $dominio = $_POST['dominio'] ;

        //Crea el producto
        $insert_query_producte = "INSERT INTO PRODUCTE (idConfig, nom, descripcio) VALUES (NULL, '$nom', '$descripcion');";
        
        $result = mysqli_query($conn, $insert_query_producte);

        if (!$result) {
            $msg = "Error al intentar crear el producto ";
            $_SESSION["error_msg"] = $msg;
            header("Location: ./servicesSaaSCreateform.php");
            die($msg);
        }
        
        $idConf = mysqli_insert_id($conn);

        $insert_query_saas = "INSERT INTO SAAS (idConfig, domini, dataCreacio, tipusMCMS, tipusCDN, tipusSSL, tipusSGBD, tipusRam, GBRam, tipusDD, GBDD) VALUES 
        ('$idConf', '$dominio', '$currentDate', '$tipoCMS', '$tipoCDN', '$tipoSSL', '$tipoSGBD', '$tipoRam', '$gbRam', '$tipoDiscDur', '$gbDiscDur');";
        $result_saas = mysqli_query($conn, $insert_query_saas);

        $emailCreador = $_SESSION["email"];

        // Persona que ha creado el producto
        $insert_query_saas = "INSERT INTO PERSONAL_CREA_PRODUCTE (emailP, idConfigProducte) VALUES ('$emailCreador', '$idConf');";
        $result_saas = mysqli_query($conn, $insert_query_saas);
        if (!$result_saas) {
            $msg = "Error al intentar crear el producto, no se ha podido añadir la persona que lo ha creado";
            $_SESSION["error_msg"] = $msg;
            header("Location: ./servicesSaaSCreateform.php");
            die($msg);
        }

    //Añadir test
    $selectedRows = isset($_POST['selectedRowstest']) ? $_POST['selectedRowstest'] : null;
    if ($selectedRows) {
        foreach ($selectedRows as $id) {
            // Sanitiza el ID
            $id = mysqli_real_escape_string($conn, $id);

            // Insertar el test en la tabla ESTAT
            $insertQuery = "INSERT INTO ESTAT(estat, nomT, idConfigProducte) VALUES ('Pendent', '$id', '$idConf')";
            $result_tests = mysqli_query($conn, $insertQuery);
            if (!$result_tests) {
                $msg = "Error al intentar crear el producto, no se ha podido añadir el test";
                $_SESSION["error_msg"] = $msg;
                header("Location: ./servicesSaaSCreateform.php");
                die($msg);
            }
        }
    }

    // Si todo ha ido bien
    $msg = "Producto creado correctamente.".$selectedRows.'1';
    $_SESSION["success_msg"] = $msg;
    header("Location: ./servicesSaaSCreateform.php");
    die($msg);

?>