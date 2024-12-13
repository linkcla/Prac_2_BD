<!-- @Author: Blanca Atienzar Martinez -->
<!-- @Author: Pau Toni Bibiloni Martínez -->
 
<?php
require_once 'conexio.php';

class Componentes {
    public static function crearComponentesSaas($component, $tipo, $tipotipo, $gb, $precio) {
        $conn = Conexion::getConnection();

        $query = " ";
        $existsQuery = "";
        switch ($component) {
            case 'CMS':
                $existsQuery = "SELECT tipus FROM MODUL_CMS WHERE tipus = '$tipotipo'";
                $query = "INSERT INTO MODUL_CMS (tipus) VALUES ('$tipotipo')";
                break;
            case 'CDN':
                if (!is_numeric($precio) || $precio < 0 || $precio > 9.99) {
                    $_SESSION["error_msg"] = "El precio debe ser un número entre 0 y 9.99 .";
                    return false;
                }else{
                    $existsQuery = "SELECT tipus FROM CDN WHERE tipus = '$tipo'";
                    $query = "INSERT INTO CDN (tipus, preu) VALUES ('$tipo', $precio)";
                }
                break;
            case 'SSL':
                if (!is_numeric($precio) || $precio < 0 || $precio > 9.99) {
                    $_SESSION["error_msg"] = "El precio debe ser un número entre 0 y 9.99.";
                    return false;
                }else{
                    $existsQuery = "SELECT tipus FROM C_SSL WHERE tipus = '$tipo'";
                    $query = "INSERT INTO C_SSL (tipus, preu) VALUES ('$tipo', $precio)";
                }
                break;
            case 'SGBD':
                $existsQuery = "SELECT tipus FROM SIST_GESTIO_BD WHERE tipus = '$tipotipo'";
                $query = "INSERT INTO SIST_GESTIO_BD (tipus) VALUES ('$tipotipo')";
                break;
            case 'RAM':
                if (!$gb || (!is_numeric($precio) || $precio < 0 || $precio > 99.99)) {
                    $_SESSION["error_msg"] = "El precio debe ser un número entre 0 y 99.99 .";
                    return false;
                }else if ($tipo == '') {
                    $existsQuery = "SELECT tipus, GB FROM RAM WHERE tipus = '$tipotipo' AND GB = $gb";
                    $query = "INSERT INTO RAM (tipus, GB, preu) VALUES ('$tipotipo', $gb, $precio)";
                } else {
                    $existsQuery = "SELECT tipus, GB FROM RAM WHERE tipus = '$tipo' AND GB = $gb";
                    $query = "INSERT INTO RAM (tipus, GB, preu) VALUES ('$tipo', $gb, $precio)";
                }
                break;
            case 'DISC_DUR':
                if (!$gb || (!is_numeric($precio) || $precio <= 0 || $precio > 999.99)) {
                    $_SESSION["error_msg"] = "El precio debe ser un número entre 0 y 999.99.";
                    return false;
                }else{
                    $existsQuery = "SELECT tipus, GB FROM DISC_DUR WHERE tipus = '$tipo' AND GB = $gb";
                    $query = "INSERT INTO DISC_DUR (tipus, GB, preu) VALUES ('$tipo', $gb, $precio)";
                }
                break;
        } 
        // Verificar si el componente ya existe en la base de datos
        $existsResult = mysqli_query($conn, $existsQuery);
        if (mysqli_num_rows($existsResult) > 0) {
            $_SESSION["error_msg"] = "El componente ya existe.";
            return false;
        } else {
            // Insertar el nuevo componente en la base de datos 
            if (mysqli_query($conn, $query)) {
                //$_SESSION["success_msg"] = "Componente añadido.";
                return true;   
            } else {
                $_SESSION["error_msg"] = "Error al añadir el componente.";
                return false;
            }
        }

    }

