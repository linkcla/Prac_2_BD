<!-- @Author: Marc -->

<?php session_start();
include "conexion.php";
$conn = Conexion::getConnection();

$nomOrg = $_SESSION['nomOrg'];
$permisos = $_SESSION['permisos'];
?>

<html>

<head>
    <!-- Basic -->
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!-- Mobile Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <!-- Site Metas -->
    <meta name="keywords" content="" />
    <meta name="description" content="" />
    <meta name="author" content="" />

    <title>MPHB</title>

    <!-- slider stylesheet -->
    <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.1.3/assets/owl.carousel.min.css" />

    <!-- bootstrap core css -->
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css" />

    <!-- fonts style -->
    <link href="https://fonts.googleapis.com/css?family=Poppins|Raleway:400,600|Righteous&display=swap"
        rel="stylesheet" />
    <!-- Custom styles for this template -->
    <link href="css/style.css" rel="stylesheet" />
    <!-- responsive style -->
    <link href="css/responsive.css" rel="stylesheet" />
    <style>
        .table th.id-column, .table td.id-column {
            width: 10%;
        }
        .table th.name-column, .table td.name-column {
            width: 15%;
        }
        .table th.description-column, .table td.description-column {
            width: 40%;
        }
    </style>
</head>

<body>
    <div class="hero_area">
        <!-- header section strats -->
        <header class="header_section">
            <div class="container-fluid">
                <nav class="navbar navbar-expand-lg custom_nav-container">
                    <a class="navbar-brand" href="loginform.php">
                        <span>
                            MPHB
                        </span>
                    </a>

                    
                    
                    <div class="navbar-collapse" id="">
                    

                        <div class="custom_menu-btn">
                            <button onclick="openNav()">
                                <span class="s-1"> </span>
                                <span class="s-2"> </span>
                                <span class="s-3"> </span>
                            </button>
                        </div>
                        <div id="myNav" class="overlay">
                            <div class="overlay-content">
                                <a href="loginform.php">Home</a>
                            </div>
                        </div>
                    </div>
                </nav>
            </div>
        </header>
        <!-- end header section -->
    </div>

    <!-- about section -->
    <section class="about_section layout_paddingAbout" style="min-height: calc(100vh - 200px);">
        <div class="container">
            <h2 class="text-uppercase">
                Benvingut, <?php echo $_SESSION['nom']; ?>
            </h2>
           <a href="servicesUsuariform.php" class="btn btn-primary mb-3">Editar perfil</a>
            <?php if (in_array('Visualizar', $permisos)): ?>
                <h3>Productes contratats per l'organització: <?php echo $nomOrg; ?></h3>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="id-column">ID Contracte</th>
                            <th class="name-column">Estat</th>
                            <th class="id-column">ID Config</th>
                            <th class="name-column">Nom del Producte</th>
                            <th class="id-column">Data de finalitzacio</th>
                            <th class="description-column">Descripció</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = "SELECT p.idConfig, p.nom, p.descripcio, c.idContracte, c.dataInici, c.mesos, c.estat, c.domini
                                  FROM CONTRACTE c 
                                  JOIN PRODUCTE p ON c.idConfigProducte = p.idConfig 
                                  WHERE c.nom = '$nomOrg'
                                  ORDER BY c.idContracte AND c.estat";
                        $result = mysqli_query($conn, $query);
                        while ($row = mysqli_fetch_assoc($result)) {
                            // Generar la descripció del producte a partir de les característiques
                            $descripcio = getDescripcio($row['idConfig'], $row['domini']);
                            $dataFin = calcularDataFinal($row['dataInici'], $row['mesos']);
                            echo "<tr>
                                    <td>{$row['idContracte']}</td>
                                    <td>{$row['estat']}</td>
                                    <td>{$row['idConfig']}</td>
                                    <td>{$row['nom']}</td>
                                    <td>{$dataFin}</td>
                                    <td>{$descripcio}</td>
                                  </tr>";
                        }
                        ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No tens permisos per visualitzar els productes contractats.</p>
            <?php endif; ?>
            <?php 
                if (in_array('Crear', $permisos)) {
                    echo '<a href="comprarProductesform.php" class="btn btn-primary">Contratar producte</a>';
                }
                // Si te tots els permisos significa que es administrador i pot gestionar els usuaris i grups de l'organització
                if (in_array('Editar', $permisos) && in_array('Borrar', $permisos) && in_array('Crear', $permisos) && in_array('Visualizar', $permisos)) {
                    echo '<a href="gestUsForm.php" class="btn btn-primary">Gestionar usuaris</a>';
                }
            ?>
        </div>
    </section>

    <!-- footer section -->
    <section class="container-fluid footer_section">
        <p>
            &copy; 2024 (UIB - EPS). Design by MPHB
        </p>
    </section>
    <!-- footer section -->

    <script>
        function openNav() {
            document.getElementById("myNav").classList.toggle("menu_width");
            document
                .querySelector(".custom_menu-btn")
                .classList.toggle("menu_btn-style");
        }
    </script>
