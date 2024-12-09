<!-- Author: Marc -->
<?php
session_start();
require_once "conexion.php";
$conn = Conexion::getConnection();

$email = $_POST['selectedRow'];
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
                    <button type="submit" class="btn btn-primary" formaction="gestUsForm.php">Tornar arrera</button>
                </div>
                </div>
            </form>
        </div>
    </section>

    <section>
        <div class="container">
            <form id="formulari" action="editarOrg.php" method="post">
                <?php
                    $select = "SELECT nom FROM grup";
                    $resultGrups = mySQLi_query($conn, $select);
                    if (!$resultGrups) {
                        // no debería pasar nunca
                        die('Error: ' . mySQLi_error($conn));
                    }

                    $select = "SELECT grup FROM usuari WHERE email = '$email'";
                    $resultGrupUsuari = mySQLi_query($conn, $select);
                    if (!$resultGrupUsuari) {
                        // no debería pasar nunca
                        die('Error: ' . mySQLi_error($conn));
                    }
                    $grupUsuari = mySQLi_fetch_assoc($resultGrupUsuari)['grup'];
                ?>
                

                <h2><?php echo "Editant el grup de l'usuari amb email: $email"; ?></h2>
                <div class="form-group">
                    <input type="hidden" name="email" value="<?php echo $email; ?>">
                    <label for="adreca">Grup:</label>
                    <select class="form-control" id="grup" name="grup">
                        <?php
                        while ($fila = mySQLi_fetch_assoc($resultGrups)) {
                            $grup = $fila['nom'];
                            // Si el grupo es el mismo que el del usuario, lo seleccionamos
                            $selected = ($grup == $grupUsuari) ? 'selected' : '';
                            echo "<option value='$grup' $selected>$grup</option>";
                        }
                        ?>
                    </select>
                </div>

                <button type="button" class="btn btn-primary mt-3" id="botRealitzarCaviG" name="action" value="delete">Realitzar canvis</button>
            </form>
            <script>
            const form = document.getElementById('formulari');
            const realitzarCaviG = document.getElementById('botRealitzarCaviG');

            botRealitzarCaviG.addEventListener('click', function() {
                form.action = 'canviarGrupUsuari.php';
                form.submit();
            });

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
