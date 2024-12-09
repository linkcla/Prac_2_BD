<!-- @Author: Blanca Atienzar Martinez (HTML, CSS y funcionalidad de SaaS) -->

<?php session_start() ;
include "conexion.php";
$conn = Conexion::getConnection();  

if (isset($_SESSION['success_msg'])) {
    echo "<div class='alert alert-success' role='alert'>{$_SESSION['success_msg']}</div>";
    unset($_SESSION['success_msg']);
}
if (isset($_SESSION['error_msg'])) {
    echo "<div class='alert alert-danger' role='alert'>{$_SESSION['error_msg']}</div>";
    unset($_SESSION['error_msg']);
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

    <section class="about_section layout_paddingAbout">
        <div class="container">
            <h2 class="text-uppercase">
                Servicios SaaS - Personal
            </h2>
            <form>
                <div class="container">
                    <button type="submit" class="btn btn-primary" formaction="servicesSaaSContratosform.php">Contratos SaaS</button>
                    
                    <button type="submit" class="btn btn-primary" formaction="servicesSaaSTestform.php">Test</button>
                    <button type="submit" class="btn btn-primary" formaction="servicesSaaSComponentesform.php">Componentes SaaS</button>
                </div>
            </form>
        </div>
        <div class="container">
            <form id="formulari" action=" " method="POST">
                <input type="hidden" name="accio" value="eliminar">
                <!-- Tabla para mostrar los datos de CONTRACTE -->
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Seleccionar</th>
                            <th>ID Configuración</th>
                            <th>Dominio</th>
                            <th>Fecha Creación</th>
                            <th>Modulo CMS</th>
                            <th>CDN</th>
                            <th>SSL</th>
                            <th>SGBD</th>
                            <th>RAM</th>
                            <th>DD</th>
                            <th>Nombre del Test</th>
                            <th>Estado del Test</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $cadenaContracte = "SELECT 
                                SAAS.idConfig, 
                                SAAS.domini, 
                                SAAS.dataCreacio, 
                                SAAS.tipusMCMS, 
                                SAAS.tipusCDN, 
                                SAAS.tipusSSL, 
                                SAAS.tipusSGBD, 
                                CONCAT(SAAS.tipusRam, ' - ', SAAS.GBRam, ' GB') AS ram,
                                CONCAT(SAAS.tipusDD, ' - ', SAAS.GBDD, ' GB') AS disc,
                                GROUP_CONCAT(TEST.nom ORDER BY TEST.nom SEPARATOR ', ') AS testNoms,
                                GROUP_CONCAT(ESTAT.estat ORDER BY TEST.nom SEPARATOR ', ') AS testEstats
                            FROM SAAS
                            LEFT JOIN ESTAT ON SAAS.idConfig = ESTAT.idConfigProducte
                            LEFT JOIN TEST ON ESTAT.nomT = TEST.nom
                            GROUP BY SAAS.idConfig
                            ORDER BY SAAS.idConfig";
                        $resultadoContracte = mysqli_query($conn, $cadenaContracte);

                        while ($rowContracte = $resultadoContracte->fetch_assoc()) {
                            $value = implode('|', [
                                $rowContracte['idConfig'],
                                $rowContracte['domini'],
                                $rowContracte['dataCreacio'],
                                $rowContracte['tipusMCMS'],
                                $rowContracte['tipusCDN'],
                                $rowContracte['tipusSSL'],
                                $rowContracte['tipusSGBD'],
                                $rowContracte['ram'],
                                $rowContracte['disc'],
                                $rowContracte['testNoms'],
                                $rowContracte['testEstats']
                            ]);

                            echo "<tr>
                                <td>
                                    <input type='radio' name='selectedRow' value='{$value}'>
                                </td>
                                <td>{$rowContracte['idConfig']}</td>
                                <td>{$rowContracte['domini']}</td>
                                <td>{$rowContracte['dataCreacio']}</td>
                                <td>{$rowContracte['tipusMCMS']}</td>
                                <td>{$rowContracte['tipusCDN']}</td>
                                <td>{$rowContracte['tipusSSL']}</td>
                                <td>{$rowContracte['tipusSGBD']}</td>
                                <td>{$rowContracte['ram']}</td>
                                <td>{$rowContracte['disc']}</td>
                                <td>{$rowContracte['testNoms']}</td>
                                <td>{$rowContracte['testEstats']}</td>
                            </tr>";
                        }
                        ?>
                    </tbody>
                </table>
                <div class="container">
                    <div class="row mb-3">
                        <div class="col">
                            <button type="button" class="btn btn-primary" id="botEditar" name="actionEd" value="editar">Editar Seleccionado</button>
                            <button type="button" class="btn btn-primary" id="botBorrar" name="actionDe" value="delete">Eliminar Seleccionado</button>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <button type="submit" class="btn btn-primary" formaction="servicesSaaSCreateform.php">Crear Servicio</button>
                        </div>
                    </div>
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
                            form.action = 'servicesSaaSEditform.php';
                            form.submit();
                        } else {
                            alert('Por favor, seleccione una organización para editar.');
                        }
                    });

                    botBorrar.addEventListener('click', function() {
                        if (isRadioSelected()) {
                            form.action = './src/vista/productoSaaSVista.php';
                            form.submit();
                        } else {
                            alert('Por favor, seleccione una organización para borrar.');
                        }
                    });
                </script>
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