</body>

</html>

<?php
    function getDescripcio($idConfig, $domini) {
        
        $query = "SELECT idConfig FROM paas WHERE idConfig = '{$idConfig}'";
        $result = mysqli_query(Conexion::getConnection(), $query);

        if (!$result) {
            $_SESSION['error_msg'] = "Error al consultar la base de dades la descripció.";
            header('Location: error.php');
            exit();
        }
        
        // Miram si es un PaaS o un SaaS. Si es un PaaS la condició serà falsa.
        if (mysqli_num_rows($result) == 0) {
            $descripcioSaaS = "SaaS: Domini: " . $row['domini'] . " | " . getSaaSDescripcio($idConfig);
            return $descripcioSaaS;
        }
        return getPaaSDescripcio($idConfig);
    }

    function getSaaSDescripcio($idConfig) {
        $query = "SELECT * FROM saas WHERE idConfig = '$idConfig'";
        $result = mysqli_query(Conexion::getConnection(), $query);

        if (!$result) {
            $_SESSION['error_msg'] = "Error al consultar la base de dades la descripció.";
            header('Location: error.php');
            exit();
        }
        $descripcioSaaS = "";
        // Només hi haurà una fila ja que l'idConfig és clau primària.
        // Concatenam tota la informació per poder mostrar-la en una sola fila.
        while ($row = mysqli_fetch_assoc($result)) {
            $descripcioSaaS .= "Data Creacio: " . $row['dataCreacio'] . " | ";
            $descripcioSaaS .= "MCMS: " . $row['tipusMCMS'] . " | ";
            $descripcioSaaS .= "CDN: " . $row['tipusCDN'] . " | ";
            $descripcioSaaS .= "SSL: " . $row['tipusSSL'] . " | ";
            $descripcioSaaS .= "SGBD: " . $row['tipusSGBD'] . " | ";
            $descripcioSaaS .= "RAM: " . $row['tipusRam'] . " | ";
            $descripcioSaaS .= "GB RAM: " . $row['GBRam'] . " | ";
            $descripcioSaaS .= "Disc dur: " . $row['tipusDD'] . " | ";
            $descripcioSaaS .= "GB disc dur: " . $row['GBDD'];
        }
        return $descripcioSaaS;

    }

    function getPaaSDescripcio($idConfig) {
        $query = "SELECT * FROM paas WHERE idConfig = '$idConfig'";
        $result = mysqli_query(Conexion::getConnection(), $query);

        if (!$result) {
            $_SESSION['error_msg'] = "Error al consultar la base de dades la descripció.";
            header('Location: error.php');
            exit();
        }

        $descripcioPaas = "PaaS: ";
        // Només hi haurà una fila ja que l'idConfig és clau primària.
        // Concatenam tota la informació per poder mostrar-la en una sola fila.
        while ($row = mysqli_fetch_assoc($result)) {
            $descripcioPaas .= "idConfig: " . $row['idConfig'] . " | ";
            if ($row['iPv4'] != null) {
            $descripcioPaas .= "iPv4: " . $row['iPv4'] . " | ";
            } else {
            $descripcioPaas .= "iPv6: " . $row['iPv6'] . " | ";
            }
            $descripcioPaas .= "tipusRAM: " . $row['tipusRAM'] . " | ";
            $descripcioPaas .= "GBRam: " . $row['GBRam'] . " | ";
            $descripcioPaas .= "tipusDD: " . $row['tipusDD'] . " | ";
            $descripcioPaas .= "GBDD: " . $row['GBDD'] . " | ";
            $descripcioPaas .= "modelCPU: " . $row['modelCPU'] . " | ";
            $descripcioPaas .= "nNuclis: " . $row['nNuclis'] . " | ";
            $descripcioPaas .= "nomSO: " . $row['nomSO'];
        }
        return $descripcioPaas;
    }

    function calcularDataFinal($dataInici, $mesos) {
        $data = new DateTime($dataInici);
        $data->modify("+$mesos months");
        return $data->format('Y-m-d');
    }
?>