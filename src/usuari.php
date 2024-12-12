<!-- @Author: Hai Zi Bibiloni Trobat -->
<?php
require_once 'conexio.php';

class Usuari {

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

    public static function editarP($email, $nom, $cognom, $contrasenya) {
        $conn = Conexion::getConnection();

        // Per obtenir les dades del personal
        $sql = "SELECT p.nom, p.cognom, p.contrasenya, per.dni FROM PERSONA p
        JOIN PERSONAL per ON per.email = p.email
        WHERE per.email = '$email'";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            $original = mysqli_fetch_assoc($result);
        } else {
            $_SESSION["error_msg"] = "Usuari no trobat.";
            return false;
        }

        // Comprovar si les dades han canviat
        if ($nom === $original['nom'] && $cognom === $original['cognom'] && $contrasenya === $original['contrasenya']) {
            $_SESSION["info_msg"] = "No s'ha actualitzat res, mateixes dades.";
            return true;
        }

        // Actualitzar les dades de l'usuari
        if ($nom !== null && $nom !== $original['nom']) {
            $sql = "UPDATE PERSONA SET nom = '$nom' WHERE email = '$email'";
            mysqli_query($conn, $sql);
        }

        if ($cognom !== null && $cognom !== $original['cognom']) {
            $sql = "UPDATE PERSONA SET cognom = '$cognom' WHERE email = '$email'";
            mysqli_query($conn, $sql);
        }

        if ($contrasenya !== null && $contrasenya !== $original['contrasenya']) {
            $sql = "UPDATE PERSONA SET contrasenya = '$contrasenya' WHERE email = '$email'";
            mysqli_query($conn, $sql);
        }

        $_SESSION["success_msg"] = "Usuari actualitzat correctament.";
        return true;
        
        if (!mysqli_query($conn, $sql)) {
            $_SESSION["error_msg"] = "Usuari no actualitzat.";
            return false;
        }
    }


    public static function editarU($email, $nom, $cognom, $contrasenya) {
        $conn = Conexion::getConnection();

        // Per obtenir les dades de l'usuari
        $sql = "SELECT p.nom, p.cognom, p.contrasenya FROM PERSONA p
        JOIN USUARI u ON u.email = p.email
        WHERE u.email = '$email'";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            $original = mysqli_fetch_assoc($result);
        } else {
            $_SESSION["error_msg"] = "Usuari no trobat.";
            return false;
        }

        // Comprovar si les dades han canviat
        if ($nom === $original['nom'] && $cognom === $original['cognom'] && $contrasenya === $original['contrasenya']) {
            $_SESSION["info_msg"] = "No s'ha actualitzat res, mateixes dades.";
            return true;
        }

        // Actualitzar les dades de  l'usuario
        if ($nom !== null && $nom !== $original['nom']) {
            $sql = "UPDATE PERSONA SET nom = '$nom' WHERE email = '$email'";
            mysqli_query($conn, $sql);
        }

        if ($cognom !== null && $cognom !== $original['cognom']) {
            $sql = "UPDATE PERSONA SET cognom = '$cognom' WHERE email = '$email'";
            mysqli_query($conn, $sql);
        }

        if ($contrasenya !== null && $contrasenya !== $original['contrasenya']) {
            $sql = "UPDATE PERSONA SET contrasenya = '$contrasenya' WHERE email = '$email'";
            mysqli_query($conn, $sql);
        }

        $_SESSION["success_msg"] = "Usuari actualizat correctament.";
        return true;

        if (!mysqli_query($conn, $sql)) {
            $_SESSION["error_msg"] = "Usuari no actualitzat.";
            return false;
        }
    }

    public static function eliminar($email) {
        $conn = Conexion::getConnection();
        
        // Eliminar persona de CONTRACTE
        $sql = "DELETE FROM CONTRACTE WHERE emailU = '$email'";
        mysqli_query($conn, $sql);

        // Eliminar persona de PERSONAL_ADMINISTRA_CONTR
        $sql = "DELETE FROM PERSONAL_ADMINISTRA_CONTR WHERE emailP = '$email'";
        mysqli_query($conn, $sql);

        // Eliminar persona de PERSONAL_CREA_PRODUCTE
        $sql = "DELETE FROM PERSONAL_CREA_PRODUCTE WHERE emailP = '$email'";
        mysqli_query($conn, $sql);

        // Eliminar persona de PERSONAL_REALITZA_TEST
        $sql = "DELETE FROM PERSONAL_REALITZA_TEST WHERE emailP = '$email'";
        mysqli_query($conn, $sql);

        // Eliminar persona de USUARI
        $sql = "DELETE FROM USUARI WHERE email = '$email'";
        mysqli_query($conn, $sql);

        // Eliminar persona de PERSONAL
        $sql = "DELETE FROM PERSONAL WHERE email = '$email'";
        mysqli_query($conn, $sql);

        // Eliminar persona de PERSONA
        $sql = "DELETE FROM PERSONA WHERE email = '$email'";
        mysqli_query($conn, $sql);

        $_SESSION["success_msg"] = "Usuario eliminado correctamente.";
        return true;

        if (!mysqli_query($conn, $sql)) {
            $_SESSION["error_msg"] = "Usuario no eliminado.";
            return false;
        }
    }

}
?>