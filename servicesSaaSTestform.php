<!-- @Author: Blanca Atienzar Martinez (HTML y CSS) -->

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
                                <a href="servicesSaaSPersonalform.php">SaaS</a>
                            </div>
                            <div class="overlay-content">
                                <a href="servicesPaaSPersonalform.php">PaaS</a>
                            </div>  
                            <div class="overlay-content">
                                <a href="gestOrg.php">Gestionar Organitzacións</a>
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
                    <button type="submit" class="btn btn-primary" formaction="servicesSaaSPersonalform.php">Contratos SaaS</button>
                    <button type="submit" class="btn btn-primary" formaction="servicesSaaSViewform.php">Visualizar</button>
                    <button type="submit" class="btn btn-primary" formaction="servicesSaaSEditform.php" >Editar</button>
                    <button type="submit" class="btn btn-primary" formaction="servicesSaaSCreateform.php">Crear</button>
                    <button type="submit" class="btn btn-primary" formaction="servicesSaaSDeleteform.php">Eliminar</button>
                    <button type="submit" class="btn btn-primary" formaction="servicesSaaSTestform.php">Test</button>
                </div>
            </form>
            
                <div class="card p-4" style="width: 100%;">

                    <div class="container d-flex justify-content-center align-items-center">
                        <form action="" method="POST">
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
                    <div class="container d-flex justify-content-center align-items-center2">
                    <form action="" method="POST">
                        <div class="form-row align-items-center2">
                            <div class="col-auto1">
                                    <?php
                                    $testOptions = [];
                                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                                        if (isset($_POST['noms'])) {
                                            $test = $_POST['noms'];
                                        } else {
                                            $test = '';
                                        }
                                        if ($test !== '') {
                                            $cadena = "SELECT * FROM TEST WHERE nom = '$test'";
                                            $resultado = mysqli_query($conn, $cadena);
                                            while ($row = $resultado->fetch_assoc()) {
                                                $testOptions[] = $row;
                                            }
                                        }
                                    }
                                    ?>
                                
                                    <select name="noms" id="noms" class="form-control" onchange="this.form.submit()">
                                        <option value="">Selecciona Test a Eliminar</option>
                                        <?php
                                            $cadena = "SELECT DISTINCT nom FROM TEST";
                                            $resultado = mysqli_query($conn, $cadena);
                                            while ($row = $resultado->fetch_assoc()) {
                                                $selected = '';
                                                if (isset($test)) {
                                                    if ($row['nom'] === $tests) {
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
               
                <?php
                    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['createTest'])) {
                        // Procesar creación de nuevo test
                        $testName = $_POST['testName'];
                        $testDescription = $_POST['testDescription'];
                        $currentDate = date('Y-m-d H:i:s');

                        // Verificar si el test ya existe
                        $select_check_Query = "SELECT nom FROM TEST WHERE nom = '$testName'";
                        $result_test = mysqli_query($conn, $select_check_Query);

                        if (!$result_test){
                            echo "<script type='text/javascript'>alert('$message'); window.location.href='servicesSaaSTestform.php';</script>";
                            die("Error al verificar la existencia del usuario: " . mysqli_error($conn));
                        }

                        if(mysqli_num_rows($result_test) == 0) {
                            // Insertar el nuevo test
                            $insertQuery = "INSERT INTO TEST (nom, descripció, dataCreacio)
                                VALUES ('$testName', '$testDescription', '$currentDate')";
                            
                            if(mysqli_query($conn, $insertQuery) == false) {
                                $message = "Error al crear el test.";
                                echo "<script type='text/javascript'>alert('$message'); window.location.href='servicesSaaSTestform.php';</script>";
                            };

                            $message = "Test creat.";
                            echo "<script type='text/javascript'>alert('$message'); window.location.href='servicesSaaSTestform.php';</script>";
                            die($message);
                        }else {
                            $message = "Error. Test ja creat.";
                            echo "<script type='text/javascript'>alert('$message'); window.location.href='servicesSaaSTestform.php';</script>";
                            die($message);
                        }
                    }

                    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['deleteTest'])) {
                        // Procesar creación de nuevo test
                        $testName = $_POST['testName'];

                        // Verificar si el test ya existe
                        $select_check_Query = "SELECT nom FROM TEST WHERE nom = '$testName'";
                        $result_test = mysqli_query($conn, $select_check_Query);

                        if (!$result_test){
                            echo "<script type='text/javascript'>alert('$message'); window.location.href='servicesSaaSTestform.php';</script>";
                            die("Error al verificar la existencia del usuario: " . mysqli_error($conn));
                        }

                        if(mysqli_num_rows($result_test) == 0) {
                            // Insertar el nuevo test
                            $insertQuery = "INSERT INTO TEST (nom, descripció, dataCreacio)
                                VALUES ('$testName', '$testDescription', '$currentDate')";
                            
                            if(mysqli_query($conn, $insertQuery) == false) {
                                $message = "Error al crear el test.";
                                echo "<script type='text/javascript'>alert('$message'); window.location.href='servicesSaaSTestform.php';</script>";
                            };

                            $message = "Test creat.";
                            echo "<script type='text/javascript'>alert('$message'); window.location.href='servicesSaaSTestform.php';</script>";
                            die($message);
                        }else {
                            $message = "Error. Test ja creat.";
                            echo "<script type='text/javascript'>alert('$message'); window.location.href='servicesSaaSTestform.php';</script>";
                            die($message);
                        }
                    }
                ?>
            </form>
        </div>
        <div class="container">
            <form action=" " method="POST">
                <!-- Tabla para mostrar los datos de CONTRACTE -->
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID Configuración</th>
                            <th>Nombre del Test</th>
                            <th>Estado del Test</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php

                        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['updateState'])) {
                            // Procesar actualización de estado
                            $idConfig = $_POST['idConfig'];
                            $nomT = $_POST['nomT'];
                            $newState = $_POST['newState'];

                            $updateQuery = "
                                UPDATE ESTAT
                                SET estat = ?
                                WHERE idConfigProducte = ? AND nomT = ?
                            ";
                            $stmt = $conn->prepare($updateQuery);
                            $stmt->bind_param("sis", $newState, $idConfig, $nomT);

                            if ($stmt->execute()) {
                                echo "<div class='alert alert-success'>Estado actualizado correctamente</div>";
                            } else {
                                echo "<div class='alert alert-danger'>Error al actualizar: " . $stmt->error . "</div>";
                            }

                            $stmt->close();
                        }

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
                            echo "<tr>
                                <td>{$rowContracte['idConfig']}</td>
                                <td>{$rowContracte['testNom']}</td>
                                <td>{$rowContracte['testEstat']}</td>
                            </tr>";
                        }

                        // Cerrar la conexión
                        mysqli_close($conn);
                        ?>
                    </tbody>
                </table>
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