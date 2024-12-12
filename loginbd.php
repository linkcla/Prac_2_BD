<!-- @Author: Marc Link Cladera -->

<?php session_start();
include "conexion.php";

$conn = Conexion::getConnection();

$locemail = $_POST['email'];
$loccont = $_POST['contrasenya'];


$cadena = "SELECT contrasenya, nom, cognom FROM persona WHERE email = '{$locemail}'";
$resultado = mysqli_query($conn,$cadena);

if (!$resultado || mysqli_num_rows($resultado) == 0) 
{
    // Error de consulta o no existe el usuario
    $_SESSION["error_msg"] = "Usuario no encontrado";
    header("Location: ./loginform.php");
    die("Ha habido un error. Disculpa las molestias.");
} 
else
{
    while ($fila = $resultado->fetch_assoc()) {
        if (!password_verify($loccont, $fila['contrasenya'])) {
        //if ($loccont != $fila['contrasenya']) {
        // contrasenya incorrecte
            $_SESSION["error_msg"] = "Contraseña incorrecta";
            header("Location: ./loginform.php");
            die("Ha habido un error. Disculpa las molestias.");
        }
        
        // contrasenya correcta
        $bool_es_usuari = esUsuari($locemail, $conn);
        $_SESSION["nom"] = $fila['nom'];
        $_SESSION["cognom"] = $fila['cognom'];
        $_SESSION["email"] = $locemail; 
        $_SESSION["esUsuari"] = $bool_es_usuari; 

        // Guardam els permissos que te cada persona, si es personal tendrà tots els permisos
        if ($bool_es_usuari) {
            // Mirar si pertany a algun grup
            $query = "SELECT nomOrg, grup FROM usuari WHERE email = '{$locemail}'";
            $result = mysqli_query($conn, $query);
            $datosUsuario = $result->fetch_assoc();
            if (!$result || $datosUsuario['nomOrg'] == NULL) {
                $msg = "El usuario no pertenece a ninguna organización, contacte con el administrador de tu organización para que te añada a un grupo.";
                $_SESSION["error_msg"] = $msg;
                header("Location: ./loginform.php");
                die($msg);
            }
            
            $_SESSION["nomOrg"] = $datosUsuario['nomOrg'];
            $_SESSION["grup"] = $datosUsuario['grup'];

            $cadena = "SELECT tipusPriv 
                       FROM priv_de_grup as pdg 
                       JOIN usuari 
                       ON pdg.nomG = usuari.grup and usuari.email = '{$locemail}'";
            $result = mysqli_query($conn, $cadena);
            $_SESSION["permisos"] = array();
            if ($result && mysqli_num_rows($result) > 0){
                while ($fila_aux = $result->fetch_assoc()) {
                    $_SESSION["permisos"][] = $fila_aux['tipusPriv'];
                }
            }
            
        }
        else
        {
            $_SESSION["permisos"] = array("visualizar","borrar", "crear", "editar");
        }
        header("Location: ./servicesform.php");
        die("Login acabado");
    }
}

function esUsuari($email, $conn) {
    $cadena = "SELECT dni FROM personal WHERE email = '{$email}'";
    $result = mysqli_query($conn, $cadena);
    return (!$result || mysqli_num_rows($result) == 0);
}
?>