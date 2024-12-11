<!-- @Author: Blanca Atienzar Martinez (HTML, CSS y funcionalidad de SaaS) -->
<?php
require_once 'conexio.php';

class Componentes {
    public static function crearComponentesSaas($component, $tipo, $tipotipo, $gb) {
        $conn = Conexion::getConnection();

        $query = " ";
        $existsQuery = "";

        switch ($component) {
            case 'CMS':
                $existsQuery = "SELECT tipus FROM MODUL_CMS WHERE tipus = '$tipotipo'";
                $query = "INSERT INTO MODUL_CMS (tipus) VALUES ('$tipotipo')";
                break;
            case 'CDN':
                if (!is_numeric($precio) || $precio < 0 || $precio > 999.99) {
                    $_SESSION["error_msg"] = "El precio debe ser un número entre 0 y 999.99.";
                    return false;
                }else{
                    $existsQuery = "SELECT tipus FROM CDN WHERE tipus = '$tipo'";
                    $query = "INSERT INTO CDN (tipus, preu) VALUES ('$tipo', $precio)";
                }
                break;
            case 'SSL':
                if (!is_numeric($precio) || $precio < 0 || $precio > 999.99) {
                    $_SESSION["error_msg"] = "El precio debe ser un número entre 0 y 999.99.";
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
                if (!$gb || (!is_numeric($precio) || $precio < 0 || $precio > 999.99)) {
                    $_SESSION["error_msg"] = "El precio debe ser un número entre 0 y 999.99.";
                    return false;
                }else{
                    if ($tipo == '') {
                        $existsQuery = "SELECT tipus, GB FROM RAM WHERE tipus = '$tipotipo' AND GB = $gb";
                        $query = "INSERT INTO RAM (tipus, GB, preu) VALUES ('$tipotipo', $gb, $precio)";
                    } else {
                        $existsQuery = "SELECT tipus, GB FROM RAM WHERE tipus = '$tipo' AND GB = $gb";
                        $query = "INSERT INTO RAM (tipus, GB, preu) VALUES ('$tipo', $gb, $precio)";
                    }
                }
                break;
            case 'DISC_DUR':
                if (!$gb || (!is_numeric($precio) || $precio < 0 || $precio > 999.99)) {
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
                $_SESSION["success_msg"] = "Componente añadido.";
                return true;   
            } else {
                $_SESSION["error_msg"] = "Error al añadir el componente.";
                return false;
            }
        }

    }

    public static function eliminarComponentesSaas($componente, $tipo, $gb, $precio) {
        $conn = Conexion::getConnection();

        switch ($componente) {
            case 'Modul CMS':
                $query = "DELETE FROM MODUL_CMS WHERE tipus='$tipo'";
                break;
            case 'CDN':
                $query = "DELETE FROM CDN WHERE tipus='$tipo'";
                break;
            case 'Certificado SSL':
                $query = "DELETE FROM C_SSL WHERE tipus='$tipo'";
                break;
            case 'Sistema de Gestion de Base de Datos':
                $query = "DELETE FROM SIST_GESTIO_BD WHERE tipus='$tipo'";
                break;
            case 'RAM':
                $query = "DELETE FROM RAM WHERE tipus='$tipo'";
                break;
            case 'Disco Duro':
                $query = "DELETE FROM DISC_DUR WHERE tipus='$tipo'";
                break;
        }
        
        $resultado = mysqli_query($conn, $query);
        if (!$resultado) {
            $_SESSION["error_msg"] = "No se pudo eliminar el componente.";
            return false;
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
                $query = "UPDATE CDN SET preu='$precio' WHERE tipus='$tipo'";
                break;
            case 'Certificado SSL':
                $query = "UPDATE C_SSL SET preu='$precio' WHERE tipus='$tipo'";
                break;
            case 'Sistema de Gestion de Base de Datos':
                $_SESSION["error_msg"] = "No se puede actualizar el precio.";
                return false;
                break;
            case 'RAM':
                $query = "UPDATE RAM SET preu='$precio' WHERE tipus='$tipo'";
                break;
            case 'Disco Duro':
                $query = "UPDATE DISC_DUR SET preu='$precio' WHERE tipus='$tipo'";
                break;
        }
        $resultado = mysqli_query($conn, $query);
        if (!$resultado) {
            $_SESSION["error_msg"] = "No se pudo actualizar el componente.";
            return false;
        }
        $_SESSION["success_msg"] = "Componente actualizado.";
        return true;
    }
}
?>