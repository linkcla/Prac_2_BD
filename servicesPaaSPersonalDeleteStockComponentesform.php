<!-- @Author: Pau Toni Bibiloni Martínez -->

<?php
session_start();
include "conexion.php";
include_once "PaaSFuncionalidades.php"; 

$conn = Conexion::getConnection();
$paasFuncionalidades = new PaaSFuncionalidades($conn);

// Manejar la solicitud POST para eliminar componentes de stock
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete_components') {
    $selectedRam = isset($_POST['selectedRam']) ? $_POST['selectedRam'] : [];
    $selectedDiscDur = isset($_POST['selectedDiscDur']) ? $_POST['selectedDiscDur'] : [];
    $selectedCpu = isset($_POST['selectedCpu']) ? $_POST['selectedCpu'] : [];
    $selectedSo = isset($_POST['selectedSo']) ? $_POST['selectedSo'] : [];

    $paasFuncionalidades->deleteStockComponentes($selectedRam, $selectedDiscDur, $selectedCpu, $selectedSo);
    header("Location: servicesPaaSPersonalDeleteStockComponentesform.php"); // Redirigir después de procesar
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

    <style>
    .bg-grey {
        background-color: #f0f0f0;
    }
</style>
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
                                <a href="servicesPaaSPersonalInicioEditform.php">PaaS</a>
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
                Servicios PaaS - Eliminar Componentes
            </h2>
            <?php
            if (isset($_SESSION["success_msg"])) {
                echo "<div class='alert alert-success' role='alert'>{$_SESSION["success_msg"]}</div>";
                unset($_SESSION["success_msg"]);
            }
            if (isset($_SESSION["error_msg"])) {
                echo "<div class='alert alert-danger' role='alert'>{$_SESSION["error_msg"]}</div>";
                unset($_SESSION["error_msg"]);
            }
            if (isset($_SESSION["warning_msg"])) {
                echo "<div class='alert alert-warning' role='alert'>{$_SESSION["warning_msg"]}</div>";
                unset($_SESSION["warning_msg"]);
            }
            ?>
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
            <form action="servicesPaaSPersonalDeleteStockComponentesform.php" method="POST">
                <!-- Tabla para mostrar los datos de la RAM -->
                <h3>RAM</h3>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Seleccionar</th>
                            <th>Tipo</th>
                            <th>GB</th>
                            <th>Precio</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $ramQuery = "SELECT * FROM RAM WHERE (tipus, GB) NOT IN (SELECT tipusRAM, GBRam FROM PAAS)";
                        $ramResult = mysqli_query($conn, $ramQuery);
                        if (mysqli_num_rows($ramResult) > 0) {
                            while ($row = mysqli_fetch_assoc($ramResult)) {
                                echo "<tr>";
                                echo "<td><input type='checkbox' name='selectedRam[]' value='{$row['tipus']},{$row['GB']}'></td>";
                                echo "<td>{$row['tipus']}</td>";
                                echo "<td>{$row['GB']}</td>";
                                echo "<td>{$row['preu']}</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4'>No hay componentes RAM disponibles</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>

                <!-- Tabla para mostrar los datos del Disco Duro -->
                <h3>Disco Duro</h3>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Seleccionar</th>
                            <th>Tipo</th>
                            <th>GB</th>
                            <th>Precio</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $discDurQuery = "SELECT * FROM DISC_DUR WHERE (tipus, GB) NOT IN (SELECT tipusDD, GBDD FROM PAAS)";
                        $discDurResult = mysqli_query($conn, $discDurQuery);
                        if (mysqli_num_rows($discDurResult) > 0) {
                            while ($row = mysqli_fetch_assoc($discDurResult)) {
                                echo "<tr>";
                                echo "<td><input type='checkbox' name='selectedDiscDur[]' value='{$row['tipus']},{$row['GB']}'></td>";
                                echo "<td>{$row['tipus']}</td>";
                                echo "<td>{$row['GB']}</td>";
                                echo "<td>{$row['preu']}</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4'>No hay componentes Disco Duro disponibles</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>

                <!-- Tabla para mostrar los datos de la CPU -->
                <h3>CPU</h3>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Seleccionar</th>
                            <th>Modelo</th>
                            <th>Núcleos</th>
                            <th>Precio</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $cpuQuery = "SELECT * FROM CPU WHERE (model, nNuclis) NOT IN (SELECT modelCPU, nNuclis FROM PAAS)";
                        $cpuResult = mysqli_query($conn, $cpuQuery);
                        if (mysqli_num_rows($cpuResult) > 0) {
                            while ($row = mysqli_fetch_assoc($cpuResult)) {
                                echo "<tr>";
                                echo "<td><input type='checkbox' name='selectedCpu[]' value='{$row['model']},{$row['nNuclis']}'></td>";
                                echo "<td>{$row['model']}</td>";
                                echo "<td>{$row['nNuclis']}</td>";
                                echo "<td>{$row['preu']}</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4'>No hay componentes CPU disponibles</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>

                <!-- Tabla para mostrar los datos del SO -->
                <h3>Sistema Operativo</h3>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Seleccionar</th>
                            <th>Nombre</th>
                            <th>Precio</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $soQuery = "SELECT * FROM SO WHERE nom NOT IN (SELECT nomSO FROM PAAS)";
                        $soResult = mysqli_query($conn, $soQuery);
                        if (mysqli_num_rows($soResult) > 0) {
                            while ($row = mysqli_fetch_assoc($soResult)) {
                                echo "<tr>";
                                echo "<td><input type='checkbox' name='selectedSo[]' value='{$row['nom']}'></td>";
                                echo "<td>{$row['nom']}</td>";
                                echo "<td>{$row['preu']}</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='3'>No hay componentes SO disponibles</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>

                <button type="submit" class="btn btn-danger mt-3" name="action" value="delete_components">Eliminar Seleccionados</button>
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
