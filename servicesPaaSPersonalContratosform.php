<!-- @Author: Blanca Atienzar Martinez -->
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
                                    <a href="servicesSaaSPersonalform.php">SaaS</a>
                                </div>
                                <div class="overlay-content">
                                    <a href="servicesPaaSPersonalInicioEditform.php">PaaS</a>
                                </div>
                                <div class="overlay-content">
                                    <a href="gestOrg.php">Gestionar Organitzacións</a>
                                </div>
                            </div>
                        </div>
                    </nav>
                </div>
            </header>
            <!-- end header section -->
        </div>

        <!-- about section -->

        <section class="about_section layout_paddingAbout">
            <div class="container">
                <h2 class="text-uppercase">
                    Servicios PaaS - Contratos
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
                    <!-- Tabla para mostrar los datos del contrato -->
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID Contracte</th>
                                <th>Data Inici</th>
                                <th>Estat</th>
                                <th>Nom Org</th>
                                <th>Email Usuari</th>
                                <th>ID Config Producte</th>
                                <th>Mesos</th>
                            </tr>
                        </thead>
                        <tbody>
                             <?php
                            // Consulta para obtener los datos de la tabla CONTRACTE donde idConfigProducte 
                            // esté en la lista de idConfigs de la tabla PAAS
                            $cadenaContracte = "SELECT c.* FROM CONTRACTE c
                                                JOIN PAAS s ON c.idConfigProducte = s.idConfig";
                            $resultadoContracte = mysqli_query($conn, $cadenaContracte);
                            
                            if ($resultadoContracte->num_rows > 0) {
                                while ($rowContracte = $resultadoContracte->fetch_assoc()) {
                                    echo "<tr>
                                        <td>{$rowContracte['idContracte']}</td>
                                        <td>{$rowContracte['dataInici']}</td>
                                        <td>{$rowContracte['estat']}</td>
                                        <td>{$rowContracte['nom']}</td>
                                        <td>{$rowContracte['emailU']}</td>
                                        <td>{$rowContracte['idConfigProducte']}</td>
                                        <td>{$rowContracte['mesos']}</td>
                                        </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='7'>No hay contratos disponibles.</td></tr>";
                            }

                            // PROCESO PARA ACTUALIZAR EL ESTADO DEL CONTRATO A "CANCELAT" SI SE ENVÍA EL FORMULARIO
                            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                                $idContracte = $_POST['idContracte'];
                                $cadenaUpdate = "UPDATE CONTRACTE SET estat = 'Cancelat' WHERE idContracte = '$idContracte'";
                                if (mysqli_query($conn, $cadenaUpdate)) {
                                    echo "<p>Contrato cancelado exitosamente.</p>";
                                } else {
                                    echo "<p>Error al cancelar el contrato: " . mysqli_error($conn) . "</p>";
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