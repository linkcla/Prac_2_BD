<!-- @Author: Marc -->

<?php session_start();
require_once "./src/conexio.php";
$conn = Conexion::getConnection();

// Mostrar mnensajes 
if (isset($_SESSION['error_msg'])) {
    echo "<script>alert('{$_SESSION['error_msg']}')</script>";
    unset($_SESSION['error_msg']);
}
if (isset($_SESSION['success_msg'])) {
    echo "<script>alert('{$_SESSION['success_msg']}')</script>";
    unset($_SESSION['success_msg']);
}

// Obtener todos los grupos disponibles
$selectGrups = "SELECT nom FROM GRUP";
$resultGrups = mysqli_query($conn, $selectGrups);

// Verificar si se ha seleccionado un grupo
$nomGrup = isset($_GET['nom']) ? $_GET['nom'] : '';

// Obtener los privilegios actuales del grupo seleccionado
$privilegisActuals = [];
if ($nomGrup) {
    $selectPrivilegisActuals = "SELECT tipusPriv FROM PRIV_DE_GRUP WHERE nomG = '$nomGrup'";
    $resultPrivilegisActuals = mysqli_query($conn, $selectPrivilegisActuals);
    while ($fila = mysqli_fetch_assoc($resultPrivilegisActuals)) {
        $privilegisActuals[] = $fila['tipusPriv'];
    }
}

// Obtener todos los privilegios disponibles
$selectPrivilegis = "SELECT tipus FROM PRIVILEGI";
$resultPrivilegis = mysqli_query($conn, $selectPrivilegis);
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
                                <a href="gestOrgForm.php">Gestionar Organitzacions</a>
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
                Gestionar Organitzacions
            </h2>
            <form>
                <div class="container">
                    <button type="submit" class="btn btn-primary" formaction="gestUsForm.php">Tornar arrera</button>
                </div>
            </form>
        </div>
        <div class="container">
            <form method="get" action="editarGrupForm.php">
                <div class="form-group">
                    <label for="nom">Seleccionar grup:</label>
                    <select class="form-control" id="nom" name="nom" onchange="this.form.submit()">
                        <option value="">Selecciona un grup</option>
                        <?php
                        while ($fila = mysqli_fetch_assoc($resultGrups)) {
                            $selected = ($fila['nom'] == $nomGrup) ? 'selected' : '';
                            echo "<option value='{$fila['nom']}' $selected>{$fila['nom']}</option>";
                        }
                        ?>
                    </select>
                </div>
            </form>

            <?php if ($nomGrup): ?>
            <form action="./src/vista/grupVista.php" method="post">
                <input type="hidden" name="accio" value="editar">
                <br>
                <h2>Editant el grup: <?php echo $nomGrup; ?></h2>

                <input type="hidden" name="nom" value="<?php echo $nomGrup; ?>">

                <div class="form-group">
                    <label for="privilegis">Privilegis:</label>
                    <?php
                    while ($fila = mysqli_fetch_assoc($resultPrivilegis)) {
                        $privilegi = $fila['tipus'];
                        $checked = in_array($privilegi, $privilegisActuals) ? 'checked' : '';
                        echo "<div class='form-check'>
                                <input class='form-check-input' type='checkbox' name='privilegis[]' value='$privilegi' id='$privilegi' $checked>
                                <label class='form-check-label' for='$privilegi'>$privilegi</label>
                              </div>";
                    }
                    ?>
                </div>

                <br>
                <button type="submit" class="btn btn-primary">Guardar canvis</button>
            </form>
            <?php endif; ?>
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