<!-- @Author: Pau Toni Bibiloni Martínez -->

<?php session_start() ;
include "conexion.php";
$conn = Conexion::getConnection();              
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
                            <div class="overlay-content">
                                <a href="servicesform.php">Services</a>
                            </div>
                            <div class="overlay-content">
                                <a href="servicesSaaSViewform.php">SaaS</a>
                            </div>
                            <div class="overlay-content">
                                <a href="servicesPaaSPersonalInicioEditform.php">PaaS</a>
                            </div>  
                            <div class="overlay-content">
                                <a href="gestOrgForm.php">Gestionar Organitzacións</a>
                            </div>                       
                        </div>
                    </div>
                </nav>
            </div>
        </header>
        <!-- end header section -->
    </div>

    <!-- about section -->

    <section class="about_section layout_paddingAbout"  style="min-height: calc(100vh - 200px);">
        <div class="container">
            <h2 class="text-uppercase">
                Servicios PaaS - Visualizar
            </h2>
            <form>
                <div class="container">
                    <button type="submit" class="btn btn-primary" formaction="servicesPaaSPersonalInicioEditform.php">Inicio</button>
                    <button type="submit" class="btn btn-primary" formaction="servicesPaaSPersonalContratosform.php">Contratos PaaS</button>
                    <button type="submit" class="btn btn-primary" formaction="servicesPaaSPersonalViewform.php">Visualizar</button>
                    <button type="submit" class="btn btn-primary" formaction="servicesPaaSPersonalCreateform.php">Crear</button>
                    <button type="submit" class="btn btn-primary" formaction="servicesPaaSPersonalDeleteform.php">Eliminar</button>
                    <button type="submit" class="btn btn-primary" formaction="servicesPaaSPersonalTestform.php">Test</button>
                </div>
            </form>
        </div>
        <div class="container">
            <form action="servicesPaaSform.php" method="POST">
                <!-- Tabla para mostrar los datos de CONTRACTE -->
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID Configuración</th>
                            <th>iPv4</th>
                            <th>iPv6</th>
                            <th>SO</th>
                            <th>RAM</th>
                            <th>DD</th>
                            <th>CPU</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $cadenaContracte = "SELECT * FROM PAAS";
                        $resultadoContracte = mysqli_query($conn, $cadenaContracte);
                        if (!$resultadoContracte) {
                            echo "<tr><td colspan='7'>Error en la consulta: " . mysqli_error($conn) . "</td></tr>";
                        } else {
                            while ($rowContracte = $resultadoContracte->fetch_assoc()) {
                                $iPv4 = isset($rowContracte['iPv4']) ? $rowContracte['iPv4'] : 'N/A';
                                $iPv6 = isset($rowContracte['iPv6']) ? $rowContracte['iPv6'] : 'N/A';

                                echo "<tr>
                                    <td>{$rowContracte['idConfig']}</td>
                                    <td>{$iPv4}</td>
                                    <td>{$iPv6}</td>
                                    <td>{$rowContracte['nomSO']}</td>
                                    <td>{$rowContracte['tipusRAM']} - {$rowContracte['GBRam']} GB</td>
                                    <td>{$rowContracte['tipusDD']} - {$rowContracte['GBDD']} GB</td>
                                    <td>{$rowContracte['modelCPU']} - {$rowContracte['nNuclis']} cores</td>
                                    </tr>";
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </form>
        </div>
    </section>

    <!-- end about section -->


    <!-- footer section -->
    <section class="container-fluid footer_section">
        <p>
            &copy; 2024 (UIB - EPS). Design by MPHB
        </p>
    </section>
    <!-- footer section -->

    <!--script type="text/javascript" src="js/jquery-3.4.1.min.js"></script-->
    <!--script type="text/javascript" src="js/bootstrap.js"></script-->

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