<!-- @Author: Pau Toni Bibiloni Martínez -->

<?php 
session_start();
include "conexion.php";
include "PaaSFuncionalidades.php"; // Incluir el archivo que contiene la clase PaaSFuncionalidades

$conn = Conexion::getConnection();

// Variables para almacenar los valores de los campos del formulario
$tipoRam = $gbRam = $tipoDiscDur = $gbDiscDur = $modelCpu = $nNuclisCpu = $nomSo = $tipoIpv = $direccionIpv = '';
$ramOptions = $discDurOptions = $cpuOptions = [];
$precioRam = $precioDiscDur = $precioCpu = $precioSo = '';
$nombreProducto = $descripcionProducto = '';

// Verificar si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tipoRam = $_POST['tipo_ram'] ?? '';
    $gbRam = $_POST['gb_ram'] ?? '';
    $tipoDiscDur = $_POST['tipo_disc_dur'] ?? '';
    $gbDiscDur = $_POST['gb_disc_dur'] ?? '';
    $modelCpu = $_POST['model_cpu'] ?? '';
    $nNuclisCpu = $_POST['n_nuclis_cpu'] ?? '';
    $nomSo = $_POST['nom_so'] ?? '';
    $tipoIpv = $_POST['tipo_ipv'] ?? '';
    $direccionIpv = $_POST['direccion_ipv'] ?? '';
    $nombreProducto = $_POST['nombre_producto'] ?? '';
    $descripcionProducto = $_POST['descripcion_producto'] ?? '';

    // Consultas para obtener opciones y precios de los componentes seleccionados
    $res = mysqli_query($conn, "SELECT tipus, GB FROM RAM WHERE tipus = '$tipoRam'");
    while ($row = $res->fetch_assoc()) $ramOptions[] = $row;

    $res = mysqli_query($conn, "SELECT preu FROM RAM WHERE tipus = '$tipoRam' AND GB = '$gbRam'");
    if ($row = $res->fetch_assoc()) $precioRam = $row['preu'];

    $res = mysqli_query($conn, "SELECT tipus, GB FROM DISC_DUR WHERE tipus = '$tipoDiscDur'");
    while ($row = $res->fetch_assoc()) $discDurOptions[] = $row;

    $res = mysqli_query($conn, "SELECT preu FROM DISC_DUR WHERE tipus = '$tipoDiscDur' AND GB = '$gbDiscDur'");
    if ($row = $res->fetch_assoc()) $precioDiscDur = $row['preu'];

    $res = mysqli_query($conn, "SELECT model, nNuclis FROM CPU WHERE model = '$modelCpu'");
    while ($row = $res->fetch_assoc()) $cpuOptions[] = $row;

    $res = mysqli_query($conn, "SELECT preu FROM CPU WHERE model = '$modelCpu' AND nNuclis = '$nNuclisCpu'");
    if ($row = $res->fetch_assoc()) $precioCpu = $row['preu'];

    $res = mysqli_query($conn, "SELECT preu FROM SO WHERE nom = '$nomSo'");
    if ($row = $res->fetch_assoc()) $precioSo = $row['preu'];

    // Variables para almacenar las direcciones iPv4 e iPv6
    $iPv4 = $tipoIpv === 'iPv4' ? $direccionIpv : '';
    $iPv6 = $tipoIpv === 'iPv6' ? $direccionIpv : '';

    // Si se ha pulsado el botón de crear PaaS
    if (isset($_POST['crear_paas'])) {
        // Crear una instancia de la clase PaaSFuncionalidades
        $paasFuncionalidades = new PaaSFuncionalidades($conn);

        // Llamar al método crearPaaS
        echo $paasFuncionalidades->crearPaaS($conn, $tipoRam, $gbRam, $tipoDiscDur, $gbDiscDur, $modelCpu, $nNuclisCpu, $nomSo, $tipoIpv, $direccionIpv, $nombreProducto, $descripcionProducto);
    }
}
?>