    public static function eliminarComponentesSaas($componente, $tipo, $gb) {
        $conn = Conexion::getConnection();

        switch ($componente) {
            case 'Modul CMS':
                $querySaaS = "SELECT idConfig FROM SAAS WHERE tipusMCMS='$tipo'";
                $query = "DELETE FROM MODUL_CMS WHERE tipus='$tipo'";
                break;
            case 'CDN':
                $querySaaS = "SELECT idConfig FROM SAAS WHERE tipusCDN='$tipo'";
                $query = "DELETE FROM CDN WHERE tipus='$tipo'";
                break;
            case 'Certificado SSL':
                $querySaaS = "SELECT idConfig FROM SAAS WHERE tipusSSL='$tipo'";
                $query = "DELETE FROM C_SSL WHERE tipus='$tipo'";
                break;
            case 'Sistema de Gestion de Base de Datos':
                $querySaaS = "SELECT idConfig FROM SAAS WHERE tipusSGBD='$tipo'";
                $query = "DELETE FROM SIST_GESTIO_BD WHERE tipus='$tipo'";
                break;
            case 'RAM':
                $querySaaS = "SELECT idConfig FROM SAAS WHERE tipusRAM='$tipo' AND GBRam='$gb'";
                $query = "DELETE FROM RAM WHERE tipus='$tipo' AND GB=$gb";
                break;
            case 'Disco Duro':
                $querySaaS = "SELECT idConfig FROM SAAS WHERE tipusDD='$tipo' AND GBDD='$gb'";
                $query = "DELETE FROM DISC_DUR WHERE tipus='$tipo' AND GBDD='$gb' ";
                break;
        }
        
        // Verificar si el componente está en uso
        $result = mysqli_query($conn, $querySaaS);
        if (mysqli_num_rows($result) > 0) {
            $_SESSION["error_msg"] = "Error, el componente esta en uso, no se pudo eliminar el componente.";
            return false;
        }else{
            $resultado = mysqli_query($conn, $query);
            if(!$resultado){
                $_SESSION["error_msg"] = "No se pudo eliminar el componente.";
                return false;
            }
        }
        $_SESSION["success_msg"] = "Componente eliminado.";
        return true;
        
    }

    public static function editarComponentesSaas($componente, $tipo, $gb, $precioCambiar) {
        $conn = Conexion::getConnection();
        switch ($componente) {
            case 'Modul CMS':
                $_SESSION["error_msg"] = "No se puede actualizar el precio.";
                return false;
                break;
            case 'CDN':
                $querySaaS = "SELECT idConfig FROM SAAS WHERE tipusCDN='$tipo'";
                $query = "UPDATE CDN SET preu='$precioCambiar' WHERE tipus='$tipo'";
                break;
            case 'Certificado SSL':
                $querySaaS = "SELECT idConfig FROM SAAS WHERE tipusCDN='$tipo'";
                $query = "UPDATE C_SSL SET preu='$precioCambiar' WHERE tipus='$tipo'";
                break;
            case 'Sistema de Gestion de Base de Datos':
                $_SESSION["error_msg"] = "No se puede actualizar el precio.";
                return false;
                break;
            case 'RAM':
                $querySaaS = "SELECT idConfig FROM SAAS WHERE tipusRAM='$tipo' AND GBRam='$gb'";
                $query = "UPDATE RAM SET preu='$precioCambiar' WHERE tipus='$tipo' AND GB='$gb'";
                break;
            case 'Disco Duro':
                $querySaaS = "SELECT idConfig FROM SAAS WHERE tipusDD='$tipo' AND GBDD='$gb'";
                $query = "UPDATE DISC_DUR SET preu='$precioCambiar' WHERE tipus='$tipo' AND GBDD='$gb'";
                break;
        }
        // Verificar si el componente está en uso
        $result = mysqli_query($conn, $querySaaS);
        if (mysqli_num_rows($result) > 0) {
            $_SESSION["error_msg"] = "Error, el componente esta en uso, no se pudo editar el componente.";
            return false;
        }else{
            $resultado = mysqli_query($conn, $query);
            if(!$resultado){
                $_SESSION["error_msg"] = "No se pudo editar el componente.";
                return false;
            }
        }
        $_SESSION["success_msg"] = "Componente editado.";
        return true;
    }

