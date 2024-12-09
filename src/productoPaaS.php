<?php
require_once 'conexio.php';

class PaaS {

    public static function crear($tipoRam, $gbRam, $tipoDiscDur, $gbDiscDur, $modelCpu, $nNuclisCpu, $nomSo, $tipoIpv, $direccionIpv, $nombreProducto, $descripcionProducto) {
        $conn = Conexion::getConnection();

        

        // Consultas para obtener opciones y precios de los componentes seleccionados
        $res = mysqli_query($conn, "SELECT tipus, GB FROM RAM WHERE tipus = '$tipoRam'");
        while ($row = $res->fetch_assoc()) $ramOptions[] = $row;

        $res = mysqli_query($conn, "SELECT preu FROM RAM WHERE tipus = '$tipoRam' AND GB = '$gbRam'");
        if ($row = $res->fetch_assoc()) $precioRam = $row['preu'];

        $res = mysqli_query($conn, "SELECT tipus, GB FROM DISC_DUR WHERE tipus = '$tipoDiscDur'");
        while ($row = $res->fetch_assoc()) $discDurOptions[] = $row;

        $res = mysqli_query($conn, "SELECT preu FROM DISC_DUR WHERE tipus = '$tipoDiscDur' AND GB = '$gbDiscDur'");
        if ($row = $res->fetch_assoc()) $precioDiscDur = $row['preu'];

        $res = mysqli_query($conn, "SELECT model, nNuclis FROM CPU WHERE model = '$modelCpu'");
        while ($row = $res->fetch_assoc()) $cpuOptions[] = $row;

        $res = mysqli_query($conn, "SELECT preu FROM CPU WHERE model = '$modelCpu' AND nNuclis = '$nNuclisCpu'");
        if ($row = $res->fetch_assoc()) $precioCpu = $row['preu'];

        $res = mysqli_query($conn, "SELECT preu FROM SO WHERE nom = '$nomSo'");
        if ($row = $res->fetch_assoc()) $precioSo = $row['preu'];

        // Variables para almacenar las direcciones iPv4 e iPv6
        $iPv4 = $tipoIpv === 'iPv4' ? $direccionIpv : '';
        $iPv6 = $tipoIpv === 'iPv6' ? $direccionIpv : '';

        // Si se ha pulsado el botón de crear PaaS
        if (isset($_POST['crear_paas'])) {
            // Validación de los campos de iPv4 e iPv6
            if ($tipoIpv === 'iPv4' && !preg_match('/^[0-9.]+$/', $direccionIpv)) {
                echo "<div class='alert alert-danger' role='alert'>Dirección IPv4 no válida.</div>";
            } elseif ($tipoIpv === 'iPv6' && !preg_match('/^[0-9.]+$/', $direccionIpv)) {
                echo "<div class='alert alert-danger' role='alert'>Dirección IPv6 no válida.</div>";
            } else {
                // Validación de los campos obligatorios
                if ($tipoRam && $gbRam && $tipoDiscDur && $gbDiscDur && $modelCpu && $nNuclisCpu && $nomSo && $tipoIpv && $direccionIpv && $nombreProducto && $descripcionProducto) {
                    
                    // Insertar los datos del producto
                    $queryProducte = "INSERT INTO PRODUCTE (nom, descripcio) VALUES ('$nombreProducto', '$descripcionProducto')";

                    // Si se ha insertado correctamente el producto
                    if (mysqli_query($conn, $queryProducte)) {
                        // Obtener el id del producto insertado
                        $idConfig = mysqli_insert_id($conn);

                        // Insertar los datos del PaaS
                        $queryPaas = "INSERT INTO PAAS (idConfig, iPv4, iPv6, tipusRAM, GBRam, tipusDD, GBDD, modelCPU, nNuclis, nomSO) 
                        VALUES ($idConfig, '$iPv4', '$iPv6', '$tipoRam', $gbRam, '$tipoDiscDur', $gbDiscDur, '$modelCpu', '$nNuclisCpu', '$nomSo')";

                        // Si se ha insertado correctamente el PaaS
                        if (mysqli_query($conn, $queryPaas)) {
                            // Cuando se inicia sesion, en la clase loginform.php se guarda el email en la variable $_SESSION['email']
                            $emailP = $_SESSION['email'];

                            // Verificar si el email existe en la tabla 'personal'
                            $queryEmail = "SELECT email FROM PERSONAL WHERE email = '$emailP'";
                            $res = mysqli_query($conn, $queryEmail);

                            // Si el email existe en la tabla 'personal'
                            if ($res->num_rows > 0) {
                                // Insertar los datos de la creación del producto
                                $queryPersonalCreaProducte = "INSERT INTO PERSONAL_CREA_PRODUCTE (emailP, idConfigProducte) VALUES ('$emailP', $idConfig)";
                                
                                // Si se ha insertado correctamente la creación del producto
                                if (mysqli_query($conn, $queryPersonalCreaProducte)) {
                                    echo "<div class='alert alert-success' role='alert'>PaaS creado exitosamente.</div>";
                                } else {
                                    echo "<p>Error al registrar la creación del producto: " . mysqli_error($conn) . "</p>";
                                }
                            } else {
                                echo "<div class='alert alert-danger' role='alert'>Error: El email no existe en la tabla 'personal'.</div>";
                            }
                        } else {
                            echo "<p>Error al crear el PaaS: " . mysqli_error($conn) . "</p>";
                        }
                    } else {
                        echo "<p>Error al crear el producto: " . mysqli_error($conn) . "</p>";
                    }
                } else {
                    echo "<div class='alert alert-warning' role='alert'>Todos los campos son obligatorios.</div>";
                }
            }
        }


        
    }

    
    public static function editar() {
        $conn = Conexion::getConnection();
        
    }

    public static function eliminar() {
        $conn = Conexion::getConnection();

        
    }
}
?>