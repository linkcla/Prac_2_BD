<!-- @Author: Blanca Atienzar Martinez (HTML y CSS) -->

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
$sql = "SELECT p.nom, p.cognom, u.nomOrg FROM USUARI u
        JOIN PERSONA p ON u.email = p.email
        WHERE u.email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $adminName = $row['nom'] ." ". $row['cognom']. " - ";
    $nomOrg = $row['nomOrg'];
} else {
    $adminName = "";
    $nomOrg = "Sin organización";
}          

// Obtener los datos de los usuarios que pertenecen a la misma organización
$sql2 = "SELECT p.nom, p.cognom, u.email, g.nom AS nomG FROM USUARI u
        JOIN PERSONA p ON u.email = p.email
        LEFT JOIN US_PERTANY_GRUP upg ON u.email = upg.emailU
        LEFT JOIN GRUP g ON upg.nomG = g.nom
        WHERE u.nomOrg = ?";
$stmt2 = $conn->prepare($sql2);
$stmt2->bind_param("s", $nomOrg);
$stmt2->execute();
$result2 = $stmt2->get_result();
$usuarios = [];
while ($row = $result2->fetch_assoc()) {
    $usuarios[] = $row;
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
                Administrador - <?php echo $adminName; ?>  <?php echo $nomOrg; ?>
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
            <form id="deleteForm" action="borrarUsuari.php" method="post" onsubmit="return confirmDelete()" class="d-inline">
                <button type="submit" id="borrarBtn" class="btn btn-primary mx-2" disabled>Borrar</button>
                <input type="hidden" id="selectedEmail" name="email" value="">
            </form>
            <form id="editForm" action="editarUsuariform.php" method="post" class="d-inline">
                <button type="submit" id="editarBtn" class="btn btn-primary mx-2" disabled>Editar</button>
                <input type="hidden" id="selectedEmailEdit" name="email" value="">
            </form>
            <a href="crearUsuariform.php" class="btn btn-primary mx-2 d-inline">Crear</a>
        </div>
<div class="mt-4"> <!-- Añadido div con clase mt-4 para margen superior -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th></th> <!-- Columna para el radio button -->
                <th>Nom</th>
                <th>Cognom</th>
                <th>Email</th>
                <th>Grup</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($usuarios as $usuario): ?>
                <tr>
                    <td>
                        <input type="radio" name="email" value="<?php echo htmlspecialchars($usuario['email']); ?>" onclick="selectUser('<?php echo htmlspecialchars($usuario['email']); ?>')">
                    </td>
                    <td><?php echo htmlspecialchars($usuario['nom']); ?></td>
                    <td><?php echo htmlspecialchars($usuario['cognom']); ?></td>
                    <td><?php echo htmlspecialchars($usuario['email']); ?></td>
                    <td><?php echo htmlspecialchars($usuario['nomG']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
            </div>
        </form>
        </div>
    <script>
        function enableButtons() {
            document.getElementById('borrarBtn').disabled = false;
            document.getElementById('editarBtn').disabled = false;
        }

        function confirmDelete() {
            return confirm("¿Estás seguro de que deseas eliminar este usuario?");
        }

        function checkSelection() {
            const radios = document.getElementsByName('email');
            for (let i = 0; i < radios.length; i++) {
                if (radios[i].checked) {
                    return true;
                }
            }
            alert('Por favor, selecciona un usuario.');
            return false;
        }
        
        function selectUser(email) {
            document.getElementById('selectedEmail').value = email;
            document.getElementById('selectedEmailEdit').value = email;
            enableButtons();
        }
       
        function editUser() {
            const form = document.getElementById('adminForm');
            form.action = 'editarUsuariform.php';
            form.submit();
        }
        
    </script>  
        <div class="container">
            <div class="row d-flex">
                <!-- Columna izquierda: Formulario de selección -->
                <div class="col-md-8">
                    <form action="servicesSaaSform.php" method="POST">                    
                    <!-- añadir más componentes -->                    
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
    </script>
</body>
</html>