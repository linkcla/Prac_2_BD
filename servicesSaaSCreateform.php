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
                                <a href="servicesSaaSPersonalform.php">SaaS</a>
                            </div>
                            <div class="overlay-content">
                                <a href="servicesPaaSfPersonalform.php">PaaS</a>
                            </div>  
                            <div class="overlay-content">
                                <a href="gestOrgform.php">Gestionar Organitzacións</a>
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
                Servicios SaaS - Crear
            </h2>
            <form>
                <div class="container">
                    <button type="submit" class="btn btn-primary" formaction="servicesSaaSViewform.php">Inicio</button>
                    <button type="submit" class="btn btn-primary" formaction="servicesSaaSPersonalform.php">Contratos SaaS</button>
                    <button type="submit" class="btn btn-primary" formaction="servicesSaaSCreateform.php">Crear</button>
                    <button type="submit" class="btn btn-primary" formaction="servicesSaaSTestform.php">Test</button>
                </div>
            </form>
        </div>
        <div class="container">
            <div class="row d-flex">
                <!-- Columna izquierda: Formulario de selección -->
                <div class="col-md-8">
                    <form action="servicesSaaSCreateBD.php " method="POST" onsubmit="return validateForm()">

                    <!-- NOM Y DESCRPCION -->
                    <fieldset>
                        <div class="form-group">
                                <input name="nombre"  id="nombre" placeholder="  Nombre Producto" type="text" required />
                                <input name="descipcio" id="descipcio" placeholder="  Descripcion Producto" type="text" required />
                        </div>
                    </fieldset>

                    <!-- CMS -->
                    <fieldset>
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
                            <select name="tipo_cms" id="tipo_cms" class="form-control" >
                                <option value="">Selecciona Modulo CMS</option>
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
                            <select name="tipo_cdn" id="tipo_cdn" class="form-control" >
                                <option value="">Selecciona Shared Content Delivery Network (CDN)</option>
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
                            <select name="tipo_ssl" id="tipo_ssl" class="form-control" >
                                <option value="">Selecciona certificado SSL</option>
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
                            <select name="tipo_sgbd" id="tipo_sgbd" class="form-control" >
                                <option value="">Selecciona Sistema de gestión de bases de datos</option>
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
                        <?php
                        $ramOptions = [];
                        $precioRam = '';

                        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                            if (isset($_POST['tipo_gb_ram'])) {
                                $tipoGbRam = $_POST['tipo_gb_ram'];
                            } else {
                                $tipoGbRam = '';
                            }

                            if ($tipoGbRam !== '') {
                                list($tipoRam, $gbRam) = explode('|', $tipoGbRam);
                                $cadena = "SELECT tipus, GB FROM RAM WHERE tipus = '$tipoRam' AND GB = '$gbRam'";
                                $resultado = mysqli_query($conn, $cadena);
                                while ($row = $resultado->fetch_assoc()) {
                                    $ramOptions[] = $row;
                                }

                                $cadena = "SELECT preu FROM RAM WHERE tipus = '$tipoRam' AND GB = '$gbRam'";
                                $resultado = mysqli_query($conn, $cadena);
                                if ($row = $resultado->fetch_assoc()) {
                                    $precioRam = $row['preu'];
                                }
                            }
                        }
                        ?>

                        <div class="form-group">
                            <select name="tipo_gb_ram" id="tipo_gb_ram" class="form-control" >
                                <option value="">Selecciona Tipo y GB de RAM</option>
                                <?php
                                $cadena = "SELECT DISTINCT tipus, GB FROM RAM";
                                $resultado = mysqli_query($conn, $cadena);
                                while ($row = $resultado->fetch_assoc()) {
                                    $value = $row['tipus'] . '|' . $row['GB'];
                                    $selected = '';
                                    if (isset($tipoGbRam) && $value === $tipoGbRam) {
                                        $selected = 'selected';
                                    }
                                    echo "<option value='" . $value . "' $selected>" . $row['tipus'] . " - " . $row['GB'] . " GB</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </fieldset>
                        
                    <!-- DISC DUR -->
                    <fieldset>
                        <?php
                        $discDurOptions = [];
                        $precioDiscDur = '';

                        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                            if (isset($_POST['tipo_gb_disc_dur'])) {
                                $tipoGbDiscDur = $_POST['tipo_gb_disc_dur'];
                            } else {
                                $tipoGbDiscDur = '';
                            }

                            if ($tipoGbDiscDur !== '') {
                                list($tipoDiscDur, $gbDiscDur) = explode('|', $tipoGbDiscDur);
                                $cadena = "SELECT tipus, GB FROM DISC_DUR WHERE tipus = '$tipoDiscDur' AND GB = '$gbDiscDur'";
                                $resultado = mysqli_query($conn, $cadena);
                                while ($row = $resultado->fetch_assoc()) {
                                    $discDurOptions[] = $row;
                                }

                                $cadena = "SELECT preu FROM DISC_DUR WHERE tipus = '$tipoDiscDur' AND GB = '$gbDiscDur'";
                                $resultado = mysqli_query($conn, $cadena);
                                if ($row = $resultado->fetch_assoc()) {
                                    $precioDiscDur = $row['preu'];
                                }
                            }
                        }
                        ?>

                        <div class="form-group">
                            <select name="tipo_gb_disc_dur" id="tipo_gb_disc_dur" class="form-control" >
                                <option value="">Selecciona Tipo y GB de DISC DUR</option>
                                <?php
                                $cadena = "SELECT DISTINCT tipus, GB FROM DISC_DUR";
                                $resultado = mysqli_query($conn, $cadena);
                                while ($row = $resultado->fetch_assoc()) {
                                    $value = $row['tipus'] . '|' . $row['GB'];
                                    $selected = '';
                                    if (isset($tipoGbDiscDur) && $value === $tipoGbDiscDur) {
                                        $selected = 'selected';
                                    }
                                    echo "<option value='" . $value . "' $selected>" . $row['tipus'] . " - " . $row['GB'] . " GB</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </fieldset>

                    <!-- TEST -->
                    <fieldset>
                        <div class="form-group ">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Seleccionar</th>
                                        <th>Test 
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $cadenaTest = "SELECT nom FROM TEST";
                                    $resultadoContracte = mysqli_query($conn, $cadenaTest);
                                    while ($rowContracte = $resultadoContracte->fetch_assoc()) {
                                        $value = $rowContracte['nom'];
                                        echo "<tr>
                                            <td>
                                                <input type='checkbox' name='selectedRowstest[]' value='{$value}'>
                                            </td>
                                            <td>{$rowContracte['nom']}</td>
                                        </tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </fieldset>
                    
                    <!-- DOMINIO -->
                    <fieldset>
                        <div class="form-group1">
                                <label for="dominio">Dominio: </label>
                                <input name="dominio" placeholder="  miejemplo.com" type="text" required />
                        </div>
                    </fieldset>

                    <!-- añadir más componentes -->
                   
                    <div class="container">
                        <button type="submit" class="btn btn-primary" name="crearbut">Crear Producto</button>
                    </div>
                </form>
                <script>
                    function validateForm() {
                        const tipoCms = document.getElementById('tipo_cms').value;
                        const tipoCdn = document.getElementById('tipo_cdn').value;
                        const tipoSsl = document.getElementById('tipo_ssl').value;
                        const tipoSgbd = document.getElementById('tipo_sgbd').value;
                        const tipoGbRam = document.getElementById('tipo_gb_ram').value;
                        const tipoGbDiscDur = document.getElementById('tipo_gb_disc_dur').value;

                        if (tipoCms === '' || tipoCdn === '' || tipoSsl === '' || tipoSgbd === '' || tipoGbRam === '' || tipoGbDiscDur === '') {
                            alert('Por favor, selecciona una opción en todos los menús desplegables.');
                            return false;
                        }
                        return true;
                    }
                </script>
                                

                </div>

                <!-- Columna derecha: Resumen de selección -->
                <div class="col-md-4">
                    <div class="container">
                        <button type="submit" class="btn btn-primary" name="newMod">Nuevos Modulos</button>
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