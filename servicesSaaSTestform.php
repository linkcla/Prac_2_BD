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
                                <a href="servicesSaaSViewform.php">SaaS</a>
                            </div>
                            <div class="overlay-content">
                                <a href="servicesPaaSPersonalInicioEditform.php">PaaS</a>
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
                    <button type="submit" class="btn btn-primary" formaction="servicesSaaSViewform.php">Atras</button>
                    <button type="submit" class="btn btn-primary" formaction="servicesSaaSContratosform.php">Contratos SaaS</button>
                    <button type="submit" class="btn btn-primary" formaction="servicesSaaSTestform.php">Test</button>
                    <button type="submit" class="btn btn-primary" formaction="servicesSaaSComponentesform.php">Componentes SaaS</button>
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
                        <form action="servicesSaaSTestBD.php" method="POST" onsubmit="return validateForm1()">
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
                        <script>
                            function validateForm1() {
                            const selectElement = document.getElementById('noms');
                            if (selectElement.value === '') {
                                alert('Por favor, selecciona un test para eliminar.');
                                return false;
                            }
                            return true;
                            }
                        </script>
                    </div>
                </div>
            
        </div>
        <div class="container">
            <form action="servicesSaaSTestBD.php" method="POST" onsubmit="return validateForm(event)">
            
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
                                INNER JOIN ESTAT ON SAAS.idConfig = ESTAT.idConfigProducte
                                INNER JOIN TEST ON ESTAT.nomT = TEST.nom
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
                                            <input type='radio' id='selectedRow' name='selectedRow' value='{$value}'>
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
                                    
                                    <select name="nomsestats" id="estat" class="form-control">
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
                                    <button type="submit" class="btn btn-primary mb-3" name="elimarTestProd">Eliminar Test del Producto</button>
                                </div>
                            </div>
                        </div>
                </form>
                <script>
                function validateForm() {
                    const testSelect = document.getElementById('input[name="selectedRow"]:checked');
                    const estatSelect = document.getElementById('estat');
                    const buttonClicked = event.submitter.name;

                    if (buttonClicked === 'editEstat') {
                        if (!selectedRow || estatSelect.value === '') {
                            alert('Por favor, selecciona un producto y un estado.');
                            return false;
                        }
                    } else if (buttonClicked === 'elimarTestProd') {
                        if (!selectedRow) {
                            alert('Por favor, selecciona un producto.');
                            return false;
                        }
                    }
                    return true;
                }
                </script>
            
        </div>
        <div class="card p-4" style="width: 100%;">
            <div class="container d-flex justify-content-center align-items-center ">
            <form action="servicesSaaSTestBD.php" method="POST" onsubmit="return validateForm2()">
                <div class="form-row">
                    <div class="col-auto">
                    <?php
                        $testOptions = [];
                        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                            if (isset($_POST['noms'])) {
                                $testnom = $_POST['noms'];
                            } else {
                                $testnom = '';
                            }
                            if ($testnom !== '') {
                                $cadena = "SELECT * FROM SAAS WHERE nom = '$testnom'";
                                $resultado = mysqli_query($conn, $cadena);
                                while ($row = $resultado->fetch_assoc()) {
                                    $testOptions[] = $row;
                                }
                            }
                        }
                        ?>

                        <select name="idConfigs" id="idConfigs" class="form-control">
                            <option value="">Selecciona el producto</option>
                            <?php
                            $cadena = "SELECT idConfig FROM SAAS";
                            $resultado = mysqli_query($conn, $cadena);
                            while ($row = $resultado->fetch_assoc()) {
                                $selected = '';
                                if (isset($testnom)) {
                                    if ($row['idConfig'] === $testnom) {
                                        $selected = 'selected';
                                    }
                                } else {
                                    if ($row['idConfig'] === '') {
                                        $selected = 'selected';
                                    }
                                }
                                echo "<option value='" . $row['idConfig'] . "' $selected>" . $row['idConfig'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-auto">
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

                        <select name="noms" id="nomsIns" class="form-control">
                            <option value="">Selecciona Test a Añadir</option>
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
                    <div class="col-auto">
                        <button type="submit" class="btn btn-primary mb-3" name="añadirEstadoTesT">Añadir Test a un Producto</button>
                    </div>
                </div>
            </form>

            <script>
            function validateForm2() {
                const testSelect = document.getElementById('nomsIns');
                const prodSelect = document.getElementById('idConfigs');
                if (testSelect.value === '') {
                    alert('Por favor, selecciona un test para añadir.');
                    return false;
                } else if (prodSelect.value === '') {
                    alert('Por favor, selecciona un producto al que añadir el test.');
                    return false;
                }
                return true;
            }
            </script>
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