<!-- @Author: Blanca Atienzar Martinez (HTML y CSS) -->

<?php session_start();
include "conexion.php";
$conn = Conexion::getConnection();  

// Verificar si nomOrg está definido en la sesión
if (!isset($_SESSION['nomOrg'])) {
    $_SESSION["error_msg"] = "No se ha podido obtener la organización del administrador.";
    header("Location: servicesAdminform.php");
    exit();
}

$nomOrg = $_SESSION['nomOrg'];

// Obtener los grupos de la base de datos
$sql = "SELECT nom FROM GRUP";
$result = $conn->query($sql);
$grupos = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $grupos[] = $row['nom'];
    }
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
                                <a href="servicesSaaSform.php">SaaS</a>
                            </div>
                            <div class="overlay-content">
                                <a href="servicesPaaSform.php">PaaS</a>
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
                Crear usuari
            </h2>
            
        </div>
    
        <div class="container">
            <div class="row d-flex">
                <!-- Columna izquierda: Formulario de creación -->
                <div class="col-md-8">
                    <form action="crearUsuari.php" method="POST">
                        <div class="form-group">
                            <label for="nom">Nom:</label>
                            <input type="text" class="form-control" id="nom" name="nom" required>
                        </div>
                        <div class="form-group">
                            <label for="cognom">Cognom:</label>
                            <input type="text" class="form-control" id="cognom" name="cognom" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email:</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="contrasenya">Contrasenya:</label>
                            <input type="password" class="form-control" id="contrasenya" name="contrasenya" required>
                        </div>
                        <div class="form-group">
                            <label for="nomG">Grup:</label>
                            <select class="form-control" id="nomG" name="nomG" required>
                                <?php foreach ($grupos as $grupo): ?>
                                    <option value="<?php echo htmlspecialchars($grupo); ?>"><?php echo htmlspecialchars($grupo); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <input type="hidden" name="nomOrg" value="<?php echo $nomOrg; ?>">
                        <button type="submit" class="btn btn-primary">Crear</button>
                    </form>
                </div>
                <!-- Columna derecha: Resumen de selección -->
                <div class="col-md-4">
                    <div class="sticky-top">
                        <!-- Aquí puedes agregar un resumen o información adicional si es necesario -->
                    </div>
                </div>
            </div>
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