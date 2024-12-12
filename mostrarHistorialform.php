<!-- @Author: Hai Zi Bibiloni Trobat -->

<?php session_start();
include "conexion.php";
$conn = Conexion::getConnection();

// Consulta SQL per obtenir les dades de la taula HISTORIAL
$sql_historial = "SELECT dia, canvis_realitzats, numero_canvis FROM HISTORIAL";
$result_historial = $conn->query($sql_historial);

// Consulta SQL per obtenir les dades de la tuala AUDITORIA
$sql_auditoria = "SELECT dia_hora_minut, taula, operacio, dades_anteriors, dades_noves FROM AUDITORIA";
$result_auditoria = $conn->query($sql_auditoria);
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
                        </div>
                    </div>
                </nav>
            </div>
        </header>
        <!-- end header section -->
    </div>

    <!-- about section -->
    <section class="about_section layout_paddingAbout" style="min-height: calc(100vh - 200px);">
        <div class="container">
            <h2 class="text-uppercase">
                Benvingut, <?php echo $_SESSION['nom']; ?> 
            </h2>         
            <br>   
            <button type="button" class="btn btn-primary" onclick="showTable('historial')">Historial</button>
            <button type="button" class="btn btn-primary" onclick="showTable('auditoria')">Auditoria</button>
            
            <div id="historialTable" style="display:none;">
                <br>
                <h3>Historial</h3>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Dia</th>
                            <th>Canvis realitzats</th>
                            <th>Nº canvis</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        if ($result_historial->num_rows > 0) {
                            while($row = $result_historial->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $row["dia"] . "</td>";
                                echo "<td>" . nl2br($row["canvis_realitzats"]) . "</td>";
                                echo "<td>" . $row["numero_canvis"] . "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='3'>No hi ha dades disponibles</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <div id="auditoriaTable" style="display:none;">
                <br>
                <h3>Auditoria</h3>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>id</th>
                            <th>Taula</th>
                            <th>Operacio</th>
                            <th>Dades anteriors</th>
                            <th>Dades noves</th>
                            <th>Data</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        if ($result_auditoria->num_rows > 0) {
                            while($row = $result_auditoria->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $row["dia_hora_minut"] . "</td>";
                                echo "<td>" . $row["taula"] . "</td>";
                                echo "<td>" . $row["operacio"] . "</td>";
                                echo "<td>" . $row["dades_anteriors"] . "</td>";
                                echo "<td>" . $row["dades_noves"] . "</td>";
                                echo "<td>" . $row["dia_hora_minut"] . "</td>";
                            }
                        } else {
                            echo "<tr><td colspan='5'>No hi ha dades disponibles</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <!-- footer section -->
    <section class="container-fluid footer_section">
        <p>
            &copy; 2024 (UIB - EPS). Design by MPHB
        </p>
    </section>
    <!-- footer section -->

    <script>
        function openNav() {
            document.getElementById("myNav").classList.toggle("menu_width");
            document
                .querySelector(".custom_menu-btn")
                .classList.toggle("menu_btn-style");
        }

        function showTable(tableId) {
            document.getElementById('historialTable').style.display = 'none';
            document.getElementById('auditoriaTable').style.display = 'none';
            document.getElementById(tableId + 'Table').style.display = 'block';
        }
    </script>
</body>

</html>