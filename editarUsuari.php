<!-- @Author: Blanca Atienzar Martinez (HTML y CSS) -->

<?php session_start();
include "conexion.php";
$conn = Conexion::getConnection();  

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
    $email = $_POST['email'];
    $nom = isset($_POST['nom']) ? $_POST['nom'] : null;
    $cognom = isset($_POST['cognom']) ? $_POST['cognom'] : null;
    $contrasenya = isset($_POST['contrasenya']) ? $_POST['contrasenya'] : null;
    $nomOrg = isset($_POST['nomOrg']) ? $_POST['nomOrg'] : null;
    $nomG = isset($_POST['nomG']) ? $_POST['nomG'] : null;

    // Obtener los datos originales del usuario
    $sql = "SELECT p.nom, p.cognom, p.contrasenya, u.nomOrg, g.nomG FROM USUARI u
            JOIN PERSONA p ON u.email = p.email
            LEFT JOIN US_PERTANY_GRU g ON u.email = g.emailU
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
    if ($nom === $original['nom'] && $cognom === $original['cognom'] && $contrasenya === $original['contrasenya'] && $nomOrg === $original['nomOrg'] && $nomG === $original['nomG']) {
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

    if ($nomOrg !== null && $nomOrg !== $original['nomOrg']) {
        $sql = "UPDATE USUARI SET nomOrg = ? WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $nomOrg, $email);
        $stmt->execute();
    }

    if ($nomG !== null && $nomG !== $original['nomG']) {
        // Verificar si el usuario ya tiene un grupo asignado
        $sql = "SELECT * FROM US_PERTANY_GRU WHERE emailU = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Actualizar el grupo existente
            $sql = "UPDATE US_PERTANY_GRU SET nomG = ? WHERE emailU = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $nomG, $email);
            $stmt->execute();
        } else {
            // Insertar un nuevo grupo
            $sql = "INSERT INTO US_PERTANY_GRU (emailU, nomG) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $email, $nomG);
            $stmt->execute();
        }
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