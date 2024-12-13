<!-- @Author: Hai Zi Bibiloni Trobat -->

<?php session_start() ;
require_once "./src/conexio.php";
$conn = Conexion::getConnection();  

if (!isset($_POST['email'])) {
    $_SESSION["error_msg"] = "No s'ha seleccionat cap usuari.";
    header("Location: servicesUsuariform.php");
    exit();
}

$email = $_POST['email'];

// Per obtenir les dades de l'usuari seleccionat
$sql = "SELECT p.nom, p.cognom, p.contrasenya, u.email, u.nomOrg, u.grup AS nomG FROM USUARI u
        JOIN PERSONA p ON u.email = p.email
        WHERE u.email = '$email'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    $usuario = mysqli_fetch_assoc($result);
} else {
    $_SESSION["error_msg"] = "Usuari no trobat.";
    header("Location: servicesUsuariform.php");
    exit();
}

// Per obtenir els grups de la base de dades
$sql = "SELECT nom FROM GRUP";
$result = mysqli_query($conn, $sql);
$grupos = mysqli_fetch_all($result, MYSQLI_ASSOC);
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
                        </div>
                    </div>
                </nav>
            </div>
        </header>
    </div>

    <section class="about_section layout_paddingAbout"  style="min-height: calc(100vh - 200px);">
        <div class="container">
            <h2 class="text-uppercase">
                <?php echo $email; ?> 
            </h2>            
        </div>
        <br>
        <div class="container d-flex justify-content-center align-items-center"  >
            <div class="row d-flex justify-content-center">
                <div class="col-md-8">
                    <form action="./src/vista/usuariVista.php" method="POST" onsubmit="return confirmUpdate()">
                        <input type="hidden" name="accio" value="editarUsuari">
                        <input type="hidden" name="email" value="<?php echo $usuario['email']; ?>">
                            <div class="form-group">
                                <label for="nom">Nom:</label>
                                <input type="text" class="form-control" id="nom" name="nom" value="<?php echo $usuario['nom']; ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="cognom">Cognom:</label>
                                <input type="text" class="form-control" id="cognom" name="cognom" value="<?php echo $usuario['cognom']; ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="contrasenya">Contrasenya:</label>
                                <div class="input-group">
                                    <input type="password" class="form-control form-control-lg" id="contrasenya" name="contrasenya" value="<?php echo $usuario['contrasenya']; ?>" required>
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary" type="button" onclick="togglePassword()">Mostrar</button>
                                    </div>
                                </div>
                            </div>                        
                        <button type="submit" class="btn btn-primary">Actualizar</button>
                    </form>
                </div>
            </div>                       
        </div>
    </section>

    <section class="container-fluid footer_section">
        <p>
            &copy; 2024 (UIB - EPS). Design by MPHB
        </p>
    </section>
    
    <script>
        function openNav() {
            document.getElementById("myNav").classList.toggle("menu_width");
            document
                .querySelector(".custom_menu-btn")
                .classList.toggle("menu_btn-style");
        }

        // Funció per confirmar l'actualització de les dades
        function confirmUpdate() {
            return confirm('Estàs segur de que vols actualitzar les dades del teu usuari?');
        }

        // Funció per mostrar o amagar la contrasenya
        function togglePassword() {
            var passwordField = document.getElementById("contrasenya");
            var passwordFieldType = passwordField.type;
            if (passwordFieldType === "password") {
                passwordField.type = "text";
                event.target.textContent = "Amaga";
            } else {
                passwordField.type = "password";
                event.target.textContent = "Mostra";
            }
        }
    </script>
</body>
</html>