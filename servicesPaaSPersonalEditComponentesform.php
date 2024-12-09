<!-- @Author: Pau Toni Bibiloni Martínez -->

<?php
session_start();
include "conexion.php";
include_once "PaaSFuncionalidades.php"; 

$conn = Conexion::getConnection();
$paasFuncionalidades = new PaaSFuncionalidades($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_precio'])) {
    $tipo = $_POST['component'];
    $nombre = $_POST['nombre'];
    $gb_componente = $_POST['gb_componente'];
    $precio = $_POST['precio'];

    // Llamar al método updatePrecio() de la clase PaaSFuncionalidades
    $paasFuncionalidades->updatePrecio($tipo, $nombre, $gb_componente, $precio);
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
        <header class="header_section">
            <div class="container-fluid">
                <nav class="navbar navbar-expand-lg custom_nav-container">
                    <a class="navbar-brand" href="loginform.php">
                        <span>MPHB</span>
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
    </div>

    <section class="about_section layout_paddingAbout">
        <div class="container">
            <h2 class="text-uppercase">Editar Componentes</h2>
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

            <?php if (isset($_SESSION["success_msg"])): ?>
                <div class="alert alert-success" role="alert">
                    <?php echo $_SESSION["success_msg"]; unset($_SESSION["success_msg"]); ?>
                </div>
            <?php endif; ?>
            <?php if (isset($_SESSION["error_msg"])): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $_SESSION["error_msg"]; unset($_SESSION["error_msg"]); ?>
                </div>
            <?php endif; ?>
            <?php if (isset($_SESSION["warning_msg"])): ?>
                <div class="alert alert-warning" role="alert">
                    <?php echo $_SESSION["warning_msg"]; unset($_SESSION["warning_msg"]); ?>
                </div>
            <?php endif; ?>

            <!-- Formulario para seleccionar el componente -->
            <form method="POST">
                <div class="form-group">
                    <label for="component">Selecciona Componente</label>
                    <select name="component" id="component" class="form-control" onchange="this.form.submit()">
                        <option value="">Selecciona Componente</option>
                        <option value="RAM" <?php if(isset($_POST['component']) && $_POST['component'] == 'RAM') echo 'selected'; ?>>RAM</option>
                        <option value="DISC_DUR" <?php if(isset($_POST['component']) && $_POST['component'] == 'DISC_DUR') echo 'selected'; ?>>DISC DUR</option>
                        <option value="CPU" <?php if(isset($_POST['component']) && $_POST['component'] == 'CPU') echo 'selected'; ?>>CPU</option>
                        <option value="SO" <?php if(isset($_POST['component']) && $_POST['component'] == 'SO') echo 'selected'; ?>>SO</option>
                    </select>
                </div>
            </form>

            <?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['component'])): ?>
                <!-- Formulario para seleccionar el nombre del componente -->
                <form method="POST">
                    <input type="hidden" name="component" value="<?php echo $_POST['component']; ?>">
                    <div class="form-group">
                        <label for="nombre">Nombre del Componente</label>
                        <select name="nombre" id="nombre" class="form-control" onchange="this.form.submit()">
                            <option value="">Selecciona Nombre</option>
                            <?php
                            $componentes = $paasFuncionalidades->getComponentesByTipo($_POST['component']);
                            $nombres = array_unique(array_column($componentes, 'nombre'));
                            foreach ($nombres as $nombre) {
                                $selected = isset($_POST['nombre']) && $_POST['nombre'] == $nombre ? 'selected' : '';
                                echo "<option value='$nombre' $selected>$nombre</option>";
                            }
                            ?>
                        </select>
                    </div>
                </form>
            <?php endif; ?>

            <?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nombre'])): ?>
                <!-- Formulario para seleccionar la especificación y actualizar el precio -->
                <form method="POST">
                    <input type="hidden" name="component" value="<?php echo $_POST['component']; ?>">
                    <input type="hidden" name="nombre" value="<?php echo $_POST['nombre']; ?>">
                    <?php if ($_POST['component'] !== 'SO'): ?>
                        <div class="form-group">
                            <label for="gb_componente">Especificación</label>
                            <select name="gb_componente" id="gb_componente" class="form-control">
                                <option value="">Selecciona Especificación</option>
                                <?php
                                $valor = "cores";
                                if ($_POST['component'] === 'RAM' || $_POST['component'] === 'DISC_DUR') {
                                    $valor = "GB";
                                }
                                foreach ($componentes as $componente) {
                                    if ($componente['nombre'] === $_POST['nombre']) {
                                        $selected = isset($_POST['gb_componente']) && $_POST['gb_componente'] == $componente['gb_componente'] ? 'selected' : '';
                                        echo "<option value='{$componente['gb_componente']}' $selected>{$componente['gb_componente']} {$valor}</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    <?php else: ?>
                        <input type="hidden" name="gb_componente" value="">
                    <?php endif; ?>
                    <div class="form-group">
                        <label for="precio">Precio</label>
                        <input type="text" name="precio" id="precio" class="form-control" placeholder="Max: 999.99">
                    </div>
                    <button type="submit" class="btn btn-primary" name="update_precio">Actualizar Precio</button>
                </form>
            <?php endif; ?>
        </div>
    </section>

    <section class="container-fluid footer_section">
        <p>&copy; 2024 (UIB - EPS). Design by MPHB</p>
    </section>
</body>

</html>
