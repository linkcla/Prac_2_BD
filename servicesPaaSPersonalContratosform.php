<!-- @Author: Pau Toni Bibiloni Martínez -->
<?php session_start(); 
include "src/conexio.php";
include "src/contratos.php";

$conn = Conexion::getConnection();
$contratos = new Contratos();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $idContracte = $_POST['idContracte'];
    $nuevoEstat = $_POST['estat'][$idContracte];
    $nuevosMesos = $_POST['mesos'][$idContracte];
    $contratos->actualizarContratoPaaS($conn, $idContracte, $nuevoEstat, $nuevosMesos);
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

    <section class="about_section layout_paddingAbout"  style="min-height: calc(100vh - 200px);">
        <div class="container">
            <h2 class="text-uppercase">
                Servicios PaaS - Contratos
            </h2>
            <form>
                <div class="container">
                    <button type="submit" class="btn btn-primary" formaction="servicesPaaSPersonalInicioEditform.php">Inicio</button>
                    <button type="submit" class="btn btn-primary" formaction="servicesPaaSPersonalContratosform.php">Contratos PaaS</button>
                    <button type="submit" class="btn btn-primary" formaction="servicesPaaSPersonalViewform.php">Visualizar</button>
                    <button type="submit" class="btn btn-primary" formaction="servicesPaaSPersonalCreateform.php">Crear</button>
                    <button type="submit" class="btn btn-primary" formaction="servicesPaaSPersonalDeleteform.php">Eliminar</button>
                    <button type="submit" class="btn btn-primary" formaction="servicesPaaSPersonalTestform.php">Test</button>
                </div>
            </form>
        </div>

        <div class="container">
            <?php
            if (isset($_SESSION["success_msg"])) {
                echo "<div class='alert alert-success' role='alert'>{$_SESSION['success_msg']}</div>";
                unset($_SESSION["success_msg"]);
            }
            if (isset($_SESSION["error_msg"])) {
                echo "<div class='alert alert-danger' role='alert'>{$_SESSION['error_msg']}</div>";
                unset($_SESSION["error_msg"]);
            }
            if (isset($_SESSION["warning_msg"])) {
                echo "<div class='alert alert-warning' role='alert'>{$_SESSION['warning_msg']}</div>";
                unset($_SESSION["warning_msg"]);
            }
            ?>
            <form action="servicesPaaSPersonalContratosform.php" method="POST">
                <!-- Tabla para mostrar los datos del contrato -->
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Seleccionar</th>
                            <th>ID Contracte</th>
                            <th>Data Inici</th>
                            <th>Estat</th>
                            <th>Nom Org</th>
                            <th>Email Usuari</th>
                            <th>ID Config Producte</th>
                            <th>Mesos</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $resultadoContracte = $contratos->obtenerContratosPaaS($conn);

                        if ($resultadoContracte->num_rows > 0) {
                            while ($rowContracte = $resultadoContracte->fetch_assoc()) {
                                echo "<tr>
                                    <td>
                                        <input type='radio' name='idContracte' value='{$rowContracte['idContracte']}' required>
                                    </td>
                                    <td>{$rowContracte['idContracte']}</td>
                                    <td>{$rowContracte['dataInici']}</td>
                                    <td>
                                        <select name='estat[{$rowContracte['idContracte']}]' class='form-control'>
                                            <option value='Actiu' " . ($rowContracte['estat'] == 'Actiu' ? 'selected' : '') . ">Actiu</option>
                                            <option value='Finalitzat' " . ($rowContracte['estat'] == 'Finalitzat' ? 'selected' : '') . ">Finalitzat</option>
                                            <option value='Cancel·lat' " . ($rowContracte['estat'] == 'Cancel·lat' ? 'selected' : '') . ">Cancel·lat</option>
                                        </select>
                                    </td>
                                    <td>{$rowContracte['nom']}</td>
                                    <td>{$rowContracte['emailU']}</td>
                                    <td>{$rowContracte['idConfigProducte']}</td>
                                    <td>
                                        <input type='number' name='mesos[{$rowContracte['idContracte']}]' class='form-control' value='{$rowContracte['mesos']}' min='3'>
                                    </td>
                                    </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='8'>No hay contratos disponibles.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
                <button type='submit' name='update' class='btn btn-primary custom-btn'>Actualizar</button>
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