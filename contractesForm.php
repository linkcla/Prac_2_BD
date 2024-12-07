<!-- @Author: Marc -->

<?php session_start();
include "conexion.php";
$conn = Conexion::getConnection();

$nomOrg = $_SESSION['nomOrg'];
$permisos = $_SESSION['permisos'];
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
        .table th.id-column, .table td.id-column {
            width: 15%;
        }
        .table th.name-column, .table td.name-column {
            width: 20%;
        }
        .table th.description-column, .table td.description-column {
            width: 65%;
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
                Benvingut, <?php echo $_SESSION['nom']; ?>
            </h2>
            <?php if (in_array('Visualizar', $permisos)): ?>
                <h3>Productes contratats per l'organització: <?php echo $nomOrg; ?></h3>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="id-column">ID Config</th>
                            <th class="name-column">Nom del Producte</th>
                            <th class="description-column">Descripció</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = "SELECT p.idConfig, p.nom, p.descripcio
                                  FROM CONTRACTE c 
                                  JOIN PRODUCTE p ON c.idConfigProducte = p.idConfig 
                                  WHERE c.nom = '$nomOrg'";
                        $result = mysqli_query($conn, $query);
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>
                                    <td>{$row['idConfig']}</td>
                                    <td>{$row['nom']}</td>
                                    <td>{$row['descripcio']}</td>
                                  </tr>";
                        }
                        ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No tens permisos per visualitzar els productes contractats.</p>
            <?php endif; ?>
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
    </script>
</body>

</html>