<!-- @Author: Marc Link Cladera -->

<?php session_start();
require_once 'conexion.php';

$locemail = $_POST['email'];
$loccont = $_POST['contrasenya'];


$conn = Connection::getConnection();

$cadena = "SELECT contrasenya, nom, cognom FROM persona WHERE email = {$locemail}";
$resultado = mysqli_query($conn,$cadena);

if (!$resultado || mysqli_num_rows($resultado) == 0) 
{
    // Error de consulta o no existe el usuario
    echo '<script>alert("Usuario no encontrado");</script>';
    header("Location: ./loginform.php");
} 
else
{
    while ($fila = $resultado->fetch_assoc()) {
        if (!password_verify($loccont, $fila['contrasenya'])) {
            // contrasenya incorrecte
            echo '<script>alert("Contraseña incorrecta");</script>';
            header("Location: ./loginform.php");
            die("Ha habido un error. Disculpa las molestias.");
        }
        
        // contrasenya correcta
        $bool_es_usuari = esUsuari($locemail);
        $_SESSION["nom"] = $fila['nom'];
        $_SESSION["cognom"] = $fila['cognom'];
        $_SESSION["email"] = $locemail; 
        $_SESSION["usuari"] = $bool_es_usuari; 

        // Guardam els permissos que te cada persona, si es personal tendrà tots els permisos
        if ($bool_es_usuari) {
            $cadena = "SELECT tipusPriv 
                       FROM priv_de_grup as pdg 
                       JOIN us_pertany_gru as upg 
                       ON pdg.nomG = upg.nomG and upg.emailU = '{$locemail}'";
            $result = mysqli_query($conn, $cadena);
            $_SESSION["permisos"] = array();
            if ($result && mysqli_num_rows($result) > 0){
                while ($fila_aux = $result->fetch_assoc()) {
                    $_SESSION["permisos"][] = $fila_aux
                }
            }
            
        }
        else
        {
            $_SESSION["permisos"] = {"visualizar","borrar", "crear", "editar"};
        }
        header("Location: ./servicesform.php");
    }
}

function esUsuari($email) {
    $cadena = "SELECT dni FROM personal WHERE email = {$email}";
    $result = mysqli_query($conn, $cadena);
    return (!$result || mysqli_num_rows($result) == 0)
}
// Ahora puedes usar $connection en cualquier parte de tu archivo index.php
?>