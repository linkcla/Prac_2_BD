<!-- @Author: Blanca Atienzar Martinez (HTML y CSS) -->
<!-- @Author: Hai Zi Bibiloni Trobat -->

<?php session_start() ;
include "conexion.php";
$conn = Conexion::getConnection();  

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['email'])) {
    header("Location: loginform.php");
    exit();
}

$email = $_SESSION['email'];

// Obtener el nombre de la organización del usuario
$sql = "SELECT p.email, p.nom, p.cognom, p.contrasenya, personal.dni FROM PERSONA as p
        JOIN personal ON p.email = personal.email";
$result = mysqli_query($conn, $sql);

if ($result->num_rows > 0) {
    $usuario = $result->fetch_assoc();
    
} else {
    $_SESSION["error_msg"] = "Usuario no encontrado.";
    header("Location: loginform.php");
    exit();
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
                            <div class="overlay-content">
                                <a href="servicesUsuariform.php">Usuari</a>
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
            <?php echo ($usuario['nom']) . ' ' . ($usuario['cognom']); ?> 
            </h2>
            <?php
        if (isset($_SESSION['success_msg'])) {
            echo "<div class='alert alert-success' role='alert'>{$_SESSION['success_msg']}</div>";
            unset($_SESSION['success_msg']);
        }
        if (isset($_SESSION['error_msg'])) {
            echo "<div class='alert alert-danger' role='alert'>{$_SESSION['error_msg']}</div>";
            unset($_SESSION['error_msg']);
        }
        if (isset($_SESSION['info_msg'])) {
            echo "<div class='alert alert-info' role='alert'>{$_SESSION['info_msg']}</div>";
            unset($_SESSION['info_msg']);
        }
        ?>
        <div class="text-center mt-4">
            <form id="deleteForm" action="borrarPersona.php" method="post" onsubmit="return confirmDelete()" class="d-inline">
                <button type="submit" id="borrarBtn" class="btn btn-primary mx-2">Borrar</button>
                <input type="hidden" id="selectedEmail" name="email" value="<?php echo $email; ?>">
            </form>
            <form id="editForm" action="editarPersonalForm.php" method="post" class="d-inline">
                <button type="submit" id="editarBtn" class="btn btn-primary mx-2">Editar</button>
                <input type="hidden" id="selectedEmailEdit" name="email" value="<?php echo $email; ?>">
            </form>
               </div>
                <div class="mt-4">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Cognom</th>
                                <th>Email</th>
                                <th>DNI</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><?php echo ($usuario['nom']); ?></td>
                                <td><?php echo ($usuario['cognom']); ?></td>
                                <td><?php echo ($usuario['email']); ?></td>
                                <td><?php echo ($usuario['dni']); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </form>
        </div>
        <script>
            function confirmDelete() {
                return confirm("Estas segur de que vols eliminarel teu compte?\nDeixaras de pertanyer a l'organització i a la plataforma.");
            }
        </script>             
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