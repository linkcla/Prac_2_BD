<!-- @Author: Pau Toni Bibiloni Martínez -->

<?php
session_start();
include "conexion.php";
$conn = Conexion::getConnection();

if (isset($_GET['idConfig'])) {
    $idConfig = $_GET['idConfig'];
    $query = "SELECT * FROM PAAS WHERE idConfig='$idConfig'";
    $result = mysqli_query($conn, $query);
    $paas = mysqli_fetch_assoc($result);

    // Obtener opciones de RAM
    $ramQuery = "SELECT DISTINCT tipus FROM RAM";
    $ramResult = mysqli_query($conn, $ramQuery);

    // Obtener opciones de cantidad de RAM
    $ramGBQuery = "SELECT tipus, GB FROM RAM";
    $ramGBResult = mysqli_query($conn, $ramGBQuery);

    // Obtener opciones de Disco Duro
    $ddQuery = "SELECT DISTINCT tipus FROM DISC_DUR";
    $ddResult = mysqli_query($conn, $ddQuery);

    // Obtener opciones de cantidad de Disco Duro
    $ddGBQuery = "SELECT tipus, GB FROM DISC_DUR";
    $ddGBResult = mysqli_query($conn, $ddGBQuery);

    // Obtener opciones de CPU
    $cpuQuery = "SELECT DISTINCT model FROM CPU";
    $cpuResult = mysqli_query($conn, $cpuQuery);

    // Obtener opciones de cantidad de CPU
    $cpuNucliQuery = "SELECT model, nNuclis FROM CPU";
    $cpuNucliResult = mysqli_query($conn, $cpuNucliQuery);

    // Obtener opciones de Sistema Operativo
    $soQuery = "SELECT * FROM SO";
    $soResult = mysqli_query($conn, $soQuery);
} else {
    $_SESSION["warning_msg"] = "No se ha seleccionado ningún PaaS para editar.";
    header("Location: servicesPaaSPersonalInicioEditform.php");
    exit();
}

// Filtrar opciones según el tipo seleccionado
$selectedTipusRAM = isset($_POST['tipusRAM']) ? $_POST['tipusRAM'] : $paas['tipusRAM'];
$selectedTipusDD = isset($_POST['tipusDD']) ? $_POST['tipusDD'] : $paas['tipusDD'];
$selectedModelCPU = isset($_POST['modelCPU']) ? $_POST['modelCPU'] : $paas['modelCPU'];
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
                    <select class="form-control" id="tipusRAM" name="tipusRAM" onchange="this.form.submit()">
                        <?php while ($rowRAM = mysqli_fetch_assoc($ramResult)) { ?>
                            <option value="<?php echo $rowRAM['tipus']; ?>" <?php if ($rowRAM['tipus'] == $selectedTipusRAM) echo 'selected'; ?>>
                                <?php echo $rowRAM['tipus']; ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="GBRam">Cantidad de RAM (GB)</label>
                    <select class="form-control" id="GBRam" name="GBRam">
                        <?php 
                        mysqli_data_seek($ramGBResult, 0);
                        while ($rowRAMGB = mysqli_fetch_assoc($ramGBResult)) { 
                            if ($rowRAMGB['tipus'] == $selectedTipusRAM) {
                                $selected = ($rowRAMGB['GB'] == $paas['GBRam']) ? 'selected' : '';
                                echo "<option value='{$rowRAMGB['GB']}' $selected>{$rowRAMGB['GB']}</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="tipusDD">Tipo de Disco Duro</label>
                    <select class="form-control" id="tipusDD" name="tipusDD" onchange="this.form.submit()">
                        <?php while ($rowDD = mysqli_fetch_assoc($ddResult)) { ?>
                            <option value="<?php echo $rowDD['tipus']; ?>" <?php if ($rowDD['tipus'] == $selectedTipusDD) echo 'selected'; ?>>
                                <?php echo $rowDD['tipus']; ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="GBDD">Cantidad de Disco Duro (GB)</label>
                    <select class="form-control" id="GBDD" name="GBDD">
                        <?php 
                        mysqli_data_seek($ddGBResult, 0);
                        while ($rowDDGB = mysqli_fetch_assoc($ddGBResult)) { 
                            if ($rowDDGB['tipus'] == $selectedTipusDD) {
                                $selected = ($rowDDGB['GB'] == $paas['GBDD']) ? 'selected' : '';
                                echo "<option value='{$rowDDGB['GB']}' $selected>{$rowDDGB['GB']}</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="modelCPU">Modelo de CPU</label>
                    <select class="form-control" id="modelCPU" name="modelCPU" onchange="this.form.submit()">
                        <?php while ($rowCPU = mysqli_fetch_assoc($cpuResult)) { ?>
                            <option value="<?php echo $rowCPU['model']; ?>" <?php if ($rowCPU['model'] == $selectedModelCPU) echo 'selected'; ?>>
                                <?php echo $rowCPU['model']; ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="nNuclis">Número de Núcleos</label>
                    <select class="form-control" id="nNuclis" name="nNuclis">
                        <?php 
                        mysqli_data_seek($cpuNucliResult, 0);
                        while ($rowNucliCPU = mysqli_fetch_assoc($cpuNucliResult)) { 
                            if ($rowNucliCPU['model'] == $selectedModelCPU) {
                                $selected = ($rowNucliCPU['nNuclis'] == $paas['nNuclis']) ? 'selected' : '';
                                echo "<option value='{$rowNucliCPU['nNuclis']}' $selected>{$rowNucliCPU['nNuclis']}</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary" formaction="servicesPaaSPersonalEditBD.php">Guardar Cambios</button>
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
</body>

</html>
