<!-- @Author: Hai Zi Bibiloni Trobat -->

<?php session_start();
    
    // nom, cognom, email, contrasenya (persona)
    // email, nomOrg (usuari)
    // Para poner la contrasenya: hash('sha256', 'password');
   
    $nom = $_POST['nom'];
    $cognom = $_POST['cognom'];
    $email = $_POST['email'];
    //$contrasenya = hash('sha256', $_POST['contrasenya']);
    $contrasenya = $_POST['contrasenya'];
    //$nomOrg = $_POST['nomOrg'];

    include "conexion.php";

    $con = Conexion::getConnection();

    // Verificar si l'organització existeix
    //$sql_check_org = "SELECT nom FROM ORGANITZACIO WHERE nom = '$nomOrg'";
    //$result_org = mysqli_query($con, $sql_check_org);

    //if (mysqli_num_rows($result_org) == 0) {
    //    $message = "Error: L'organització '$nomOrg' no existeix.";
    //    echo "<script type='text/javascript'>alert('$message');</script>";
    //    mysqli_close($con);
    //    exit();
    //}    

    // Verificar si el usuari existeix
    $sql_check_p = "SELECT email, nom, cognom, contrasenya FROM PERSONA WHERE email = '$email'";
    $result_p = mysqli_query($con, $sql_check_p);

    if (!$result_p || mysqli_num_rows($result_p) == 0) 
    {       
        // Insertar a la taula PERSONA
        $cadena_persona = "INSERT INTO PERSONA (nom, cognom, email, contrasenya) VALUES ('$nom', '$cognom', '$email', '$contrasenya')";
        if (mysqli_query($con, $cadena_persona) == false) {
            // Error al insertar persona a la BD
            // Error el usuario ja existeix
            $message = "Error al crear la persona.";
            echo "<script type='text/javascript'>alert('$message'); window.location.href='insereixusuariform.php';</script>";
        }

        // Insertar persona a la taula USUARI
        $cadena_usuari = "INSERT INTO USUARI (email, nomOrg) VALUES ('$email', NULL)";
        if(mysqli_query($con, $cadena_usuari) == false) {
            $message = "Error al crear l'usuari.";
            echo "<script type='text/javascript'>alert('$message'); window.location.href='insereixusuariform.php';</script>";
        };
        
        $_SESSION['usuariCreat'] = "Usuari creat correctament";
        header("Location: ./loginform.php");
        die("Ja s'ha creat l'usuari");
    } else{
       // Error el usuario ja existeix
       $message = "Usuari ja creat.";
       echo "<script type='text/javascript'>alert('$message'); window.location.href='insereixusuariform.php';</script>";
    } 
  
    mysqli_close($con);
?>
