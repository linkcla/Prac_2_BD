<!-- @Author: Blanca Atienzar Martinez (HTML y CSS) -->

<?php session_start() ;
include "conexion.php";
$conn = Conexion::getConnection();  

if (!isset($_POST['email'])) {
    $_SESSION["error_msg"] = "No se ha seleccionado ningún usuario.";
    header("Location: servicesAdminform.php");
    exit();
}

$email = $_POST['email'];

// Obtener los datos del usuario seleccionado
$sql = "SELECT p.nom, p.cognom, p.contrasenya, u.email, u.nomOrg, g.nomG FROM USUARI u
        JOIN PERSONA p ON u.email = p.email
        LEFT JOIN US_PERTANY_GRU g ON u.email = g.emailU
        WHERE u.email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $usuario = $result->fetch_assoc();
} else {
    $_SESSION["error_msg"] = "Usuario no encontrado.";
    header("Location: servicesAdminform.php");
    exit();
}

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
                <?php echo $email; ?> 
            </h2>
            
        </div>
    
        <div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh;" >
            <div class="row d-flex justify-content-center">
                <!-- Columna izquierda: Formulario de selección -->
                <div class="col-md-8">
                <form action="editarUsuari.php" method="POST" onsubmit="return confirmUpdate()">
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
                        <div class="form-group">
                            <label for="nomOrg">Nom organització:</label>
                            <input type="text" class="form-control" id="nomOrg" name="nomOrg" value="<?php echo $usuario['nomOrg']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="nomG">Grup:</label>
                            <select class="form-control form-control-lg" id="nomG" name="nomG" required>
                                <?php foreach ($grupos as $grupo): ?>
                                    <option value="<?php echo htmlspecialchars($grupo); ?>" <?php echo ($grupo == $usuario['nomG']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($grupo); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Actualizar</button>
                    </form>
                </div>
                <!-- Columna derecha: Resumen de selección -->
                <div class="col-md-4">
                    <div class="sticky-top">
                       
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

        function confirmUpdate() {
            return confirm('¿Estás seguro de que deseas actualizar los datos del usuario?');
        }

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