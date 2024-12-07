<!-- Author: Marc -->
<?php session_start() ;
require_once "conexion.php";
$conn = Conexion::getConnection(); 

// Agafar els valors que s'han seleccionat.
if (isset($_POST['selectedRow'])) {
    $valorSeleccionat = $_POST['selectedRow'];
    list($nom, $adreca, $telefon) = explode("|", $valorSeleccionat);
} else {
    $nom = $_SESSION['nomOrg'];
    unset($_SESSION['nomOrg']);
}

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
                                <a href="servicesPaaSfPersonalorm.php">PaaS</a>
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
                Gestionant l'organizació: <?php echo $nom; ?>
            </h2>
            <form>
                <div class="container">
                <div class="container">
                    <button type="submit" class="btn btn-primary" formaction="gestOrgForm.php">Tornar arrera</button>
                    <button type="submit" class="btn btn-primary" formaction="crearGrupForm.php">Crear grup</button>
                    <button type="submit" class="btn btn-primary" formaction="editarGrupForm.php">Editar grup</button>
                    <button type="submit" class="btn btn-primary" formaction="eliminarGrupForm.php">Eliminar grup</button>
                    <button type="submit" class="btn btn-primary" formaction="afegirUsuarisForm.php">Afegir usuaris</button>
                </div>
                </div>
            </form>
        </div>
    </section>

    <section>
        
        <form id="formulari" action="" method="post">
            
            <div class="container">
                <!-- Tabla para mostrar los datos de CONTRACTE -->
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Selecionar</th>
                            <th>Nom</th>
                            <th>Cognom</th>
                            <th>Email</th>
                            <th>Grup</th>
                            <th>Privilegis</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $_SESSION['nomOrg'] = $nom;
                        $cadenaUsuaris = "SELECT nom as Nom,
                                            cognom as Cognom,
                                            persona.email as email,
                                            usuaris.grup as Grup,
                                            GROUP_CONCAT(priv_de_grup.tipusPriv SEPARATOR ', ') AS Privilegis
                                        FROM (SELECT email, grup FROM usuari WHERE nomOrg = '{$nom}') as usuaris
                                            JOIN persona 
                                                ON usuaris.email = persona.email 
                                            JOIN priv_de_grup
                                                ON usuaris.grup = priv_de_grup.nomG
                                                GROUP BY persona.email, usuaris.grup";
                        
                        $resultadoUsuaris = mysqli_query($conn, $cadenaUsuaris);
                        
                        if (!$resultadoUsuaris) {
                            die("Error al obtener datos de las organizaciones: " . mysqli_error($conn));
                        }
                        
                        while ($rowUsuari = $resultadoUsuaris->fetch_assoc()) {
                            
                            echo "<tr>
                                <td>
                                    <input type='radio' name='selectedRow' id='emailUsuari' value='{$rowUsuari['email']}'>
                                </td>
                                <td>{$rowUsuari['Nom']}</td>
                                <td>{$rowUsuari['Cognom']}</td>
                                <td>{$rowUsuari['email']}</td>
                                <td>{$rowUsuari['Grup']}</td>
                                <td>{$rowUsuari['Privilegis']}</td>
                            </tr>";
                        }
                        ?>
                    </tbody>
                </table>
                <button type="button" class="btn btn-primary mt-3" id="botEliminar" name="action" value="delete">Eliminar de l'organització</button>
                <button type="button" class="btn btn-primary mt-3" id="botEditarP" name="action" value="delete">Editar permisos</button>
        </form>
        <script>
            const form = document.getElementById('formulari');
            const botEliminar = document.getElementById('botEliminar');
            const botEditarP = document.getElementById('botEditarP');

            // Función para verificar si se seleccionó un radio
            function isRadioSelected() {
                const radios = document.querySelectorAll('input[name="selectedRow"]');
                for (let i = 0; i < radios.length; i++) {
                    if (radios[i].checked) {
                        return true;  // Si al menos uno está seleccionado
                    }
                }
                return false;  // Ningún radio está seleccionado
            }

            botEliminar.addEventListener('click', function() {
                if (isRadioSelected()) {
                    form.action = 'eliminarUsDeOrg.php';
                    form.submit();
                } else {
                    alert('Por favor, seleccione una organización para borrar.');
                }
            });
            
            botEditarP.addEventListener('click', function() {
                if (isRadioSelected()) {
                    form.action = 'editarPermUsDeOrgForm.php';
                    form.submit();
                } else {
                    alert('Por favor, seleccione una organización para borrar.');
                }
            });

        </script>
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