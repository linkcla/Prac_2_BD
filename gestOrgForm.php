<!-- @Author: Blanca Atienzar Martinez (HTML y CSS) -->

<?php session_start() ;
require_once "conexion.php";
$conn = Conexion::getConnection();   
if (isset($_SESSION['success_msg'])) {
    echo "<div class='alert alert-success' role='alert'>{$_SESSION['success_msg']}</div>";
    unset($_SESSION['success_msg']);
}
if (isset($_SESSION['error_msg'])) {
    echo "<div class='alert alert-danger' role='alert'>{$_SESSION['error_msg']}</div>";
    unset($_SESSION['error_msg']);
}
if (isset($_SESSION['noMod'])) {
    echo "<div class='alert alert-warning' role='alert'>{$_SESSION['noMod']}</div>";
    unset($_SESSION['noMod']);
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

    <section class="about_section layout_paddingAbout">
        <div class="container">
            <h2 class="text-uppercase">
                Gestionar Organitzacións
            </h2>
            <form>
                <div class="container">
                <div class="container">
                    <button type="submit" class="btn btn-primary" formaction="servicesSaaSViewform.php">Administrar usuarios</button>
                    <button type="submit" class="btn btn-primary" formaction="crearOrgForm.php">Crear</button>
                </div>
                </div>
            </form>
        </div>
    </section>

    <section>
        <form id="formulari" action="" method="post">
            <div class="container">
                <!-- Tabla para mostrar los datos de CONTRACTE -->
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Selecionar</th>
                            <th>Nom</th>
                            <th>Adreça</th>
                            <th>Telèfon</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $cadenaOrg = "SELECT * FROM organitzacio";
                        
                        $resultadoOrg = mysqli_query($conn, $cadenaOrg);
                        
                        if (!$resultadoOrg) {
                            die("Error al obtener datos de las organizaciones: " . mysqli_error($conn));
                        }
                        
                        while ($rowOrg = $resultadoOrg->fetch_assoc()) {
                            $value = "{$rowOrg['nom']}|{$rowOrg['adreca']}|{$rowOrg['telefon']}";
                            echo "<tr>
                                <td>
                                    <input type='radio' name='selectedRow' value='{$value}'>
                                </td>
                                <td>{$rowOrg['nom']}</td>
                                <td>{$rowOrg['adreca']}</td>
                                <td>{$rowOrg['telefon']}</td>
                            </tr>";
                        }
                        ?>
                    </tbody>
                </table>
                <button type="button" class="btn btn-primary mt-3" id="botEditar" name="action" value="edit">Editar seleccionado</button>
                <button type="button" class="btn btn-primary mt-3" id="botBorrar" name="action" value="delete">Borrar seleccionado</button>
            </div>
        </form>

        <script>
            const form = document.getElementById('formulari');
            const botEditar = document.getElementById('botEditar');
            const botBorrar = document.getElementById('botBorrar');

            // Función para verificar si se seleccionó un radio
            function isRadioSelected() {
                const radios = document.querySelectorAll('input[name="selectedRow"]');
                for (let i = 0; i < radios.length; i++) {
                    if (radios[i].checked) {
                        return true;  // Si al menos uno está seleccionado
                    }
                }
                return false;  // Ningún radio está seleccionado
            }

            // Cambiar la acción del formulario dependiendo del botón clicado
            botEditar.addEventListener('click', function() {
                if (isRadioSelected()) {
                    form.action = 'editarOrgForm.php';
                    form.submit();
                } else {
                    alert('Por favor, seleccione una organización para editar.');
                }
            });

            botBorrar.addEventListener('click', function() {
                if (isRadioSelected()) {
                    form.action = 'eliminarOrg.php';
                    form.submit();
                } else {
                    alert('Por favor, seleccione una organización para borrar.');
                }
            });
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