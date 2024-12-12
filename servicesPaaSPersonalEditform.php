<!-- @Author: Pau Toni Bibiloni Martínez -->

<?php
session_start();
include "conexion.php";
include "PaaSFuncionalidades.php";
$conn = Conexion::getConnection();
$paasFuncionalidades = new PaaSFuncionalidades($conn);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $idConfig = $_POST['idConfig'];
    $iPv4 = $_POST['iPv4'];
    $iPv6 = $_POST['iPv6'];
    $nomSO = $_POST['nomSO'];
    $tipusRAM = $_POST['tipusRAM'];
    $GBRam = $_POST['GBRam'];
    $tipusDD = $_POST['tipusDD'];
    $GBDD = $_POST['GBDD'];
    $modelCPU = $_POST['modelCPU'];
    $nNuclis = $_POST['nNuclis'];

    if ($paasFuncionalidades->updatePaaS($idConfig, $iPv4, $iPv6, $nomSO, $tipusRAM, $GBRam, $tipusDD, $GBDD, $modelCPU, $nNuclis)) {
        header("Location: servicesPaaSPersonalInicioEditform.php");
        exit();
    } else {
        header("Location: servicesPaaSPersonalEditform.php?idConfig=$idConfig");
        exit();
    }
}

if (isset($_GET['idConfig'])) {
    $idConfig = $_GET['idConfig'];
    $query = "SELECT * FROM PAAS WHERE idConfig='$idConfig'";
    $result = mysqli_query($conn, $query);
    $paas = mysqli_fetch_assoc($result);

    // Obtener opciones de RAM
    $ramQuery = "SELECT tipus, GB FROM RAM";
    $ramResult = mysqli_query($conn, $ramQuery);
    $ramOptions = [];
    while ($rowRAM = mysqli_fetch_assoc($ramResult)) {
        $ramOptions[$rowRAM['tipus']][] = $rowRAM['GB'];
    }

    // Obtener opciones de Disco Duro
    $ddQuery = "SELECT tipus, GB FROM DISC_DUR";
    $ddResult = mysqli_query($conn, $ddQuery);
    $ddOptions = [];
    while ($rowDD = mysqli_fetch_assoc($ddResult)) {
        $ddOptions[$rowDD['tipus']][] = $rowDD['GB'];
    }

    // Obtener opciones de CPU
    $cpuQuery = "SELECT model, nNuclis FROM CPU";
    $cpuResult = mysqli_query($conn, $cpuQuery);
    $cpuOptions = [];
    while ($rowCPU = mysqli_fetch_assoc($cpuResult)) {
        $cpuOptions[$rowCPU['model']][] = $rowCPU['nNuclis'];
    }

    // Obtener opciones de Sistema Operativo
    $soQuery = "SELECT DISTINCT nom FROM SO";
    $soResult = mysqli_query($conn, $soQuery);
} else {
    $_SESSION["warning_msg"] = "No se ha seleccionado ningún PaaS para editar.";
    header("Location: servicesPaaSPersonalInicioEditform.php");
    exit();
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

    <section class="about_section layout_paddingAbout"  style="min-height: calc(100vh - 200px);">
        <div class="container">
            <h2 class="text-uppercase">
                Servicios PaaS - Editar PaaS
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
            ?>
            <form method="POST" action="servicesPaaSPersonalEditform.php?idConfig=<?php echo $idConfig; ?>">
                <input type="hidden" name="idConfig" value="<?php echo $paas['idConfig']; ?>">
                <div class="form-group">
                    <label for="iPv4">iPv4</label>
                    <input type="text" class="form-control" id="iPv4" name="iPv4" value="<?php echo $paas['iPv4']; ?>">
                </div>
                <div class="form-group">
                    <label for="iPv6">iPv6</label>
                    <input type="text" class="form-control" id="iPv6" name="iPv6" value="<?php echo $paas['iPv6']; ?>">
                </div>
                <div class="form-group">
                    <label for="nomSO">Sistema Operativo</label>
                    <select class="form-control" id="nomSO" name="nomSO">
                        <?php while ($rowSO = mysqli_fetch_assoc($soResult)) { ?>
                            <option value="<?php echo $rowSO['nom']; ?>" <?php if ($rowSO['nom'] == $paas['nomSO']) echo 'selected'; ?>>
                                <?php echo $rowSO['nom']; ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="tipusRAM">Tipo de RAM</label>
                    <select class="form-control" id="tipusRAM" name="tipusRAM" onchange="updateGBRamOptions()">
                        <?php foreach (array_keys($ramOptions) as $tipus) { ?>
                            <option value="<?php echo $tipus; ?>" <?php if ($tipus == $paas['tipusRAM']) echo 'selected'; ?>>
                                <?php echo $tipus; ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="GBRam">Cantidad de RAM (GB)</label>
                    <select class="form-control" id="GBRam" name="GBRam">
                        <!-- Las opciones se actualizarán dinámicamente con JavaScript -->
                    </select>
                </div>
                <div class="form-group">
                    <label for="tipusDD">Tipo de Disco Duro</label>
                    <select class="form-control" id="tipusDD" name="tipusDD" onchange="updateGBDDOptions()">
                        <?php foreach (array_keys($ddOptions) as $tipus) { ?>
                            <option value="<?php echo $tipus; ?>" <?php if ($tipus == $paas['tipusDD']) echo 'selected'; ?>>
                                <?php echo $tipus; ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="GBDD">Cantidad de Disco Duro (GB)</label>
                    <select class="form-control" id="GBDD" name="GBDD">
                        <!-- Las opciones se actualizarán dinámicamente con JavaScript -->
                    </select>
                </div>
                <div class="form-group">
                    <label for="modelCPU">Modelo de CPU</label>
                    <select class="form-control" id="modelCPU" name="modelCPU" onchange="updateNuclisOptions()">
                        <?php foreach (array_keys($cpuOptions) as $model) { ?>
                            <option value="<?php echo $model; ?>" <?php if ($model == $paas['modelCPU']) echo 'selected'; ?>>
                                <?php echo $model; ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="nNuclis">Número de Núcleos</label>
                    <select class="form-control" id="nNuclis" name="nNuclis">
                        <!-- Las opciones se actualizarán dinámicamente con JavaScript -->
                    </select>
                </div>
                <button type="submit" class="btn btn-primary custom-btn">Guardar Cambios</button>
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

    <script>
        const ramOptions = <?php echo json_encode($ramOptions); ?>;
        const ddOptions = <?php echo json_encode($ddOptions); ?>;
        const cpuOptions = <?php echo json_encode($cpuOptions); ?>;

        function updateGBRamOptions() {
            const tipusRAM = document.getElementById('tipusRAM').value;
            const gbramSelect = document.getElementById('GBRam');
            gbramSelect.innerHTML = '';

            ramOptions[tipusRAM].forEach(gb => {
                const opt = document.createElement('option');
                opt.value = gb;
                opt.innerHTML = gb;
                gbramSelect.appendChild(opt);
            });
        }

        function updateGBDDOptions() {
            const tipusDD = document.getElementById('tipusDD').value;
            const gbddSelect = document.getElementById('GBDD');
            gbddSelect.innerHTML = '';

            ddOptions[tipusDD].forEach(gb => {
                const opt = document.createElement('option');
                opt.value = gb;
                opt.innerHTML = gb;
                gbddSelect.appendChild(opt);
            });
        }

        function updateNuclisOptions() {
            const modelCPU = document.getElementById('modelCPU').value;
            const nuclisSelect = document.getElementById('nNuclis');
            nuclisSelect.innerHTML = '';

            cpuOptions[modelCPU].forEach(nucli => {
                const opt = document.createElement('option');
                opt.value = nucli;
                opt.innerHTML = nucli;
                nuclisSelect.appendChild(opt);
            });
        }

        // Inicializar las opciones al cargar la página
        document.addEventListener('DOMContentLoaded', function() {
            updateGBRamOptions();
            updateGBDDOptions();
            updateNuclisOptions();
        });
    </script>
</body>

</html>