<?php
session_start();
include "conexion.php";
$conn = Conexion::getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'];
    $cognom = $_POST['cognom'];
    $email = $_POST['email'];
    $contrasenya = $_POST['contrasenya'];
    $nomOrg = $_POST['nomOrg'];
    $nomG = $_POST['nomG'];

    // Verificar si el email ya existe
    $sql = "SELECT email FROM PERSONA WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION["error_msg"] = "El email ya está registrado.";
        header("Location: crearUsuariform.php");
        exit();
    }

    // Verificar si la organización existe
    $sql = "SELECT nom FROM ORGANITZACIO WHERE nom = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $nomOrg);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        $_SESSION["error_msg"] = "La organización no existe.";
        header("Location: crearUsuariform.php");
        exit();
    }

    // Insertar datos en la tabla PERSONA
    $sql = "INSERT INTO PERSONA (nom, cognom, email, contrasenya) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $nom, $cognom, $email, $contrasenya);
    if ($stmt->execute()) {
        // Insertar datos en la tabla USUARI
        $sql = "INSERT INTO USUARI (email, nomOrg) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $email, $nomOrg);
        if ($stmt->execute()) {
            // Insertar datos en la tabla US_PERTANY_GRU
            $sql = "INSERT INTO US_PERTANY_GRU (emailU, nomG) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $email, $nomG);
            if ($stmt->execute()) {
                $_SESSION["success_msg"] = "Usuario creado correctamente.";
                header("Location: servicesAdminform.php");
                exit();
            } else {
                $_SESSION["error_msg"] = "No se ha podido asignar el grupo al usuario.";
                header("Location: crearUsuariform.php");
                exit();
            }
        } else {
            $_SESSION["error_msg"] = "No se ha podido crear el usuario en la tabla USUARI.";
            header("Location: crearUsuariform.php");
            exit();
        }
    } else {
        $_SESSION["error_msg"] = "No se ha podido crear el usuario en la tabla PERSONA.";
        header("Location: crearUsuariform.php");
        exit();
    }
} else {
    $_SESSION["error_msg"] = "No se ha podido crear el usuario.";
    header("Location: crearUsuariform.php");
    exit();
}
?>