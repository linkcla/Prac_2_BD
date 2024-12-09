<!-- @Author: Blanca Atienzar Martinez (HTML, CSS y funcionalidad de SaaS) -->

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
                                <a href="gestOrgform.php">Gestionar Organitzacións</a>
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
            <h2 class="text-uppercase" >
                Servicios SaaS - Contratos
            </h2>
            <form>    
                <div class="container">
                    <button type="submit" class="btn btn-primary" formaction="servicesSaaSViewform.php">Atras</button>
                    <button type="submit" class="btn btn-primary" formaction="servicesSaaSContratosform.php">Contratos SaaS</button>
                    <button type="submit" class="btn btn-primary" formaction="servicesSaaSTestform.php">Test</button>
                    <button type="submit" class="btn btn-primary" formaction="servicesSaaSComponentesform.php">Componentes SaaS</button>
                </div>
            </form>
        </div>
                     
    
        <div class="container">
            <form action=" " method="POST">
                <!-- Tabla para mostrar los datos de CONTRACTE -->
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
                        // Consulta para obtener los idConfig de la tabla SAAS
                        $cadenaSaas = "SELECT idConfig FROM SAAS";
                        $resultadoSaas = mysqli_query($conn, $cadenaSaas);
                        $idConfigs = [];
                        while ($rowSaas = $resultadoSaas->fetch_assoc()) {
                            $idConfigs[] = $rowSaas['idConfig'];
                        }
                        if (!empty($idConfigs)) {
                            $idConfigsString = implode(',', $idConfigs);

                            // Consulta para obtener los datos de la tabla CONTRACTE donde idConfigProducte esté en la lista de idConfigs
                            $cadenaContracte = "SELECT * FROM CONTRACTE WHERE idConfigProducte IN ($idConfigsString)";
                            $resultadoContracte = mysqli_query($conn, $cadenaContracte);
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