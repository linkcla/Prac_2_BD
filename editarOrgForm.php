<!-- @Author: Marc -->

<?php session_start() ;
require_once "./src/conexio.php";
$conn = Conexion::getConnection(); 

// Agafar els valors que s'han seleccionat.
$valorSeleccionat = $_POST['selectedRow'];
list($nom, $adreca, $telefon) = explode("|", $valorSeleccionat);
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

    <section class="about_section layout_paddingAbout"  style="min-height: calc(100vh - 200px);">
        <div class="container">
            <h2 class="text-uppercase">
                Gestionar Organitzacións
            </h2>
            <form>
                <div class="container">
                <div class="container">
                    <button type="submit" class="btn btn-primary" formaction="gestOrgForm.php">Tornar arrera</button>
                </div>
                </div>
            </form>
        </div>
        <div class="container">
            <form action="./src/vista/organitzacioVista.php" method="post">
                <input type="hidden" id="accio" name="accio" value="editar">
                <input type="hidden" name="nom" value="<?php echo $nom; ?>">
                <input type="hidden" name="adrecaAnterior" value="<?php echo $adreca; ?>">
                <input type="hidden" name="telefonAnterior" value="<?php echo $telefon; ?>">
                <br>
                <h2><?php echo "Editant l'organització: $nom"; ?></h2>
                <div class="form-group">
                    <label for="adreca">Direcció:</label>
                    <input type="text" id="adreca" name="adreca" class="form-control" required value="<?php echo $adreca; ?>" placeholder="<?php echo $adreca; ?>">
                </div>

                <div class="form-group">
                    <label for="telefon">Telèfon:</label>
                    <input type="tel" id="telefon" name="tlf" class="form-control" required value="<?php echo $telefon; ?>" placeholder="<?php echo $telefon; ?>">
                </div>

                <br>
                <button type="submit" class="btn btn-primary">Realitzar canvis</button>
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