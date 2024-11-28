<!-- @Author: Hai Zi Bibiloni Trobat -->

<?php session_start();
    require_once 'conexion.php';

    // nom, cognom, email, contrasenya (persona)
    // email, nomOrg (usuari)
    // Para poner la contrasenya: hash('sha256', 'password');
    $nom = $_GET['nom'];
    $cognoms = $_GET['cognom'];
    $email = $_GET['email'];
    $contrasenya = hash('sha256', $_GET['contrasenya']);
    $nomOrg = $_GET['nomOrg'];

    $conn = Connection::getConnection();

    $cadena = "";
    mysqli_query($con,$cadena);    

    // Iniciar una transacción
    $conn->begin_transaction();

try {
    // Preparar la consulta SQL para insertar los datos en PERSONA
    $cadenaPersona = "INSERT INTO PERSONA (nom, cognom, email, contrasenya) VALUES (?, ?, ?, ?)";
    $stmtPersona = $conn->prepare($cadenaPersona);
    $stmtPersona->bind_param("ssss", $nom, $cognom, $email, $contrasenya);
    $stmtPersona->execute();

    // Preparar la consulta SQL para insertar los datos en USUARI
    $cadenaUsuari = "INSERT INTO USUARI (email, nomOrg) VALUES (?, ?)";
    $stmtUsuari = $conn->prepare($cadenaUsuari);
    $stmtUsuari->bind_param("ss", $email, $nomOrg);
    $stmtUsuari->execute();

    // Confirmar la transacción
    $conn->commit();

    echo "Usuario registrado con éxito.";
} catch (Exception $e) {
    // Revertir la transacción en caso de error
    $conn->rollback();
    echo "Error: " . $e->getMessage();
}








?>