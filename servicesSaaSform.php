<!-- @Author: Blanca Atienzar Martinez (HTML y CSS) -->

<?php session_start() ;
include "conexion.php";
$conn = Conexion::getConnection();      

// Suponiendo que el email del usuario está almacenado en la sesión
$emailUsuario = $_SESSION['emailUsuario'];

// Obtener el grupo del usuario
$query = $conn->prepare("SELECT nomG FROM US_PERTANY_GRU WHERE emailU = ?");
$query->bind_param("s", $emailUsuario);
$query->execute();
$result = $query->get_result();
$usuario = $result->fetch_assoc();
$grupo = $usuario['nomG'];
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
                Nuestros Servicios SaaS
            </h2>
        </div>
                        
    
        <div class="container">
            <div class="row d-flex">
                <!-- Columna izquierda: Formulario de selección -->
                <div class="col-md-8">
                    <form action="servicesSaaSform.php" method="POST">
                    <!-- CMS -->
                    <fieldset>
                        <legend>CMS</legend>
                        <?php
                        $cmsOptions = [];

                        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                            if (isset($_POST['tipo_cms'])) {
                                $tipoCMS = $_POST['tipo_cms'];
                            } else {
                                $tipoCMS = '';
                            }

                            if ($tipoCMS !== '') {
                                $cadena = "SELECT tipus FROM MODUL_CMS WHERE tipus = '$tipoCMS'";
                                $resultado = mysqli_query($conn, $cadena);
                                while ($row = $resultado->fetch_assoc()) {
                                    $cmsOptions[] = $row;
                                }
                            }
                        }
                        ?>

                        <div class="form-group">
                            <label for="tipo_cms">Selecciona Modulo CMS</label>
                            <select name="tipo_cms" id="tipo_cms" class="form-control" onchange="this.form.submit()">
                                <option value="">Selecciona Tipo</option>
                                <?php
                                $cadena = "SELECT DISTINCT tipus FROM MODUL_CMS";
                                $resultado = mysqli_query($conn, $cadena);
                                while ($row = $resultado->fetch_assoc()) {
                                    $selected = '';
                                    if (isset($tipoCMS)) {
                                        if ($row['tipus'] === $tipoCMS) {
                                            $selected = 'selected';
                                        }
                                    } else {
                                        if ($row['tipus'] === '') {
                                            $selected = 'selected';
                                        }
                                    }

                                    echo "<option value='" . $row['tipus'] . "' $selected>" . $row['tipus'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>

                    </fieldset>

                    <!-- CDN -->
                    <fieldset>
                        <legend>CDN</legend>
                        <?php
                        $cdnOptions = [];
                        $precioCDN = '';

                        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                            if (isset($_POST['tipo_cdn'])) {
                                $tipoCDN = $_POST['tipo_cdn'];
                            } else {
                                $tipoCDN = '';
                            }

                            if ($tipoCDN !== '') {
                                $cadena = "SELECT tipus, preu FROM CDN WHERE tipus = '$tipoCDN'";
                                $resultado = mysqli_query($conn, $cadena);
                                while ($row = $resultado->fetch_assoc()) {
                                    $cdnOptions[] = $row;
                                    $precioCDN = $row['preu'];
                                }
                            }
                        }
                        ?>

                        <div class="form-group">
                            <label for="tipo_cdn">Selecciona Shared Content Delivery Network (CDN)</label>
                            <select name="tipo_cdn" id="tipo_cdn" class="form-control" onchange="this.form.submit()">
                                <option value="">Selecciona Tipo</option>
                                <?php
                                $cadena = "SELECT DISTINCT tipus FROM CDN";
                                $resultado = mysqli_query($conn, $cadena);
                                while ($row = $resultado->fetch_assoc()) {
                                    $selected = '';
                                    if (isset($tipoCDN)) {
                                        if ($row['tipus'] === $tipoCDN) {
                                            $selected = 'selected';
                                        }
                                    } else {
                                        if ($row['tipus'] === '') {
                                            $selected = 'selected';
                                        }
                                    }

                                    echo "<option value='" . $row['tipus'] . "' $selected>" . $row['tipus'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </fieldset>

                    <!-- SSL -->
                    <fieldset>
                        <legend>SSL</legend>
                        <?php
                        $sslOptions = [];
                        $precioSSL = '';

                        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                            if (isset($_POST['tipo_ssl'])) {
                                $tipoSSL = $_POST['tipo_ssl'];
                            } else {
                                $tipoSSL = '';
                            }

                            if ($tipoSSL !== '') {
                                $cadena = "SELECT tipus, preu FROM C_SSL WHERE tipus = '$tipoSSL'";
                                $resultado = mysqli_query($conn, $cadena);
                                while ($row = $resultado->fetch_assoc()) {
                                    $cdnOptions[] = $row;
                                    $precioSSL = $row['preu'];
                                }
                            }
                        }
                        ?>

                        <div class="form-group">
                            <label for="tipo_ssl">Selecciona certificado SSL</label>
                            <select name="tipo_ssl" id="tipo_ssl" class="form-control" onchange="this.form.submit()">
                                <option value="">Selecciona Tipo</option>
                                <?php
                                $cadena = "SELECT DISTINCT tipus FROM C_SSL";
                                $resultado = mysqli_query($conn, $cadena);
                                while ($row = $resultado->fetch_assoc()) {
                                    $selected = '';
                                    if (isset($tipoSSL)) {
                                        if ($row['tipus'] === $tipoSSL) {
                                            $selected = 'selected';
                                        }
                                    } else {
                                        if ($row['tipus'] === '') {
                                            $selected = 'selected';
                                        }
                                    }

                                    echo "<option value='" . $row['tipus'] . "' $selected>" . $row['tipus'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </fieldset>

                    <!-- SGBD -->
                    <fieldset>
                        <legend>SGBD</legend>
                        <?php
                        $sgbdOptions = [];

                        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                            if (isset($_POST['tipo_sgbd'])) {
                                $tipoSGBD = $_POST['tipo_sgbd'];
                            } else {
                                $tipoSGBD = '';
                            }

                            if ($tipoSGBD !== '') {
                                $cadena = "SELECT tipus FROM SIST_GESTIO_BD WHERE tipus = '$tipoSGBD'";
                                $resultado = mysqli_query($conn, $cadena);
                                while ($row = $resultado->fetch_assoc()) {
                                    $sgbdOptions[] = $row;
                                }
                            }
                        }
                        ?>

                        <div class="form-group">
                            <label for="tipo_sgbd">Selecciona Sistema de gestión de bases de datos</label>
                            <select name="tipo_sgbd" id="tipo_sgbd" class="form-control" onchange="this.form.submit()">
                                <option value="">Selecciona Tipo</option>
                                <?php
                                $cadena = "SELECT DISTINCT tipus FROM SIST_GESTIO_BD";
                                $resultado = mysqli_query($conn, $cadena);
                                while ($row = $resultado->fetch_assoc()) {
                                    $selected = '';
                                    if (isset($tipoSGBD)) {
                                        if ($row['tipus'] === $tipoSGBD) {
                                            $selected = 'selected';
                                        }
                                    } else {
                                        if ($row['tipus'] === '') {
                                            $selected = 'selected';
                                        }
                                    }

                                    echo "<option value='" . $row['tipus'] . "' $selected>" . $row['tipus'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>

                    </fieldset>
                    
                    <!-- RAM -->
                    <fieldset>
                        <legend>RAM</legend>
                        <?php
                        $ramOptions = [];
                        $precioRam = '';

                        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                            if (isset($_POST['tipo_ram'])) {
                                $tipoRam = $_POST['tipo_ram'];
                            } else {
                                $tipoRam = '';
                            }

                            if (isset($_POST['gb_ram'])) {
                                $gbRam = $_POST['gb_ram'];
                            } else {
                                $gbRam = '';
                            }

                            if ($tipoRam !== '') {
                                $cadena = "SELECT tipus, GB FROM RAM WHERE tipus = '$tipoRam'";
                                $resultado = mysqli_query($conn, $cadena);
                                while ($row = $resultado->fetch_assoc()) {
                                    $ramOptions[] = $row;
                                }
                            }

                            if ($tipoRam !== '' && $gbRam !== '') {
                                $cadena = "SELECT preu FROM RAM WHERE tipus = '$tipoRam' AND GB = '$gbRam'";
                                $resultado = mysqli_query($conn, $cadena);
                                if ($row = $resultado->fetch_assoc()) {
                                    $precioRam = $row['preu'];
                                }
                            }
                        }
                        ?>

                        <div class="form-group">
                            <label for="tipo_ram">Selecciona Tipo de RAM</label>
                            <select name="tipo_ram" id="tipo_ram" class="form-control" onchange="this.form.submit()">
                                <option value="">Selecciona Tipo</option>
                                <?php
                                $cadena = "SELECT DISTINCT tipus FROM RAM";
                                $resultado = mysqli_query($conn, $cadena);
                                while ($row = $resultado->fetch_assoc()) {
                                    $selected = '';
                                    if (isset($tipoRam)) {
                                        if ($row['tipus'] === $tipoRam) {
                                            $selected = 'selected';
                                        }
                                    } else {
                                        if ($row['tipus'] === '') {
                                            $selected = 'selected';
                                        }
                                    }

                                    echo "<option value='" . $row['tipus'] . "' $selected>" . $row['tipus'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="gb_ram">Selecciona GB de RAM</label>
                            <select name="gb_ram" id="gb_ram" class="form-control" onchange="this.form.submit()">
                                <option value="">Selecciona GB</option>
                                <?php
                                while ($option = current($ramOptions)) {
                                    $selected = '';
                                    if (isset($gbRam)) {
                                        if ($option['GB'] == $gbRam) {
                                            $selected = 'selected';
                                        }
                                    } else {
                                        if ($option['GB'] === '') {
                                            $selected = 'selected';
                                        }
                                    }

                                    echo "<option value='" . $option['GB'] . "' $selected>" . $option['GB'] . " GB</option>";
                                    next($ramOptions);
                                }
                                ?>
                            </select>
                        </div> 
                    </fieldset>
                        
                    <!-- DISC DUR -->
                    <fieldset>
                        <legend>DISC DUR</legend>
                        <?php
                        $discDurOptions = [];
                        $precioDiscDur = '';

                        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                            if (isset($_POST['tipo_disc_dur'])) {
                                $tipoDiscDur = $_POST['tipo_disc_dur'];
                            } else {
                                $tipoDiscDur = '';
                            }

                            if (isset($_POST['gb_disc_dur'])) {
                                $gbDiscDur = $_POST['gb_disc_dur'];
                            } else {
                                $gbDiscDur = '';
                            }

                            if ($tipoDiscDur !== '') {
                                $cadena = "SELECT tipus, GB FROM DISC_DUR WHERE tipus = '$tipoDiscDur'";
                                $resultado = mysqli_query($conn, $cadena);
                                while ($row = $resultado->fetch_assoc()) {
                                    $discDurOptions[] = $row;
                                }
                            }

                            if ($tipoDiscDur !== '' && $gbDiscDur !== '') {
                                $cadena = "SELECT preu FROM DISC_DUR WHERE tipus = '$tipoDiscDur' AND GB = '$gbDiscDur'";
                                $resultado = mysqli_query($conn, $cadena);
                                if ($row = $resultado->fetch_assoc()) {
                                    $precioDiscDur = $row['preu'];
                                }
                            }
                        }
                        ?>

                        <div class="form-group">
                            <label for="tipo_disc_dur">Selecciona Tipo de DISC DUR</label>
                            <select name="tipo_disc_dur" id="tipo_disc_dur" class="form-control" onchange="this.form.submit()">
                                <option value="">Selecciona Tipo</option>
                                <?php
                                $cadena = "SELECT DISTINCT tipus FROM DISC_DUR";
                                $resultado = mysqli_query($conn, $cadena);
                                while ($row = $resultado->fetch_assoc()) {
                                    $selected = '';
                                    if (isset($tipoDiscDur)) {
                                        if ($row['tipus'] === $tipoDiscDur) {
                                            $selected = 'selected';
                                        }
                                    } else {
                                        if ($row['tipus'] === '') {
                                            $selected = 'selected';
                                        }
                                    }

                                    echo "<option value='" . $row['tipus'] . "' $selected>" . $row['tipus'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="gb_disc_dur">Selecciona GB de DISC DUR</label>
                            <select name="gb_disc_dur" id="gb_disc_dur" class="form-control" onchange="this.form.submit()">
                                <option value="">Selecciona GB</option>
                                <?php
                                while ($option = current($discDurOptions)) {
                                    $selected = '';
                                    if (isset($gbDiscDur)) {
                                        if ($option['GB'] == $gbDiscDur) {
                                            $selected = 'selected';
                                        }
                                    } else {
                                        if ($option['GB'] === '') {
                                            $selected = 'selected';
                                        }
                                    }

                                    echo "<option value='" . $option['GB'] . "' $selected>" . $option['GB'] . " GB</option>";
                                    next($discDurOptions);
                                }
                                ?>
                            </select>
                        </div>
                    </fieldset>
                    
                    <!-- DOMINIO -->
                    <fieldset>
                        <legend>DOMINIO</legend>

                        <div class="form-group">

                        <form action="check_domain.php" method="POST">
                            <input name="dominio" placeholder="  miejemplo.com" type="text" required />
                            <button type="submit">Comprobar Dominio</button>
                        </form>
                                <?php
                                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                                    // Recibir el valor del dominio
                                    $dominio = mysqli_real_escape_string($conn, $_POST['dominio']); 

                                    // Verificar si el dominio ya existe
                                    $query = "SELECT domini FROM SAAS WHERE domini = '$dominio'";
                                    $resultado = mysqli_query($conn, $query);

                                    if (mysqli_num_rows($resultado) > 0) {
                                        // El dominio ya existe
                                        echo "Error: El dominio '$dominio' ya está en uso. Por favor, elige otro.";
                                    } 
                                }
                                ?>
                        </div>

                    </fieldset>

                    <!-- añadir más componentes -->

                    <div class="container">
                        <button type="submit" class="btn btn-primary" >Montar</button>
                    </div>
                </form>
                </div>

                <!-- Columna derecha: Resumen de selección -->
                <div class="col-md-4">
                    <div class="sticky-top">
                        <h4 class="text-center">Resumen de selección</h4>
                        <ul class="list-group">
                            <li class="list-group-item">
                                CMS: <?php echo isset($tipoCMS) ? $tipoCMS : "No seleccionado"; ?>
                            </li>
                            <li class="list-group-item">
                                CDN: <?php echo isset($tipoCDN) ? $tipoCDN : "No seleccionado"; ?>
                                <span class="float-right"><?php echo $precioCDN ? $precioCDN . "€": ""; ?></span>
                            </li>
                            <li class="list-group-item">
                                SSL: <?php echo isset($tipoSSL) ? $tipoSSL : "No seleccionado"; ?>
                                <span class="float-right"><?php echo $precioSSL ? $precioSSL . "€" : ""; ?></span>
                            </li>
                            <li class="list-group-item">
                                SGBD: <?php echo isset($tipoSGBD) ? $tipoSGBD : "No seleccionado"; ?>
                            </li>
                            <li class="list-group-item">
                                RAM: <?php echo isset($tipoRam) ? $tipoRam . " - " . $gbRam . " GB" : "No seleccionado"; ?>
                                <span class="float-right"><?php echo $precioRam ? $precioRam . "€": ""; ?></span>
                            </li>
                            <li class="list-group-item">
                                DISC DUR: <?php echo isset($tipoDiscDur) ? $tipoDiscDur . " - " . $gbDiscDur . " GB" : "No seleccionado"; ?>
                                <span class="float-right"><?php echo $precioDiscDur ? $precioDiscDur . "€" : ""; ?></span>
                            </li>
                            <li class="list-group-item">
                                DOMINIO: <?php echo isset($dominio) ? $dominio : "No seleccionado"; ?>
                            </li>
                            <h5 class="text-center mt-3">
                                Precio Total:
                                <?php
                                $precioCDN = isset($precioCDN) ? floatval($precioCDN) : 0;
                                $precioSSL = isset($precioSSL) ? floatval($precioSSL) : 0;
                                $precioRam = isset($precioRam) ? floatval($precioRam) : 0;
                                $precioDiscDur = isset($precioDiscDur) ? floatval($precioDiscDur) : 0;

                                $precioTotal = $precioCDN + $precioSSL + $precioRam + $precioDiscDur;
                                echo $precioTotal . "€";
                                ?>
                            </h5>
                        </ul>
                    </div>
                </div>
            </div>
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