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

// Recoger los datos del formulario
$ipType = $_POST['ip']; // Puede ser 'IPv4' o 'IPv6'
$ipValue = $_POST['ipValue']; // El valor de la IP
$tipusRAM = $_POST['tipusRAM'];
$gbRAM = $_POST['gbRAM'];
$tipusDD = $_POST['tipusDD'];
$gbDD = $_POST['gbDD'];
$modelCPU = $_POST['modelCPU'];
$nNuclis = $_POST['nNuclis'];
$nomSO = $_POST['nomSO'];
$emailU = $_SESSION['email']; 
$nomOrg = $_SESSION['nomOrg']; 
$estat = "Actiu";
$mesos = $_POST['mesos']; 
$dataInici = date("Y-m-d");

// Establecer los valores de IPv4 e IPv6 según la selección
if ($ipType == 'IPv4') {
    $iPv4 = $ipValue;
    $iPv6 = NULL;
} else {
    $iPv4 = NULL;
    $iPv6 = $ipValue;
}

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

// Comprobar si la combinación de modelCPU y nNuclis existe en la tabla CPU
$sql = "SELECT * FROM CPU WHERE model = ? AND nNuclis = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $modelCPU, $nNuclis);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    $_SESSION['error_msg'] = "La combinación de modelCPU y nNuclis no existe en la base de datos.";
    header("Location: comprarProductesform.php");
    exit();
}

// Comprobar si la IP seleccionada existe en la base de datos
$sql = "SELECT * FROM PAAS WHERE (iPv4 = ? OR iPv6 = ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $iPv4, $iPv6);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    $_SESSION['error_msg'] = "La IP seleccionada no existe en la base de datos.";
    header("Location: comprarProductesform.php");
    exit();
}

// Comprobar si el producto ya existe en la base de datos
$sql = "SELECT idConfig FROM PAAS WHERE tipusRAM = ? AND gbRAM = ? AND tipusDD = ? AND gbDD = ? AND modelCPU = ? AND nNuclis = ? AND nomSO = ? AND (iPv4 = ? OR iPv6 = ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssssssss", $tipusRAM, $gbRAM, $tipusDD, $gbDD, $modelCPU, $nNuclis, $nomSO, $iPv4, $iPv6);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $idConfig = $row['idConfig'];

    // Comprobar si el idConfigProducte tiene un estado de "Aprovat" en la tabla ESTAT
    $sql = "SELECT * FROM ESTAT WHERE idConfigProducte = ? AND estat = 'Aprovat'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $idConfig);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Comprobar si ya existe un contrato con el mismo idConfigProducte
        $sql = "SELECT * FROM CONTRACTE WHERE idConfigProducte = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $idConfig);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $_SESSION['error_msg'] = "Ja hi ha un contracte amb el mateix idConfigProducte.";
        } else {
            // Insertar un nuevo registro en la tabla CONTRACTE
            $sql = "INSERT INTO CONTRACTE (dataInici, estat, nom, emailU, idConfigProducte, mesos, domini) VALUES (?, ?, ?, ?, ?, ?, 'NULL')";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssssi", $dataInici, $estat, $nomOrg, $emailU, $idConfig, $mesos);
            $stmt->execute();

            $_SESSION['success_msg'] = "El contracte s'ha creat correctament.";
        }
    } else {
        $_SESSION['error_msg'] = "El producte no té l'estat de 'Aprovat'.";
    }
} else {
    $_SESSION['error_msg'] = "El producte no existeix.";
}

header("Location: comprarProductesform.php");
exit();
?>