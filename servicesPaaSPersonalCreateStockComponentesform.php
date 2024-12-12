<!-- @Author: Pau Toni Bibiloni Martínez -->

<?php session_start() ;
include "src/conexio.php";
include "src/componentes.php";

$conn = Conexion::getConnection();

// Obtener tipos de RAM desde la base de datos
$ramTypesQuery = "SELECT DISTINCT tipus FROM RAM";
$ramTypesResult = mysqli_query($conn, $ramTypesQuery);
$ramTypes = [];
while ($row = mysqli_fetch_assoc($ramTypesResult)) {
    $ramTypes[] = $row['tipus'];
}

// Verificar si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['component'])) {
        $component = $_POST['component'];
        $tipo = isset($_POST['tipo']) ? $_POST['tipo'] : null;
        $gb = isset($_POST['gb']) ? $_POST['gb'] : null;
        $nNuclis = isset($_POST['nNuclis']) ? $_POST['nNuclis'] : null;
        $precio = isset($_POST['precio']) ? $_POST['precio'] : null;

        // Verificar que los campos obligatorios no estén vacíos
        if ($tipo && $precio) {
            // Validar que el precio sea un número válido
            if (!is_numeric($precio) || $precio < 0 || $precio > 999.99) {
                echo "<div class='alert alert-danger' role='alert'>El precio debe ser un número entre 0 y 999.99</div>";
            } else {
                // Crear el componente en la base de datos
                Componentes::crearComponentePaaS($conn, $component, $tipo, $gb, $nNuclis, $precio);
            }
        } else {
            // Error: Todos los campos son obligatorios.
            echo "<div class='alert alert-warning' role='alert'>Todos los campos son obligatorios.</div>";
        }
    }
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
        .container {
            margin-top: 20px;
        }
        fieldset {
            margin-bottom: 20px;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
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

    <section class="about_section layout_paddingAbout"  style="min-height: calc(100vh - 200px);">
        <div class="container">
            <h2 class="text-uppercase">
                Servicios PaaS - Añadir Componentes
            </h2>
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

                <!-- Formulario para añadir RAM -->                
                <?php if(isset($_POST['component']) && $_POST['component'] == 'RAM'): ?>
                <fieldset>
                    <legend>RAM</legend>
                    <div class="form-group">
                        <label for="tipo_ram">Tipo de RAM</label>
                        <select name="tipo" id="tipo_ram" class="form-control">
                            <?php foreach ($ramTypes as $ramType): ?>
                                <option value="<?php echo $ramType; ?>"><?php echo $ramType; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="gb_ram">GB de RAM</label>
                        <select name="gb" id="gb_ram" class="form-control">
                            <option value="4">4 GB</option>
                            <option value="8">8 GB</option>
                            <option value="16">16 GB</option>
                            <option value="32">32 GB</option>
                            <option value="64">64 GB</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="precio_ram">Precio</label>
                        <input type="text" name="precio" id="precio_ram" class="form-control" placeholder="Max: 99.99">
                    </div>
                </fieldset>
                <?php endif; ?>

                <!-- Formulario para añadir DISC DUR -->
                <?php if(isset($_POST['component']) && $_POST['component'] == 'DISC_DUR'): ?>
                <fieldset>
                    <legend>DISC DUR</legend>
                    <div class="form-group">
                        <label for="tipo_disc_dur">Tipo de DISC DUR</label>
                        <select name="tipo" id="tipo_disc_dur" class="form-control">
                            <option value="HDD">HDD</option>
                            <option value="SSD">SSD</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="gb_disc_dur">GB de DISC DUR</label>
                        <select name="gb" id="gb_disc_dur" class="form-control">
                            <option value="128">128 GB</option>
                            <option value="256">256 GB</option>
                            <option value="512">512 GB</option>
                            <option value="1024">1024 GB</option>
                            <option value="2048">2048 GB</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="precio_disc_dur">Precio</label>
                        <input type="text" name="precio" id="precio_disc_dur" class="form-control" placeholder="Max: 999.99">
                    </div>
                </fieldset>
                <?php endif; ?>

                <!-- Formulario para añadir CPU -->
                <?php if(isset($_POST['component']) && $_POST['component'] == 'CPU'): ?>
                <fieldset>
                    <legend>CPU</legend>
                    <div class="form-group">
                        <label for="model_cpu">Modelo de CPU</label>
                        <select name="tipo" id="model_cpu" class="form-control">
                            <option value="Intel">Intel</option>
                            <option value="AMD">AMD</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="n_nuclis_cpu">Número de Núcleos</label>
                        <select name="nNuclis" id="n_nuclis_cpu" class="form-control">
                            <option value="2">2 Núcleos</option>
                            <option value="4">4 Núcleos</option>
                            <option value="6">6 Núcleos</option>
                            <option value="8">8 Núcleos</option>
                            <option value="10">10 Núcleos</option>
                            <option value="12">12 Núcleos</option>
                            <option value="16">16 Núcleos</option>
                            <option value="24">24 Núcleos</option>
                            <option value="32">32 Núcleos</option>
                            <option value="64">64 Núcleos</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="precio_cpu">Precio</label>
                        <input type="text" name="precio" id="precio_cpu" class="form-control" placeholder="Max: 999.99">
                    </div>
                </fieldset>
                <?php endif; ?>
                
                <!-- Formulario para añadir Sistema Operativo -->
                <?php if(isset($_POST['component']) && $_POST['component'] == 'SO'): ?>
                <fieldset>
                    <legend>Sistema Operativo</legend>
                    <div class="form-group">
                        <label for="nom_so">Nombre del SO</label>
                        <select name="tipo" id="nom_so" class="form-control">
                            <option value="Windows">Windows</option>
                            <option value="Linux">Linux</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="precio_so">Precio</label>
                        <input type="text" name="precio" id="precio_so" class="form-control" placeholder="Max: 999.99">
                    </div>
                </fieldset>
                <?php endif; ?>

                <button type="submit" class="btn btn-primary custom-btn">Añadir Componente</button>
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
