<!-- @Author: Blanca Atienzar Martinez -->
<?php session_start() ; 
include "conexion.php";
$conn = Conexion::getConnection(); 


        // PROCESO PARA ACTUALIZAR EL ESTADO DEL CONTRATO Y LOS MESES
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $idContracte = $_POST['update'];
    $nuevoEstat = $_POST['estat'][$idContracte];
    $nuevosMesos = $_POST['mesos'][$idContracte];
            
    // Consulta para actualizar el contrato
    $cadenaUpdate = "UPDATE CONTRACTE SET estat = '$nuevoEstat', mesos = '$nuevosMesos' WHERE idContracte = '$idContracte'";
    if (mysqli_query($conn, $cadenaUpdate)) {
        echo "<div class='alert alert-success' role='alert'>Contrato actualizado exitosamente.</div>";
    } else {
        echo "<div class='alert alert-danger' role='alert'>Error al actualizar el contrato: " . mysqli_error($conn) . "</div>";
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
                    Servicios PaaS - Contratos
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
                <form action="servicesPaaSPersonalContratosform.php" method="POST">
                    <!-- Tabla para mostrar los datos del contrato -->
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID Contracte</th>
                                <th>Data Inici</th>
                                <th>Estat</th>
                                <th>Nom Org</th>
                                <th>Email Usuari</th>
                                <th>ID Config Producte</th>
                                <th>Mesos</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Consulta para obtener los datos de la tabla CONTRACTE donde idConfigProducte 
                            // esté en la lista de idConfigs de la tabla PAAS
                            $cadenaContracte = "SELECT c.idContracte, c.dataInici, c.estat, c.nom, c.emailU, c.idConfigProducte, c.mesos
                                                FROM CONTRACTE c
                                                JOIN PAAS s ON c.idConfigProducte = s.idConfig";
                            $resultadoContracte = mysqli_query($conn, $cadenaContracte);
                            
                            // Consulta para obtener los valores de 'mesos' disponibles en la tabla 'durada'
                            $cadenaDurada = "SELECT mesos FROM durada";
                            $resultadoDurada = mysqli_query($conn, $cadenaDurada);
                            $mesosOptions = [];
                            while ($rowDurada = $resultadoDurada->fetch_assoc()) {
                                $mesosOptions[] = $rowDurada['mesos'];
                            }

                            if ($resultadoContracte->num_rows > 0) {
                                while ($rowContracte = $resultadoContracte->fetch_assoc()) {
                                    echo "<tr>
                                        <td>{$rowContracte['idContracte']}</td>
                                        <td>{$rowContracte['dataInici']}</td>
                                        <td>
                                            <select name='estat[{$rowContracte['idContracte']}]' class='form-control'>
                                                <option value='Actiu' " . ($rowContracte['estat'] == 'Actiu' ? 'selected' : '') . ">Actiu</option>
                                                <option value='Finalitzat' " . ($rowContracte['estat'] == 'Finalitzat' ? 'selected' : '') . ">Finalitzat</option>
                                                <option value='Cancel·lat' " . ($rowContracte['estat'] == 'Cancel·lat' ? 'selected' : '') . ">Cancel·lat</option>
                                            </select>
                                        </td>
                                        <td>{$rowContracte['nom']}</td>
                                        <td>{$rowContracte['emailU']}</td>
                                        <td>{$rowContracte['idConfigProducte']}</td>
                                        <td>
                                            <select name='mesos[{$rowContracte['idContracte']}]' class='form-control'>";
                                            foreach ($mesosOptions as $mesos) {
                                                echo "<option value='$mesos' " . ($rowContracte['mesos'] == $mesos ? 'selected' : '') . ">$mesos</option>";
                                            }
                                            echo "</select>
                                        </td>
                                        <td>
                                            <button type='submit' name='update' value='{$rowContracte['idContracte']}' class='btn btn-primary custom-btn'>Actualizar</button>
                                        </td>
                                        </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='8'>No hay contratos disponibles.</td></tr>";
                            }
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