     //-------------------------------------FUNCIONES DE CREATE_NEW_RAM--------------------------------------
     public static function crearNuevaRAMPaaS($conn, $tipo, $gb, $precio) {

        if ($tipo && $gb && $precio) {
            // Validar que el precio sea un valor decimal con dos decimales y no negativo
            if (!preg_match('/^\d+(\.\d{1,2})?$/', $precio) || $precio < 0) {
                echo "<div class='alert alert-danger' role='alert'>El precio debe ser un número entre 0 y 999.99.</div>";
                return;
            }

            // Verificar si el tipo de RAM ya existe con la misma cantidad de GB
            $checkQuery = "SELECT * FROM RAM WHERE tipus = '$tipo' AND GB = $gb";
            $checkResult = mysqli_query($conn, $checkQuery);
            
            if (mysqli_num_rows($checkResult) > 0) {
                echo "<div class='alert alert-danger' role='alert'>El tipo de RAM con esa cantidad de GB ya existe.</div>";
            } else {
                $query = "INSERT INTO RAM (tipus, GB, preu) VALUES ('$tipo', $gb, $precio)";
                if (mysqli_query($conn, $query)) {
                    echo "<div class='alert alert-success' role='alert'>Nuevo tipo de RAM añadido exitosamente.</div>";
                } else {
                    echo "Error al añadir el nuevo tipo de RAM: " . mysqli_error($conn);
                }
            }
        } else {
            echo "<div class='alert alert-warning' role='alert'>Todos los campos son obligatorios.</div>";
        }
    }

    //----------------------------------FUNCIONES DE CREATE_STOCK_COMPONENTES-----------------------------------
    public static function crearComponentePaaS($conn, $component, $tipo, $gb, $nNuclis, $precio) {
        $query = "";
        $existsQuery = "";
    
        switch ($component) {
            case 'RAM':
                if ($gb) {
                    $existsQuery = "SELECT * FROM RAM WHERE tipus = '$tipo' AND GB = $gb";
                    $query = "INSERT INTO RAM (tipus, GB, preu) VALUES ('$tipo', $gb, $precio)";
                }
                break;
            case 'DISC_DUR':
                if ($gb) {
                    $existsQuery = "SELECT * FROM DISC_DUR WHERE tipus = '$tipo' AND GB = $gb";
                    $query = "INSERT INTO DISC_DUR (tipus, GB, preu) VALUES ('$tipo', $gb, $precio)";
                }
                break;
            case 'CPU':
                if ($nNuclis) {
                    $existsQuery = "SELECT * FROM CPU WHERE model = '$tipo' AND nNuclis = '$nNuclis'";
                    $query = "INSERT INTO CPU (model, nNuclis, preu) VALUES ('$tipo', '$nNuclis', $precio)";
                }
                break;
            case 'SO':
                $existsQuery = "SELECT * FROM SO WHERE nom = '$tipo'";
                $query = "INSERT INTO SO (nom, preu) VALUES ('$tipo', $precio)";
                break;
        }
    
        if ($existsQuery) {
            $existsResult = mysqli_query($conn, $existsQuery);
            if (mysqli_num_rows($existsResult) > 0) {
                echo "<div class='alert alert-danger' role='alert'>El componente ya existe.</div>";
            } else {
                if (mysqli_query($conn, $query)) {
                    echo "<div class='alert alert-success' role='alert'>Componente añadido exitosamente.</div>";
                } else {
                    echo "<p>Error al añadir el componente: " . mysqli_error($conn) . "</p>";
                }
            }
        }
    }

