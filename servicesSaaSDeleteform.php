<!-- @Author: Blanca Atienzar Martinez (HTML, CSS y funcionalidad de SaaS) -->

<?php session_start() ;
include "conexion.php";
$conn = Conexion::getConnection();   
if (isset($_SESSION['success_msg'])) {
    echo "<div class='alert alert-success' role='alert'>{$_SESSION['success_msg']}</div>";
    unset($_SESSION['success_msg']);
}
if (isset($_SESSION['error_msg'])) {
    echo "<div class='alert alert-danger' role='alert'>{$_SESSION['error_msg']}</div>";
    unset($_SESSION['error_msg']);
}           
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
            <h2 class="text-uppercase">
                Servicios SaaS - Visualizar
            </h2>
            <form>
                <div class="container">
                <button type="submit" class="btn btn-primary" formaction="servicesSaaSViewform.php">Inicio</button>
                    <button type="submit" class="btn btn-primary" formaction="servicesSaaSPersonalform.php">Contratos SaaS</button>
                    <button type="submit" class="btn btn-primary" formaction="servicesSaaSCreateform.php">Crear</button>
                    <button type="submit" class="btn btn-primary" formaction="servicesSaaSDeleteform.php">Eliminar</button>
                    <button type="submit" class="btn btn-primary" formaction="servicesSaaSTestform.php">Test</button>
                </div>
            </form>
        </div>
        <div class="container">
            <form action="servicesSaaSDeleteBD.php" method="POST" onsubmit="return validateForm()">
                <!-- Tabla para mostrar los datos de CONTRACTE -->
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Seleccionar</th>
                            <th>ID Configuración</th>
                            <th>Dominio</th>
                            <th>Fecha Creación</th>
                            <th>Modulo CMS</th>
                            <th>CDN</th>
                            <th>SSL</th>
                            <th>SGBD</th>
                            <th>RAM</th>
                            <th>DD</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $cadenaContracte = "SELECT * FROM SAAS";
                        $resultadoContracte = mysqli_query($conn, $cadenaContracte);

                        while ($rowContracte = $resultadoContracte->fetch_assoc()) {
                            $value = $rowContracte['idConfig'] . '|' . $rowContracte['domini'] . '|' . $rowContracte['dataCreacio'] . '|' . $rowContracte['tipusMCMS'] . '|' . 
                            $rowContracte['tipusCDN'] . '|' . $rowContracte['tipusSSL'] . '|' . $rowContracte['tipusSGBD'] . '|' . $rowContracte['tipusRam'] . '|' . $rowContracte['GBRam'] . '|' . $rowContracte['tipusDD'] . '|' . $rowContracte['GBDD'];

                            echo "<tr>
                                <td>
                                    <input type='checkbox' name='selectedRows[]' value='{$value}'>
                                </td>
                                <td>{$rowContracte['idConfig']}</td>
                                <td>{$rowContracte['domini']}</td>
                                <td>{$rowContracte['dataCreacio']}</td>
                                <td>{$rowContracte['tipusMCMS']}</td>
                                <td>{$rowContracte['tipusCDN']}</td>
                                <td>{$rowContracte['tipusSSL']}</td>
                                <td>{$rowContracte['tipusSGBD']}</td>
                                <td>{$rowContracte['tipusRam']} - {$rowContracte['GBRam']} GB</td>
                                <td>{$rowContracte['tipusDD']} - {$rowContracte['GBDD']} GB</td>
                            </tr>";
                        }
                        ?>
                    </tbody>
                </table>
                <button type="submit" class="btn btn-primary mt-3" name="action" value="delete">Eliminar Seleccionados</button>
            </form>
            <script>
                function validateForm() {
                    const checkboxes = document.querySelectorAll('input[name="selectedRows[]"]:checked');
                    if (checkboxes.length === 0) {
                        alert('Por favor, selecciona al menos un registro.');
                        return false;
                    }
                    return true;
                }
            </script>
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