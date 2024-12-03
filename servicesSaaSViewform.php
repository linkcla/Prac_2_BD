<!-- @Author: Blanca Atienzar Martinez (HTML y CSS) -->

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
                                <a href="servicesPaaSPersonalform.php">PaaS</a>
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
                Servicios SaaS - Visualizar
            </h2>
            <form>
                <div class="container">
                    <button type="submit" class="btn btn-primary" formaction="servicesSaaSPersonalform.php">Contratos SaaS</button>
                    <button type="submit" class="btn btn-primary" formaction="servicesSaaSViewform.php">Visualizar</button>
                    <button type="submit" class="btn btn-primary" formaction="servicesSaaSEditform.php" >Editar</button>
                    <button type="submit" class="btn btn-primary" formaction="servicesSaaSCreateform.php">Crear</button>
                    <button type="submit" class="btn btn-primary" formaction="servicesSaaSDeleteform.php">Eliminar</button>
                    <button type="submit" class="btn btn-primary" formaction="servicesSaaSTestform.php">Test</button>
                </div>
            </form>
        </div>
        <div class="container">
        <form action=" " method="POST">
                <!-- Tabla para mostrar los datos de CONTRACTE -->
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID Configuración</th>
                            <th>Dominio</th>
                            <th>Fecha Creación</th>
                            <th>Modulo CMS</th>
                            <th>CDN</th>
                            <th>SSL</th>
                            <th>SGBD</th>
                            <th>RAM</th>
                            <th>DD</th>
                            <th>Nombre del Test</th>
                            <th>Estado del Test</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                            // Procesar actualización de estado
                            $idConfig = $_POST['idConfig'];
                            $nomT = $_POST['nomT'];
                            $newState = $_POST['newState'];

                            $updateQuery = "
                                UPDATE ESTAT
                                SET estat = ?
                                WHERE idConfigProducte = ? AND nomT = ?
                            ";
                            $stmt = $conn->prepare($updateQuery);
                            $stmt->bind_param("sis", $newState, $idConfig, $nomT);

                            if ($stmt->execute()) {
                                echo "<div class='alert alert-success'>Estado actualizado correctamente</div>";
                            } else {
                                echo "<div class='alert alert-danger'>Error al actualizar: " . $stmt->error . "</div>";
                            }

                            $stmt->close();
                        }

                        $cadenaContracte = "
                            SELECT 
                                SAAS.idConfig, 
                                SAAS.domini, 
                                SAAS.dataCreacio, 
                                SAAS.tipusMCMS, 
                                SAAS.tipusCDN, 
                                SAAS.tipusSSL, 
                                SAAS.tipusSGBD, 
                                CONCAT(SAAS.tipusRam, ' - ', SAAS.GBRam, ' GB') AS ram,
                                CONCAT(SAAS.tipusDD, ' - ', SAAS.GBDD, ' GB') AS disc,
                                GROUP_CONCAT(TEST.nom ORDER BY TEST.nom SEPARATOR ', ') AS testNoms,
                                GROUP_CONCAT(ESTAT.estat ORDER BY TEST.nom SEPARATOR ', ') AS testEstats
                            FROM SAAS
                            LEFT JOIN ESTAT ON SAAS.idConfig = ESTAT.idConfigProducte
                            LEFT JOIN TEST ON ESTAT.nomT = TEST.nom
                            GROUP BY SAAS.idConfig
                            ORDER BY SAAS.idConfig
                        ";
                        
                        $resultadoContracte = mysqli_query($conn, $cadenaContracte);
                        
                        if (!$resultadoContracte) {
                            die("Error al obtener datos: " . mysqli_error($conn));
                        }

                        while ($rowContracte = $resultadoContracte->fetch_assoc()) {
                            echo "<tr>
                                <td>{$rowContracte['idConfig']}</td>
                                <td>{$rowContracte['domini']}</td>
                                <td>{$rowContracte['dataCreacio']}</td>
                                <td>{$rowContracte['tipusMCMS']}</td>
                                <td>{$rowContracte['tipusCDN']}</td>
                                <td>{$rowContracte['tipusSSL']}</td>
                                <td>{$rowContracte['tipusSGBD']}</td>
                                <td>{$rowContracte['ram']}</td>
                                <td>{$rowContracte['disc']}</td>
                                <td>{$rowContracte['testNoms']}</td>
                                <td>{$rowContracte['testEstats']}</td>
                            </tr>";
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