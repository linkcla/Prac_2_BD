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
                                <a href="servicesPaaSPersonalform.php">PaaS</a>
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
                Servicios SaaS - Test
            </h2>
            <form>
                <div class="container">
                    <button type="submit" class="btn btn-primary" formaction="servicesSaaSViewform.php">Inicio</button>
                    <button type="submit" class="btn btn-primary" formaction="servicesSaaSPersonalform.php">Contratos SaaS</button>
                    <button type="submit" class="btn btn-primary" formaction="servicesSaaSCreateform.php">Crear</button>
                    <button type="submit" class="btn btn-primary" formaction="servicesSaaSDeleteform.php">Eliminar</button>
                    <button type="submit" class="btn btn-primary" formaction="servicesSaaSTestform.php">Test</button>
                </div>
            </form>
            
                <div class="card p-4" style="width: 100%;">
                    <div class="container d-flex justify-content-center align-items-center">
                        <form action="servicesSaaSTestBD.php" method="POST">
                            <div class="form-row align-items-center">
                                <div class="col-auto">
                                    <input type="text" class="form-control mb-2" id="testName" name="testName" placeholder="Nombre del nuevo Test" required>
                                </div>
                                <div class="col-auto">
                                    <input type="text" class="form-control mb-2" id="testDescription" name="testDescription" placeholder="Descripción del Test" required>
                                </div>
                                <div class="col-auto">
                                    <button type="submit" class="btn btn-primary mb-2" name="createTest">Crear Test</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="container d-flex justify-content-center align-items-center">
                        <form action="servicesSaaSTestBD.php" method="POST">
                            <div class="form-row align-items-center2">
                                <div class="col-auto1">
                                        <?php
                                        $testOptions = [];
                                        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                                            if (isset($_POST['noms'])) {
                                                $testnom = $_POST['noms'];
                                            } else {
                                            $testnom = '';
                                            }
                                            if ($testnom !== '') {
                                                $cadena = "SELECT * FROM TEST WHERE nom = '$testnom'";
                                                $resultado = mysqli_query($conn, $cadena);
                                                while ($row = $resultado->fetch_assoc()) {
                                                    $testOptions[] = $row;
                                                }
                                            }
                                        }
                                        ?>
                                    
                                        <select name="noms" id="noms" class="form-control" >
                                            <option value="">Selecciona Test a Eliminar</option>
                                            <?php
                                                $cadena = "SELECT DISTINCT nom FROM TEST";
                                                $resultado = mysqli_query($conn, $cadena);
                                                while ($row = $resultado->fetch_assoc()) {
                                                    $selected = '';
                                                    if (isset($testnom)) {
                                                        if ($row['nom'] === $testnom) {
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
                                <div class="col-auto1">
                                    <button type="submit" class="btn btn-primary mb-3" name="deleteTest">Eliminar Test</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            
        </div>
        <div class="container">
            <form action="servicesSaaSTestBD.php" method="POST" onsubmit="return validateForm()">
            
                        <!-- Tabla para mostrar los datos de CONTRACTE -->
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Seleccionar</th>
                                    <th>ID Configuración</th>
                                    <th>Nombre del Test</th>
                                    <th>Estado del Test</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $cadenaContracte = "
                                SELECT 
                                    SAAS.idConfig, 
                                    TEST.nom AS testNom,
                                    ESTAT.estat AS testEstat
                                FROM SAAS
                                LEFT JOIN ESTAT ON SAAS.idConfig = ESTAT.idConfigProducte
                                LEFT JOIN TEST ON ESTAT.nomT = TEST.nom
                                ORDER BY SAAS.idConfig, TEST.nom
                                ";
                                
                                $resultado = mysqli_query($conn, $cadenaContracte);
                                
                                if (!$resultado) {
                                    die("Error al obtener datos: " . mysqli_error($conn));
                                }

                                while ($rowContracte = mysqli_fetch_assoc($resultado)) {
                                    $value = $rowContracte['idConfig'] . '|' . $rowContracte['testNom'];
                                    echo "<tr>
                                        <td>
                                            <input type='radio' name='selectedRow' value='{$value}'>
                                        </td>
                                        <td>{$rowContracte['idConfig']}</td>
                                        <td>{$rowContracte['testNom']}</td>
                                        <td>{$rowContracte['testEstat']}</td>
                                    </tr>";
                                }
                                
                                ?>
                            </tbody>
                        </table>
                        <div class="container d-flex justify-content-center align-items-center">
                            <div class="form-row align-items-center3">
                                <div class="col-auto2">
                                    <?php
                                     $estatOptions = [];
                                     if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                                         if (isset($_POST['nomsestats'])) {
                                             $testnom = $_POST['nomsestats'];
                                         } else {
                                         $testnom = '';
                                         }
                                         if ($testnom !== '') {
                                             $cadena = "SELECT * FROM ESTAT WHERE estat = '$testnom'";
                                             $resultado = mysqli_query($conn, $cadena);
                                             while ($row = $resultado->fetch_assoc()) {
                                                 $testOptions[] = $row;
                                             }
                                         }
                                     }
                                    ?>
                                    
                                    <select name="nomsestats" id="nomsestats" class="form-control">
                                        <option value="">Selecciona un Estado</option>
                                        <?php
                                        $sql = "SELECT DISTINCT estat FROM ESTAT";
                                        $resultado = mysqli_query($conn, $sql);
                                        while ($row = $resultado->fetch_assoc()) {
                                            $selected = '';
                                            if (isset($testnom)) {
                                                if ($row['estat'] === $testnom) {
                                                    $selected = 'selected';
                                                }
                                            } else {
                                                if ($row['estat'] === '') {
                                                    $selected = 'selected';
                                                }
                                            }
                                            echo "<option value='" . $row['estat'] . "' $selected>" . $row['estat'] . "</option>";
                                        }
                                        ?>

                                    </select>
                                </div>   
                                <div class="col-auto2">
                                    <button type="submit" class="btn btn-primary mb-3" name="editEstat">Actualizar Estado Test</button>
                                </div>
                            </div>
                        </div>
                </form>
                <script>
                function validateForm() {
                    const selectedRow = document.querySelector('input[name="selectedRow"]:checked');
                    const selectedState = document.getElementById('nomsestats').value;
                    if (!selectedRow) {
                        alert('Por favor, selecciona un registro.');
                        return false;
                    }
                    if (selectedState === '') {
                        alert('Por favor, selecciona un estado.');
                        return false;
                    }
                    return true;
                }
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