<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="keywords" content="" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>MPHB</title>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.1.3/assets/owl.carousel.min.css" />
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css" />
    <link href="https://fonts.googleapis.com/css?family=Poppins|Raleway:400,600|Righteous&display=swap" rel="stylesheet" />
    <link href="css/style.css" rel="stylesheet" />
    <link href="css/responsive.css" rel="stylesheet" />
    <style>
        fieldset { 
            margin-bottom: 20px;
            padding: 20px;
            border: 1px
            solid #ccc;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="hero_area">
        <header class="header_section">
            <div class="container-fluid">
                <nav class="navbar navbar-expand-lg custom_nav-container">
                    <a class="navbar-brand" href="loginform.php"><span>MPHB</span></a>
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
    </div>

    <section class="about_section layout_paddingAbout">
        <div class="container">
            <h2 class="text-uppercase">Servicios PaaS - Crear PaaS</h2>
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
            <div class="row d-flex">
                <div class="col-md-8">
                    <form action="servicesPaaSPersonalCreatePaaSform.php" method="POST">

                        <!-- Seleccionar iPv4 o iPv6 -->
                        <fieldset>
                            <legend>iPv</legend>
                            <div class="form-group">
                                <label for="tipo_ipv">Selecciona Tipo de iPv</label>
                                <select name="tipo_ipv" id="tipo_ipv" class="form-control" onchange="this.form.submit()">
                                    <option value="">Selecciona Tipo</option>
                                    <option value="iPv4" <?php echo isset($tipoIpv) && $tipoIpv === 'iPv4' ? 'selected' : ''; ?>>iPv4</option>
                                    <option value="iPv6" <?php echo isset($tipoIpv) && $tipoIpv === 'iPv6' ? 'selected' : ''; ?>>iPv6</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="direccion_ipv">Introduce la dirección iPv</label>
                                <input type="text" name="direccion_ipv" id="direccion_ipv" class="form-control" value="<?php echo $direccionIpv ?? ''; ?>" placeholder="Ej: 111.111.1.1">
                            </div>
                        </fieldset>

                        <!-- Seleccionar RAM -->
                        <fieldset>
                            <legend>RAM</legend>
                            <div class="form-group">
                                <label for="tipo_ram">Selecciona Tipo de RAM</label>
                                <select name="tipo_ram" id="tipo_ram" class="form-control" onchange="this.form.submit()">
                                    <option value="">Selecciona Tipo</option>
                                    <?php
                                    $res = mysqli_query($conn, "SELECT DISTINCT tipus FROM RAM");
                                    while ($row = $res->fetch_assoc()) {
                                        $selected = isset($tipoRam) && $row['tipus'] === $tipoRam ? 'selected' : '';
                                        echo "<option value='{$row['tipus']}' $selected>{$row['tipus']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="gb_ram">Selecciona GB de RAM</label>
                                <select name="gb_ram" id="gb_ram" class="form-control" onchange="this.form.submit()">
                                    <option value="">Selecciona GB</option>
                                    <?php
                                    foreach ($ramOptions as $option) {
                                        $selected = isset($gbRam) && $option['GB'] == $gbRam ? 'selected' : '';
                                        echo "<option value='{$option['GB']}' $selected>{$option['GB']} GB</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </fieldset>

                        <!-- Seleccionar DISC DUR -->
                        <fieldset>
                            <legend>DISC DUR</legend>
                            <div class="form-group">
                                <label for="tipo_disc_dur">Selecciona Tipo de DISC DUR</label>
                                <select name="tipo_disc_dur" id="tipo_disc_dur" class="form-control" onchange="this.form.submit()">
                                    <option value="">Selecciona Tipo</option>
                                    <?php
                                    $res = mysqli_query($conn, "SELECT DISTINCT tipus FROM DISC_DUR");
                                    while ($row = $res->fetch_assoc()) {
                                        $selected = isset($tipoDiscDur) && $row['tipus'] === $tipoDiscDur ? 'selected' : '';
                                        echo "<option value='{$row['tipus']}' $selected>{$row['tipus']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="gb_disc_dur">Selecciona GB de DISC DUR</label>
                                <select name="gb_disc_dur" id="gb_disc_dur" class="form-control" onchange="this.form.submit()">
                                    <option value="">Selecciona GB</option>
                                    <?php
                                    foreach ($discDurOptions as $option) {
                                        $selected = isset($gbDiscDur) && $option['GB'] == $gbDiscDur ? 'selected' : '';
                                        echo "<option value='{$option['GB']}' $selected>{$option['GB']} GB</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </fieldset>

                        <!-- Seleccionar CPU -->
                        <fieldset>
                            <legend>CPU</legend>
                            <div class="form-group">
                                <label for="model_cpu">Selecciona Modelo de CPU</label>
                                <select name="model_cpu" id="model_cpu" class="form-control" onchange="this.form.submit()">
                                    <option value="">Selecciona Modelo</option>
                                    <?php
                                    $res = mysqli_query($conn, "SELECT DISTINCT model FROM CPU");
                                    while ($row = $res->fetch_assoc()) {
                                        $selected = isset($modelCpu) && $row['model'] === $modelCpu ? 'selected' : '';
                                        echo "<option value='{$row['model']}' $selected>{$row['model']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="n_nuclis_cpu">Selecciona Número de Núcleos</label>
                                <select name="n_nuclis_cpu" id="n_nuclis_cpu" class="form-control" onchange="this.form.submit()">
                                    <option value="">Selecciona Núcleos</option>
                                    <?php
                                    foreach ($cpuOptions as $option) {
                                        $selected = isset($nNuclisCpu) && $option['nNuclis'] == $nNuclisCpu ? 'selected' : '';
                                        echo "<option value='{$option['nNuclis']}' $selected>{$option['nNuclis']} Núcleos</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </fieldset>
                        
                        <!-- Seleccionar SO -->
                        <fieldset>
                            <legend>SISTEMA OPERATIU</legend>
                            <div class="form-group">
                                <label for="nom_so">Selecciona Sistema Operativo</label>
                                <select name="nom_so" id="nom_so" class="form-control" onchange="this.form.submit()">
                                    <option value="">Selecciona Sistema Operativo</option>
                                    <?php
                                    $res = mysqli_query($conn, "SELECT DISTINCT nom FROM SO");
                                    while ($row = $res->fetch_assoc()) {
                                        $selected = isset($nomSo) && $row['nom'] === $nomSo ? 'selected' : '';
                                        echo "<option value='{$row['nom']}' $selected>{$row['nom']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </fieldset>

                        <!-- Nombre y Descripción del Producto -->
                        <fieldset>
                            <legend>Producto</legend>
                            <div class="form-group">
                                <label for="nombre_producto">Nombre del Producto</label>
                                <input type="text" name="nombre_producto" id="nombre_producto" class="form-control" value="<?php echo $nombreProducto; ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="descripcion_producto">Descripción del Producto</label>
                                <textarea name="descripcion_producto" id="descripcion_producto" class="form-control" required><?php echo $descripcionProducto; ?></textarea>
                            </div>
                        </fieldset>
                                
                        <!-- Botón para crear PaaS -->
                        <div class="container">
                            <button type="submit" name="crear_paas" class="btn btn-primary">Crear Producto PaaS</button>
                        </div>
                    </form>
                </div>

                <div class="col-md-4">
                    <div class="sticky-top">
                        <h4 class="text-center">Resumen de selección</h4>
                        <ul class="list-group">
                            <!-- Mostrar los valores seleccionados y los precios de los componentes -->
                            <li class="list-group-item">
                                RAM: <?php echo $tipoRam ? "$tipoRam - $gbRam GB" : "No seleccionado"; ?>
                                <span class="float-right"><?php echo $precioRam ? "€$precioRam" : ""; ?></span>
                            </li>
                            <li class="list-group-item">
                                DISC DUR: <?php echo $tipoDiscDur ? "$tipoDiscDur - $gbDiscDur GB" : "No seleccionado"; ?>
                                <span class="float-right"><?php echo $precioDiscDur ? "€$precioDiscDur" : ""; ?></span>
                            </li>
                            <li class="list-group-item">
                                CPU: <?php echo $modelCpu ? "$modelCpu - $nNuclisCpu núcleos" : "No seleccionado"; ?>
                                <span class="float-right"><?php echo $precioCpu ? "€$precioCpu" : ""; ?></span>
                            </li>
                            <li class="list-group-item">
                                Sistema Operativo: <?php echo $nomSo ? "$nomSo" : "No seleccionado"; ?>
                                <span class="float-right"><?php echo $precioSo ? "€$precioSo" : ""; ?></span>
                            </li>
                            <!-- Mostrar el precio total de los componentes seleccionados -->
                            <h5 class="text-center mt-3">
                                Precio Total: <?php echo "€" . (floatval($precioRam) + floatval($precioDiscDur) + floatval($precioCpu) + floatval($precioSo)); ?>
                            </h5>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="container-fluid footer_section">
        <p>&copy; 2024 (UIB - EPS). Design by MPHB</p>
    </section>

    <script>
        function openNav() {
            document.getElementById("myNav").classList.toggle("menu_width");
            document.querySelector(".custom_menu-btn").classList.toggle("menu_btn-style");
        }
    </script>
</body>
</html>