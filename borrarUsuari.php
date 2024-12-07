<!-- @Author: Hai Zi Bibiloni Trobat -->

<?php
session_start();
include "conexion.php";
$conn = Conexion::getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
    $email = $_POST['email'];

    // Iniciar una transacción
    $conn->begin_transaction();

    try {
        // Eliminar relaciones en CONTRACTE
        $sql = "DELETE FROM CONTRACTE WHERE emailU = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();

        // Eliminar relaciones en PERSONAL_ADMINISTRA_CONTR
        $sql = "DELETE FROM PERSONAL_ADMINISTRA_CONTR WHERE emailP = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();

        // Eliminar relaciones en PERSONAL_CREA_PRODUCTE
        $sql = "DELETE FROM PERSONAL_CREA_PRODUCTE WHERE emailP = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();

        // Eliminar relaciones en PERSONAL_REALITZA_TEST
        $sql = "DELETE FROM PERSONAL_REALITZA_TEST WHERE emailP = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();

        // Eliminar el usuario de la tabla USUARI
        $sql = "DELETE FROM USUARI WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();

        // Eliminar el usuario de la tabla PERSONAL
        $sql = "DELETE FROM PERSONAL WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();

        // Eliminar el usuario de la tabla PERSONA
        $sql = "DELETE FROM PERSONA WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();

        // Confirmar la transacción
        $conn->commit();

        $_SESSION["success_msg"] = "Usuario eliminado correctamente.";
        header("Location: servicesUsuariform.php");
        exit();
    } catch (Exception $e) {
        // Revertir la transacción en caso de error
        $conn->rollback();
        $_SESSION["error_msg"] = "No se ha podido eliminar el usuario.";
        header("Location: servicesUsuariform.php");
        exit();
    }
} else {
    $_SESSION["error_msg"] = "No se ha seleccionado ningún usuario.";
    header("Location: servicesUsuariform.php");
    exit();
}
?>