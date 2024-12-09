<!-- @Author: Pau Toni Bibiloni Martínez -->

<?php 
session_start();
include "conexion.php";
$conn = Conexion::getConnection();
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

    <section class="about_section layout_paddingAbout">
        <div class="container">
            <h2 class="text-uppercase">
                Servicios PaaS - Crear Test
            </h2>
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
            <form method="POST" action="servicesPaaSPersonalTestBD.php">
                <div class="form-group">
                    <label for="nombre_test">Nombre del Test</label>
                    <input type="text" class="form-control" id="nombre_test" name="nombre_test" required>
                </div>
                <div class="form-group">
                    <label for="descripcion_test">Descripción del Test</label>
                    <textarea class="form-control" id="descripcion_test" name="descripcion_test" required></textarea>
                </div>
                <div class="form-group">
                    <label for="idConfigProducte">Producto PaaS</label>
                    <select class="form-control" id="idConfigProducte" name="idConfigProducte" required>
                        <?php
                        $query = "SELECT P.idConfig, P.nom FROM PRODUCTE P JOIN PAAS S ON P.idConfig = S.idConfig";
                        $result = mysqli_query($conn, $query);
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<option value='{$row['idConfig']}'>ID: {$row['idConfig']} - Nombre: {$row['nom']}</option>";
                        }
                        ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary" name="crear_test">Crear Test</button>
            </form>
        </div>

        <div class="container mt-5">
            <h2 class="text-uppercase">
                Tests Existentes
            </h2>
            <form method="POST" action="servicesPaaSPersonalTestBD.php">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Seleccionar</th>
                            <th>Nombre del Test</th>
                            <th>ID Producto PaaS</th>
                            <th>Fecha de Creación</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = "SELECT T.nom, T.dataCreacio, E.estat, E.idConfigProducte FROM TEST T LEFT JOIN ESTAT E ON T.nom = E.nomT";
                        $result = mysqli_query($conn, $query);
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td><input type='radio' name='nombre_test_seleccionado' value='{$row['nom']}' required></td>";
                            echo "<td>{$row['nom']}</td>";
                            echo "<td>{$row['idConfigProducte']}</td>";
                            echo "<td>{$row['dataCreacio']}</td>";
                            echo "<td>{$row['estat']}</td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
                <div class="form-group">
                    <label for="nuevo_estado">Nuevo Estado</label>
                    <select class="form-control" id="nuevo_estado" name="nuevo_estado" required>
                        <option value="Pendent">Pendent</option>
                        <option value="Aprovat">Aprovat</option>
                        <option value="Fallat">Fallat</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary" name="actualizar_estado">Actualizar Estado</button>
                <button type="submit" class="btn btn-danger" name="eliminar_test">Eliminar Test</button>
            </form>
        </div>
    </section>

    <!-- end about section -->


    <!-- footer section -->
    <section class="container-fluid footer_section">
        <p>&copy; 2024 (UIB - EPS). Design by MPHB</p>
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
