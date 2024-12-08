<!-- @Author: Marc -->

<?php session_start();
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
                                <a href="servicesPaaSfPersonalorm.php">PaaS</a>
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
                Gestionar Organitzacións
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
            <form action="./src/vista/grupVista.php" method="post">
                <input type="hidden" name="accio" value="crear">
                <br>
                <h2>Crear un grup</h2>

                <div class="form-group">
                    <label for="nom">Nom del grup:</label>
                    <input type="text" id="nom" name="nom" class="form-control" required placeholder="Ej. Administradors">
                </div>

                <div class="form-group">
                    <label for="privilegis">Privilegis:</label>
                    <?php
                    $select = "SELECT tipus FROM PRIVILEGI";
                    $resultPrivilegis = mySQLi_query($conn, $select);
                    if (!$resultPrivilegis) {
                        die('Error: ' . mySQLi_error($conn));
                    }
                    while ($fila = mySQLi_fetch_assoc($resultPrivilegis)) {
                        $privilegi = $fila['tipus'];
                        echo "<div class='form-check'>
                                <input class='form-check-input' type='checkbox' name='privilegis[]' value='$privilegi' id='$privilegi'>
                                <label class='form-check-label' for='$privilegi'>$privilegi</label>
                              </div>";
                    }
                    ?>
                </div>

                <br>
                <button type="submit" class="btn btn-primary">Crear</button>
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