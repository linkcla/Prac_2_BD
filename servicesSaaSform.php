<!-- @Author: Blanca Atienzar Martinez -->
<?php session_start() ; ?>

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
                    Nuestros Servicios SaaS
                </h2>
            </div>

            <progress id="file" value="32" max="100"> 32% </progress>

            <h4>Elija un sistema de gestion de base de datos preinstalado</h4>
            <div class="container">
                <div class="selection-container">
                    <?php 
                    require_once 'conexion.php';

                    // Conectar a la base de datos
                    $conn = $conexio->getConnection();

                    // Consulta para obtener los sistema de gestion de base de datos disponibles
                    $query = "SELECT tipus FROM sist_gestio_bd";
                    $result = mysqli_query($conn, $query);

                    // Generar las opciones del formulario basadas en los resultados de la consulta
                    if (!$result || mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo '<label class="selection-box">';
                            echo '<input type="radio" name="sist_gestio_bd" value="' . strtolower($row['tipus']) . '">';
                            echo '<span>' . $row['tipus'] . '</span>';
                            echo '</label>';
                        }
                    } else {
                        echo '<p>No hay sistemaa de gestion de basea de datos disponibles.</p>';
                    }

                    // Cerrar la conexión
                    ?>
                    
                </div>
            </div>

            <h4>Elija una memoria RAM preinstalado</h4>
            <div class="container">
                <div class="selection-container">
                    <?php 
                    require_once 'conexion.php';

                    // Conectar a la base de datos
                    $conn = $conexio->getConnection();

                    // Consulta para obtener los sistema de gestion de base de datos disponibles
                    $query = "SELECT tipus, GB, preu FROM ram";
                    $result = mysqli_query($conn, $query);

                    // Generar las opciones del formulario basadas en los resultados de la consulta
                    if (!$result || mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo '<label class="selection-box">';
                            echo '<input type="radio" name="ram" value="' . strtolower($row['tipus']) . '">';
                            echo '<span>' . $row['tipus'] . ' - ' . $row['GB'] .'GB RAM - ' . $row['preu'] . '€ ' . '</span>';
                            echo '</label>';
                        }
                    } else {
                        echo '<p>No hay memorias RAM disponibles.</p>';
                    }

                    // Cerrar la conexión
                    ?>
                    
                </div>
            </div>

            <h4>Elija un disco duro preinstalado</h4>
            <div class="container">
                <div class="selection-container">
                    <?php 
                    require_once 'conexion.php';

                    // Conectar a la base de datos
                    $conn = $conexio->getConnection();

                    // Consulta para obtener los sistema de gestion de base de datos disponibles
                    $query = "SELECT tipus, GB, preu FROM disc_dur";
                    $result = mysqli_query($conn, $query);

                    // Generar las opciones del formulario basadas en los resultados de la consulta
                    if (!$result || mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo '<label class="selection-box">';
                            echo '<input type="radio" name="disc_dur" value="' . strtolower($row['tipus']) . '">';
                            echo '<span>' . $row['tipus'] . ' - ' . $row['GB'] .'GB RAM - ' . $row['preu'] . '€ ' . '</span>';
                            echo '</label>';
                        }
                    } else {
                        echo '<p>No hay discos duros disponibles.</p>';
                    }

                    // Cerrar la conexión
                    ?>
                    
                </div>
            </div>

            <h4>Elija un módulo CMS preinstalado</h4>
            <div class="container">
                <div class="selection-container">
                    <?php 
                    require_once 'conexion.php';

                    // Conectar a la base de datos
                    $conn = $conexio->getConnection();

                    // Consulta para obtener los módulos CMS disponibles
                    $query = "SELECT tipus FROM modul_cms";
                    $result = mysqli_query($conn, $query);

                    // Generar las opciones del formulario basadas en los resultados de la consulta
                    if (!$result || mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo '<label class="selection-box">';
                            echo '<input type="radio" name="cdm" value="' . strtolower($row['tipus']) . '">';
                            echo '<span>' . $row['tipus'] . '</span>';
                            echo '</label>';
                        }
                    } else {
                        echo '<p>No hay módulos CMS disponibles.</p>';
                    }

                    // Cerrar la conexión
                    ?>
                    
                </div>
            </div>

            <h4>Shared Content Delivery Network (CDN)</h4>
            <div class="container">
                <div class="selection-container1">
                    <?php 
                    require_once 'conexion.php';

                    

                    // Consulta para obtener los módulos CMS disponibles
                    $query = "SELECT tipus, preu FROM cdn";
                    $result = mysqli_query($conn, $query);

                    // Generar las opciones del formulario basadas en los resultados de la consulta
                    if (!$result || mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo '<label class="selection-box">';
                            echo '<input type="radio" name="cms" value="' . strtolower($row['tipus']) . '">';
                            echo '<span>' . $row['tipus'] . ' - ' . $row['preu'] . '€ ' . '</span>';
                            echo '</label>';
                        }
                    } else {
                        echo '<p>No hay módulos CDN disponibles.</p>';
                    }

                    ?>
                    
                </div>
            </div>

            <h4>Proteja su sitio web con nuestros certificados SSL</h4>
            <div class="container">
                <div class="selection-container1">
                    <?php 
                    require_once 'conexion.php';

                    // Consulta para obtener los módulos CMS disponibles
                    $query = "SELECT tipus, preu FROM c_ssl";
                    $result = mysqli_query($conn, $query);

                    // Generar las opciones del formulario basadas en los resultados de la consulta
                    if (!$result || mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo '<label class="selection-box">';
                            echo '<input type="radio" name="c_ssl" value="' . strtolower($row['tipus']) . '">';
                            echo '<span>' . $row['tipus'] . ' - ' . $row['preu'] . '€ ' . '</span>';
                            echo '</label>';
                        }
                    } else {
                        echo '<p>No hay certificados SSL disponibles.</p>';
                    }

                    // Cerrar la conexión
                    mysqli_close($conn);
                    ?>
                    
                </div>
            </div>
            <h4>1 dominio incluido con su plan de hosting</h4>
            <div>
                <input name="dominio" placeholder="  miejemplo.com" />
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
