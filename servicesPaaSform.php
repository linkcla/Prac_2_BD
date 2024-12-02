<!-- @Author: Blanca Atienzar Martinez (HTML y CSS) -->
<!-- @Author: Pau Toni Bibiloni Martínez (PHP y funcionalidad de PaaS) -->

<?php session_start() ;
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
                Nuestros Servicios PaaS
            </h2>
        </div>
                        
    
        <div class="container">
            <div class="row d-flex">
                <!-- Columna izquierda: Formulario de selección -->
                <div class="col-md-8">
                    <form action="servicesPaaSform.php" method="POST">
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

                    <fieldset>
                        <legend>CPU</legend>
                        <?php
                        $cpuOptions = [];
                        $precioCpu = '';

                        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                            if (isset($_POST['model_cpu'])) {
                                $modelCpu = $_POST['model_cpu'];
                            } else {
                                $modelCpu = '';
                            }

                            if (isset($_POST['n_nuclis_cpu'])) {
                                $nNuclisCpu = $_POST['n_nuclis_cpu'];
                            } else {
                                $nNuclisCpu = '';
                            }

                            if ($modelCpu !== '') {
                                $cadena = "SELECT model, nNuclis FROM CPU WHERE model = '$modelCpu'";
                                $resultado = mysqli_query($conn, $cadena);
                                while ($row = $resultado->fetch_assoc()) {
                                    $cpuOptions[] = $row;
                                }
                            }

                            if ($modelCpu !== '' && $nNuclisCpu !== '') {
                                $cadena = "SELECT preu FROM CPU WHERE model = '$modelCpu' AND nNuclis = '$nNuclisCpu'";
                                $resultado = mysqli_query($conn, $cadena);
                                if ($row = $resultado->fetch_assoc()) {
                                    $precioCpu = $row['preu'];
                                }
                            }
                        }
                        ?>

                        <div class="form-group">
                            <label for="model_cpu">Selecciona Modelo de CPU</label>
                            <select name="model_cpu" id="model_cpu" class="form-control" onchange="this.form.submit()">
                                <option value="">Selecciona Modelo</option>
                                <?php
                                $cadena = "SELECT DISTINCT model FROM CPU";
                                $resultado = mysqli_query($conn, $cadena);
                                while ($row = $resultado->fetch_assoc()) {
                                    $selected = '';
                                    if (isset($modelCpu)) {
                                        if ($row['model'] === $modelCpu) {
                                            $selected = 'selected';
                                        }
                                    } else {
                                        if ($row['model'] === '') {
                                            $selected = 'selected';
                                        }
                                    }

                                    echo "<option value='" . $row['model'] . "' $selected>" . $row['model'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="n_nuclis_cpu">Selecciona Número de Núcleos</label>
                            <select name="n_nuclis_cpu" id="n_nuclis_cpu" class="form-control" onchange="this.form.submit()">
                                <option value="">Selecciona Núcleos</option>
                                <?php
                                while ($option = current($cpuOptions)) {
                                    $selected = '';
                                    if (isset($nNuclisCpu)) {
                                        if ($option['nNuclis'] == $nNuclisCpu) {
                                            $selected = 'selected';
                                        }
                                    } else {
                                        if ($option['nNuclis'] === '') {
                                            $selected = 'selected';
                                        }
                                    }

                                    echo "<option value='" . $option['nNuclis'] . "' $selected>" . $option['nNuclis'] . " Núcleos</option>";
                                    next($cpuOptions);
                                }
                                ?>
                            </select>
                        </div>  

                    </fieldset>


                    <fieldset>
                        <legend>SISTEMA OPERATIU</legend>
                        <?php
                        $precioSo = '';

                        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                            if (isset($_POST['nom_so'])) {
                                $nomSo = $_POST['nom_so'];
                            } else {
                                $nomSo = '';
                            }

                            if ($nomSo !== '') {
                                $cadena = "SELECT preu FROM SO WHERE nom = '$nomSo'";
                                $resultado = mysqli_query($conn, $cadena);
                                if ($row = $resultado->fetch_assoc()) {
                                    $precioSo = $row['preu'];
                                }
                            }
                        }
                        ?>

                        <div class="form-group">
                            <label for="nom_so">Selecciona Sistema Operativo</label>
                            <select name="nom_so" id="nom_so" class="form-control" onchange="this.form.submit()">
                                <option value="">Selecciona Sistema Operativo</option>
                                <?php
                                $cadena = "SELECT DISTINCT nom FROM SO";
                                $resultado = mysqli_query($conn, $cadena);
                                while ($row = $resultado->fetch_assoc()) {
                                    $selected = '';
                                    if (isset($nomSo)) {
                                        if ($row['nom'] === $nomSo) {
                                            $selected = 'selected';
                                        }
                                    } else {
                                        if ($row['nom'] === '') {
                                            $selected = 'selected';
                                        }
                                    }
                                        
                                    echo "<option value='" . $row['nom'] . "' $selected>" . $row['nom'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>

                    </fieldset>

                    <!-- añadir más componentes -->

                    <div class="container">
                        <button type="submit" class="btn btn-primary" formaction="servicesform.php">Montar</button>
                    </div>
                </form>
                </div>

                <!-- Columna derecha: Resumen de selección -->
                <div class="col-md-4">
                    <div class="sticky-top">
                        <h4 class="text-center">Resumen de selección</h4>
                        <ul class="list-group">
                            <li class="list-group-item">
                                RAM: <?php echo isset($tipoRam) ? $tipoRam . " - " . $gbRam . " GB" : "No seleccionado"; ?>
                                <span class="float-right"><?php echo $precioRam ? "€" . $precioRam : ""; ?></span>
                            </li>
                            <li class="list-group-item">
                                DISC DUR: <?php echo isset($tipoDiscDur) ? $tipoDiscDur . " - " . $gbDiscDur . " GB" : "No seleccionado"; ?>
                                <span class="float-right"><?php echo $precioDiscDur ? "€" . $precioDiscDur : ""; ?></span>
                            </li>
                            <li class="list-group-item">
                                CPU: <?php echo isset($modelCpu) ? $modelCpu . " - " . $nNuclisCpu . " núcleos" : "No seleccionado"; ?>
                                <span class="float-right"><?php echo $precioCpu ? "€" . $precioCpu : ""; ?></span>
                            </li>
                            <li class="list-group-item">
                                Sistema Operativo: <?php echo isset($nomSo) ? $nomSo : "No seleccionado"; ?>
                                <span class="float-right"><?php echo $precioSo ? "€" . $precioSo : ""; ?></span>
                            </li>
                            <h5 class="text-center mt-3">
                                Precio Total:
                                <?php
                                $precioRam = isset($precioRam) ? floatval($precioRam) : 0;
                                $precioDiscDur = isset($precioDiscDur) ? floatval($precioDiscDur) : 0;
                                $precioCpu = isset($precioCpu) ? floatval($precioCpu) : 0;
                                $precioSo = isset($precioSo) ? floatval($precioSo) : 0;

                                $precioTotal = $precioRam + $precioDiscDur + $precioCpu + $precioSo;
                                echo "€" . $precioTotal;
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