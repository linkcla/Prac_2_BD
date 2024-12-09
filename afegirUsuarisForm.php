<!-- @Author: Marc -->

<?php session_start();
include "conexion.php";
$conn = Conexion::getConnection();

// Obtener todos los usuarios que no pertenecen a ninguna organización
$selectUsuaris = "SELECT email FROM USUARI WHERE nomOrg IS NULL";
$resultUsuaris = mysqli_query($conn, $selectUsuaris);

// Obtener todos los grupos disponibles
$selectGrups = "SELECT nom FROM GRUP";
$resultGrups = mysqli_query($conn, $selectGrups);
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
                <nav class="navbar navbar-expand-lg custom_nav-container"  style="min-height: calc(100vh - 200px);">
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

    <section class="about_section layout_paddingAbout">
        <div class="container">
            <h2 class="text-uppercase">
                Afegir Usuaris a l'Organització
            </h2>
            <form>
                <div class="container">
                    <button type="submit" class="btn btn-primary" formaction="gestOrgForm.php">Tornar arrera</button>
                </div>
            </form>
        </div>
    </section>

    <section>
        <div class="container">
            <form action="afegirUsuaris.php" method="post">
                <div class="form-group">
                    <label for="usuari">Seleccionar usuari:</label>
                    <select class="form-control" id="usuari" name="usuari" required>
                        <option value="">Selecciona un usuari</option>
                        <?php
                        while ($fila = mysqli_fetch_assoc($resultUsuaris)) {
                            echo "<option value='{$fila['email']}'>{$fila['email']}</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="grup">Seleccionar grup:</label>
                    <select class="form-control" id="grup" name="grup" required>
                        <option value="">Selecciona un grup</option>
                        <?php
                        while ($fila = mysqli_fetch_assoc($resultGrups)) {
                            echo "<option value='{$fila['nom']}'>{$fila['nom']}</option>";
                        }
                        ?>
                    </select>
                </div>

                <br>
                <button type="submit" class="btn btn-primary">Afegir Usuari</button>
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