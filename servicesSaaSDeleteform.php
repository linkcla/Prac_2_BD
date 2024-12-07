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
                Servicios SaaS - Eliminar
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
                            <th>Test 
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $cadenaTest = "SELECT nom FROM TEST";
                        $resultadoContracte = mysqli_query($conn, $cadenaTest);

                        while ($rowContracte = $resultadoContracte->fetch_assoc()) {
                            $value = $rowContracte['nom'];

                            echo "<tr>
                                <td>
                                    <input type='checkbox' name='selectedRows' value='{$value}'>
                                </td>
                                <td>{$rowContracte['nom']}</td>
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
    
        <div class="container">
            <h2>Componentes SAAS</h2>
            <!-- Tabla para tipusMCMS -->
            <h3>tipusMCMS</h3>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>tipusMCMS</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT DISTINCT tipusMCMS FROM SAAS";
                    $result = mysqli_query($conn, $sql);
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr><td>{$row['tipusMCMS']}</td></tr>";
                    }
                    ?>
                </tbody>
            </table>

            <!-- Tabla para tipusCDN -->
            <h3>tipusCDN</h3>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>tipusCDN</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT DISTINCT tipusCDN FROM SAAS";
                    $result = mysqli_query($conn, $sql);
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr><td>{$row['tipusCDN']}</td></tr>";
                    }
                    ?>
                </tbody>
            </table>

            <!-- Tabla para tipusSSL -->
            <h3>tipusSSL</h3>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>tipusSSL</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT DISTINCT tipusSSL FROM SAAS";
                    $result = mysqli_query($conn, $sql);
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr><td>{$row['tipusSSL']}</td></tr>";
                    }
                    ?>
                </tbody>
            </table>

            <!-- Tabla para tipusSGBD -->
            <h3>tipusSGBD</h3>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>tipusSGBD</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT DISTINCT tipusSGBD FROM SAAS";
                    $result = mysqli_query($conn, $sql);
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr><td>{$row['tipusSGBD']}</td></tr>";
                    }
                    ?>
                </tbody>
            </table>

            <!-- Tabla para tipusRam -->
            <h3>tipusRam</h3>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>tipusRam</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT DISTINCT tipusRam FROM SAAS";
                    $result = mysqli_query($conn, $sql);
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr><td>{$row['tipusRam']}</td></tr>";
                    }
                    ?>
                </tbody>
            </table>

            <!-- Tabla para GBRam -->
            <h3>GBRam</h3>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>GBRam</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT DISTINCT GBRam FROM SAAS";
                    $result = mysqli_query($conn, $sql);
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr><td>{$row['GBRam']}</td></tr>";
                    }
                    ?>
                </tbody>
            </table>

            <!-- Tabla para tipusDD -->
            <h3>tipusDD</h3>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>tipusDD</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT DISTINCT tipusDD FROM SAAS";
                    $result = mysqli_query($conn, $sql);
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr><td>{$row['tipusDD']}</td></tr>";
                    }
                    ?>
                </tbody>
            </table>

            <!-- Tabla para GBDD -->
            <h3>GBDD</h3>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>GBDD</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT DISTINCT GBDD FROM SAAS";
                    $result = mysqli_query($conn, $sql);
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr><td>{$row['GBDD']}</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
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