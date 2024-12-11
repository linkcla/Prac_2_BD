<!-- @Author: Hai Zi Bibiloni Trobat (PHP y funcionalidad) -->
 
<?php
session_start();
include "conexion.php";
$conn = Conexion::getConnection();  

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['email'])) {
    header("Location: loginform.php");
    exit();
}

$tipusMCMS = $_POST['tipusMCMS'];
$tipusCDN = $_POST['tipusCDN'];
$tipusSSL = $_POST['tipusSSL'];
$tipusSGBD = $_POST['tipusSGBD'];
$tipusRAM = $_POST['tipusRAM'];
$gbRAM = $_POST['gbRAM'];
$tipusDD = $_POST['tipusDD'];
$gbDD = $_POST['gbDD'];
$domini = $_POST['domini'];
$emailU = $_SESSION['email']; 
$nomOrg = $_SESSION['nomOrg']; 
$estat = "Actiu";
$mesos = $_POST['mesos']; 
$dataInici = date("Y-m-d");

// Comprobar si la combinación de tipusDD y GBDD existe en la tabla DISC_DUR
$sql = "SELECT * FROM DISC_DUR WHERE tipus = ? AND GB = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $tipusDD, $gbDD);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    $_SESSION['error_msg'] = "La combinación de tipusDD y GBDD no existe en la base de datos.";
    header("Location: comprarProductesform.php");
    exit();
}

// Comprobar si la combinación de tipusRAM y GBRAM existe en la tabla RAM
$sql = "SELECT * FROM RAM WHERE tipus = ? AND GB = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $tipusRAM, $gbRAM);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    $_SESSION['error_msg'] = "La combinación de tipusRAM y GBRAM no existe en la base de datos.";
    header("Location: comprarProductesform.php");
    exit();
}

// Comprobar si el valor de mesos existe en la tabla DURADA
$sql = "SELECT * FROM DURADA WHERE mesos = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $mesos);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    $_SESSION['error_msg'] = "El valor de mesos no existe en la base de datos.";
    header("Location: comprarProductesform.php");
    exit();
}

// Comprobar si el producto ya existe en la base de datos
$sql = "SELECT idConfig FROM SAAS WHERE tipusMCMS = ? AND tipusCDN = ? AND tipusSSL = ? AND tipusSGBD = ? AND tipusRAM = ? AND gbRAM = ? AND tipusDD = ? AND gbDD = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssssss", $tipusMCMS, $tipusCDN, $tipusSSL, $tipusSGBD, $tipusRAM, $gbRAM, $tipusDD, $gbDD);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $idConfig = $row['idConfig'];

    // Comprobar si el idConfigProducte tiene un estado de "Aprovat" en la tabla ESTAT
    $sql = "SELECT estat FROM ESTAT WHERE idConfigProducte = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $idConfig);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $estatProducte = $row['estat'];

        if ($estatProducte == 'Aprovat') {
            // Comprobar si ya existe un contrato con las mismas características y el mismo dominio
            $sql = "SELECT * FROM CONTRACTE WHERE domini = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $domini);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $_SESSION['error_msg'] = "Ja hi ha un contracte amb el mateix domini.";
            } else {
                // Insertar un nuevo registro en la tabla CONTRACTE
                $sql = "INSERT INTO CONTRACTE (dataInici, estat, nom, emailU, idConfigProducte, mesos, domini) VALUES (?, ?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sssssis", $dataInici, $estat, $nomOrg, $emailU, $idConfig, $mesos, $domini);
                $stmt->execute();

                $_SESSION['success_msg'] = "El producte existeix, s'ha afegit al contracte.";
            }
        } else {
            $_SESSION['error_msg'] = "El producte no té l'estat de 'Aprovat'.";
        }
    } else {
        $_SESSION['error_msg'] = "No se pudo obtener el estado del producto.";
    }
} else {
    $_SESSION['error_msg'] = "El producte no existeix.";
}

header("Location: comprarProductesform.php");
exit();
?>