    //----------------------------------------FUNCIONES DE DELETE_STOCK_COMPONENTES--------------------------------
    public static function eliminarComponentesPaaS($conn, $selectedRam, $selectedDiscDur, $selectedCpu, $selectedSo) {

        if (empty($selectedRam) && empty($selectedDiscDur) && empty($selectedCpu) && empty($selectedSo)) {
            $_SESSION["warning_msg"] = "Debes seleccionar al menos un componente.";
        } else {
            $error_ocurred = false;
            // Iniciar transacción
            mysqli_begin_transaction($conn);

            // Eliminar RAM seleccionada
            foreach ($selectedRam as $ram) {
                list($tipus, $GB) = explode(',', $ram);
                $tipus = mysqli_real_escape_string($conn, $tipus);
                $GB = mysqli_real_escape_string($conn, $GB);
                $deleteRamQuery = "DELETE FROM RAM WHERE tipus = '$tipus' AND GB = '$GB'";
                if (!mysqli_query($conn, $deleteRamQuery)) {
                    $error_ocurred = true;
                    break;
                }
            }

            // Eliminar Disco Duro seleccionado
            if (!$error_ocurred) {
                foreach ($selectedDiscDur as $discDur) {
                    list($tipus, $GB) = explode(',', $discDur);
                    $tipus = mysqli_real_escape_string($conn, $tipus);
                    $GB = mysqli_real_escape_string($conn, $GB);
                    $deleteDiscDurQuery = "DELETE FROM DISC_DUR WHERE tipus = '$tipus' AND GB = '$GB'";
                    if (!mysqli_query($conn, $deleteDiscDurQuery)) {
                        $error_ocurred = true;
                        break;
                    }
                }
            }

            // Eliminar CPU seleccionada
            if (!$error_ocurred) {
                foreach ($selectedCpu as $cpu) {
                    list($model, $nNuclis) = explode(',', $cpu);
                    $model = mysqli_real_escape_string($conn, $model);
                    $nNuclis = mysqli_real_escape_string($conn, $nNuclis);
                    $deleteCpuQuery = "DELETE FROM CPU WHERE model = '$model' AND nNuclis = '$nNuclis'";
                    if (!mysqli_query($conn, $deleteCpuQuery)) {
                        $error_ocurred = true;
                        break;
                    }
                }
            }

            // Eliminar SO seleccionado
            if (!$error_ocurred) {
                foreach ($selectedSo as $so) {
                    $nom = mysqli_real_escape_string($conn, $so);
                    $deleteSoQuery = "DELETE FROM SO WHERE nom = '$nom'";
                    if (!mysqli_query($conn, $deleteSoQuery)) {
                        $error_ocurred = true;
                        break;
                    }
                }
            }

            if ($error_ocurred) {
                // Revertir la transacción si hubo error
                mysqli_rollback($conn);
                $_SESSION["error_msg"] = "Error al eliminar los componentes seleccionados.";
            } else {
                // Confirmar la transacción si todo salió bien
                mysqli_commit($conn);
                $_SESSION["success_msg"] = "Los componentes seleccionados se eliminaron correctamente.";
            }
        }
    }

    //-----------------------------------------FUNCIONES DE EDIT_STOCK_COMPONENTES--------------------------------
    public static function getComponentesByTipoPaaS($conn, $tipo) {
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
        $result = mysqli_query($conn, $query);
        while ($row = mysqli_fetch_assoc($result)) {
            $componentes[] = $row;
        }
        return $componentes;
    }

    public static function updatePrecioComponentePaaS($conn, $tipo, $nombre, $gb_componente, $precio) {

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
                $query = "UPDATE CPU SET preu = $precio WHERE model = '$nombre' AND nNuclis = $gb_componente";
                break;
            case 'SO':
                $query = "UPDATE SO SET preu = $precio WHERE nom = '$nombre'";
                break;
        }

        if (mysqli_query($conn, $query)) {
            $_SESSION["success_msg"] = "Precio actualizado correctamente.";
            return true;
        } else {
            $_SESSION["error_msg"] = "Error al actualizar el precio: " . mysqli_error($conn);
            return false;
        }
    }
}
?>
