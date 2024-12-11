<!-- @Author: Blanca Atienzar Martinez (HTML, CSS y funcionalidad de SaaS) -->
<?php
require_once 'conexio.php';

class Usuario {

    public static function crear($nom, $cognom, $email, $contrasenya) {
        $conn = Conexion::getConnection();

        $sql_check_p = "SELECT email FROM PERSONA WHERE email = '$email'";
        $result_p = mysqli_query($conn, $sql_check_p);

        if (!$result_p || mysqli_num_rows($result_p) == 0) {       
            // Insertar a la taula PERSONA
            $cadena_persona = "INSERT INTO PERSONA (nom, cognom, email, contrasenya) VALUES ('$nom', '$cognom', '$email', '$contrasenya')";
            if (mysqli_query($conn, $cadena_persona) == false) {
                // Error al insertar persona a la BD
                // Error el usuario ja existeix
                $_SESSION["error_msg"] = "Error al crear la persona.";
                return false;
            }

            // Insertar persona a la taula USUARI
            $cadena_usuari = "INSERT INTO USUARI (email, nomOrg, grup) VALUES ('$email', NULL, NULL)";
            if(mysqli_query($conn, $cadena_usuari) == false) {
                $_SESSION["error_msg"] = "Error al crear la persona.";
                return false;
            };
            
            $_SESSION['success_msg'] = "Usuari creat correctament";
            return true;
        } else{
            // Error el usuario ja existeix
            $_SESSION["error_msg"] = "Usuario ja existeix. ";
            return false;
        } 
    
        mysqli_close($conn);
    }

    public static function editar() {
        $conn = Conexion::getConnection();
    }

    public static function eliminar() {
        $conn = Conexion::getConnection();
    }

}
?>

$_SESSION["error_msg"] = "Error al añadir el test.";
            return false;
        }
                    
        $_SESSION["success_msg"] = "Test asignado correctamente.";
        return true;