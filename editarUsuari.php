<!-- @Author: Hai Zi Bibiloni Trobat -->

<?php session_start();
include "conexion.php";
$conn = Conexion::getConnection();  

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
    $email = $_POST['email'];
    $nom = isset($_POST['nom']) ? $_POST['nom'] : null;
    $cognom = isset($_POST['cognom']) ? $_POST['cognom'] : null;
    $contrasenya = isset($_POST['contrasenya']) ? $_POST['contrasenya'] : null;

    // Obtener los datos originales del usuario
    $sql = "SELECT p.nom, p.cognom, p.contrasenya FROM PERSONA p
            JOIN USUARI u ON u.email = p.email
            WHERE u.email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $original = $result->fetch_assoc();
    } else {
        $_SESSION["error_msg"] = "Usuario no encontrado.";
        header("Location: servicesAdminform.php");
        exit();
    }

    // Comprobar si los datos han cambiado
    if ($nom === $original['nom'] && $cognom === $original['cognom'] && $contrasenya === $original['contrasenya']) {
        $_SESSION["info_msg"] = "No s'ha actualitzat res, mateixes dades.";
        header("Location: servicesAdminform.php");
        exit();
    }

    // Actualizar los datos del usuario
    if ($nom !== null && $nom !== $original['nom']) {
        $sql = "UPDATE PERSONA SET nom = ? WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $nom, $email);
        $stmt->execute();
    }

    if ($cognom !== null && $cognom !== $original['cognom']) {
        $sql = "UPDATE PERSONA SET cognom = ? WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $cognom, $email);
        $stmt->execute();
    }

    if ($contrasenya !== null && $contrasenya !== $original['contrasenya']) {
        $sql = "UPDATE PERSONA SET contrasenya = ? WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $contrasenya, $email);
        $stmt->execute();
    }

    $_SESSION["success_msg"] = "Usuario actualizado correctamente.";
    header("Location: servicesAdminform.php");
    exit();
} else {
    $_SESSION["error_msg"] = "No se ha podido actualizar el usuario.";
    header("Location: servicesAdminform.php");
    exit();
}
?>