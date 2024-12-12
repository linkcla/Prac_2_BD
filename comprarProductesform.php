<!-- @Author: Blanca Atienzar Martinez (HTML y CSS) -->
<!-- @Author: Hai Zi Bibiloni Trobat (PHP y funcionalidad) -->

<?php session_start() ;
include "conexion.php";
$conn = Conexion::getConnection();  

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['email'])) {
    header("Location: loginform.php");
    exit();
}

$email = $_SESSION['email'];

// Obtener el nombre de la organización del usuario y sus privilegios
$sql = "SELECT p.nom, p.cognom, u.nomOrg, pdg.tipusPriv FROM USUARI u
        JOIN PERSONA p ON u.email = p.email
        JOIN PRIV_DE_GRUP pdg ON u.grup = pdg.nomG
        WHERE u.email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $adminName = $row['nom'] . " " . $row['cognom'] . " - ";
    $nomOrg = $row['nomOrg'];
    $privilegios = $row['tipusPriv'];
} else {
    $adminName = "";
    $nomOrg = "Sin organización";
    $privilegios = "";
}

// Obtener los datos de las tablas correspondientes
$tipusMCMS = array_unique($conn->query("SELECT DISTINCT tipus FROM MODUL_CMS")->fetch_all(MYSQLI_ASSOC), SORT_REGULAR);
$tipusCDN = array_unique($conn->query("SELECT DISTINCT tipus, preu FROM CDN")->fetch_all(MYSQLI_ASSOC), SORT_REGULAR);
$tipusSSL = array_unique($conn->query("SELECT DISTINCT tipus, preu FROM C_SSL")->fetch_all(MYSQLI_ASSOC), SORT_REGULAR);
$tipusSGBD = array_unique($conn->query("SELECT DISTINCT tipus FROM SIST_GESTIO_BD")->fetch_all(MYSQLI_ASSOC), SORT_REGULAR);
$tipusRAM = array_unique($conn->query("SELECT DISTINCT tipus, preu FROM RAM")->fetch_all(MYSQLI_ASSOC), SORT_REGULAR);
$gbRAM = array_unique($conn->query("SELECT DISTINCT GB, preu FROM RAM")->fetch_all(MYSQLI_ASSOC), SORT_REGULAR);
$tipusDD = array_unique($conn->query("SELECT DISTINCT tipus, preu FROM DISC_DUR")->fetch_all(MYSQLI_ASSOC), SORT_REGULAR);
$gbDD = array_unique($conn->query("SELECT DISTINCT GB, preu FROM DISC_DUR")->fetch_all(MYSQLI_ASSOC), SORT_REGULAR);
$modelCPU = array_unique($conn->query("SELECT DISTINCT model, preu FROM CPU")->fetch_all(MYSQLI_ASSOC), SORT_REGULAR);
$nNuclis = array_unique($conn->query("SELECT DISTINCT nNuclis, preu FROM CPU")->fetch_all(MYSQLI_ASSOC), SORT_REGULAR);
$nomSO = array_unique($conn->query("SELECT DISTINCT nom, preu FROM SO")->fetch_all(MYSQLI_ASSOC), SORT_REGULAR);
$ipv4Options = $conn->query("SELECT DISTINCT iPv4 FROM PAAS WHERE iPv4 IS NOT NULL")->fetch_all(MYSQLI_ASSOC);
$ipv6Options = $conn->query("SELECT DISTINCT iPv6 FROM PAAS WHERE iPv6 IS NOT NULL")->fetch_all(MYSQLI_ASSOC);
$mesosOptions = $conn->query("SELECT DISTINCT mesos FROM DURADA")->fetch_all(MYSQLI_ASSOC);

// Función para eliminar duplicados
function uniqueOptions($array, $key) {
    $tempArray = [];
    foreach ($array as $item) {
        $tempArray[$item[$key]] = $item;
    }
    return array_values($tempArray);
}

// Eliminar duplicados
$tipusMCMS = uniqueOptions($tipusMCMS, 'tipus');
$tipusCDN = uniqueOptions($tipusCDN, 'tipus');
$tipusSSL = uniqueOptions($tipusSSL, 'tipus');
$tipusSGBD = uniqueOptions($tipusSGBD, 'tipus');
$tipusRAM = uniqueOptions($tipusRAM, 'tipus');
$gbRAM = uniqueOptions($gbRAM, 'GB');
$tipusDD = uniqueOptions($tipusDD, 'tipus');
$gbDD = uniqueOptions($gbDD, 'GB');
$modelCPU = uniqueOptions($modelCPU, 'model');
$nNuclis = uniqueOptions($nNuclis, 'nNuclis');
$nomSO = uniqueOptions($nomSO, 'nom');

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
                                <a href="servicesUsuariform.php">Perfil</a>
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
                Productes
            </h2>
            <button type="button" class="btn btn-primary" onclick="window.location.href='iniciUsuariform.php'">Tornar enrera</button>            
            <?php
            if (isset($_SESSION['success_msg'])) {
                echo "<div class='alert alert-success' role='alert'>{$_SESSION['success_msg']}</div>";
                unset($_SESSION['success_msg']);
            }
            if (isset($_SESSION['error_msg'])) {
                echo "<div class='alert alert-danger' role='alert'>{$_SESSION['error_msg']}</div>";
                unset($_SESSION['error_msg']);
            }
            if (isset($_SESSION['info_msg'])) {
                echo "<div class='alert alert-info' role='alert'>{$_SESSION['info_msg']}</div>";
                unset($_SESSION['info_msg']);
            }
            ?>

        <!-- Tabla de productos SaaS -->
<div class="mt-4">
    <h1>Productos SaaS</h1>
    <form action="./src/vista/comprarProductesVista.php" method="POST" id="productFormSaaS" onsubmit="return validateForm('productFormSaaS')">
    <input type="hidden" name="accio" value="comprarSaaS">
    <table class="table table-bordered table-large-sa">
        <thead>
            <tr>
                <th>Tipus MCMS</th>
                <th>Tipus CND</th>
                <th>Tipus SSL</th>
                <th>Tipus SGBD</th>
                <th>Tipus RAM</th>
                <th>GB RAM </th>
                <th>Tipus DD</th>
                <th>GB DD</th>
                <th>Domini</th>
                <th>Mesos</th>
                <th>Preu (€)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <select name="tipusMCMS" class="form-control" onchange="calculatePriceSaaS()" onclick="showOptions(this)">
                        <option value="" disabled selected>Selecciona una opción</option>
                        <?php foreach ($tipusMCMS as $item): ?>
                            <option value="<?php echo ($item['tipus']); ?>" data-precio="0"><?php echo ($item['tipus']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td>
                    <select name="tipusCDN" class="form-control" onchange="calculatePriceSaaS()" onclick="showOptions(this)">
                        <option value="" disabled selected>Selecciona una opción</option>
                        <?php foreach ($tipusCDN as $item): ?>
                            <option value="<?php echo ($item['tipus']); ?>" data-precio="<?php echo ($item['preu']); ?>"><?php echo ($item['tipus']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td>
                    <select name="tipusSSL" class="form-control" onchange="calculatePriceSaaS()" onclick="showOptions(this)">
                        <option value="" disabled selected>Selecciona una opción</option>
                        <?php foreach ($tipusSSL as $item): ?>
                            <option value="<?php echo ($item['tipus']); ?>" data-precio="<?php echo ($item['preu']); ?>"><?php echo ($item['tipus']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td>
                    <select name="tipusSGBD" class="form-control">
                        <option value="" disabled selected>Selecciona una opción</option>
                        <?php foreach ($tipusSGBD as $item): ?>
                            <option value="<?php echo ($item['tipus']); ?>"><?php echo ($item['tipus']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td>
                    <select name="tipusRAM" class="form-control" onchange="calculatePriceSaaS()" onclick="showOptions(this)">
                        <option value="" disabled selected>Selecciona una opción</option>
                        <?php foreach ($tipusRAM as $item): ?>
                            <option value="<?php echo ($item['tipus']); ?>" data-precio="<?php echo ($item['preu']); ?>"><?php echo ($item['tipus']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td>
                    <select name="gbRAM" class="form-control" onchange="calculatePriceSaaS()" onclick="showOptions(this)">
                        <option value="" disabled selected>Selecciona una opción</option>
                        <?php foreach ($gbRAM as $item): ?>
                            <option value="<?php echo ($item['GB']); ?>" data-precio="<?php echo ($item['preu']); ?>"><?php echo ($item['GB']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td>
                    <select name="tipusDD" class="form-control" onchange="calculatePriceSaaS()" onclick="showOptions(this)">
                        <option value="" disabled selected>Selecciona una opción</option>
                        <?php foreach ($tipusDD as $item): ?>
                            <option value="<?php echo ($item['tipus']); ?>" data-precio="<?php echo ($item['preu']); ?>"><?php echo ($item['tipus']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td>
                     <select name="gbDD" class="form-control" onchange="calculatePriceSaaS()" onclick="showOptions(this)">
                        <option value="" disabled selected>Selecciona una opción</option>
                        <?php foreach ($gbDD as $item): ?>
                            <option value="<?php echo ($item['GB']); ?>" data-precio="<?php echo ($item['preu']); ?>"><?php echo ($item['GB']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td>
                    <input type="text" name="domini" class="form-control" placeholder="Ej: hola.com">
                </td>
                <td>
                    <select name="mesos" class="form-control" onchange="calculatePriceSaaS()">
                        <option value="" disabled selected>Selecciona una opción</option>
                        <?php foreach ($mesosOptions as $option): ?>
                            <option value="<?php echo $option['mesos']; ?>"><?php echo $option['mesos']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td>
                    <input type="text" name="precio" class="form-control" id="precioTotalSaaS" readonly>
                </td>
            </tr>           
        </tbody>
    </table>
        <button type="submit" class="btn btn-primary">Comprar</button>
    </form>
</div>

<!-- Tabla de productos PaaS -->
<div class="mt-4">
    <h1>Productos PaaS</h1>
    <form action="./src/vista/comprarProductesVista.php" method="POST" id="productFormPaaS" onsubmit="return validateForm('productFormPaaS')">
    <input type="hidden" name="accio" value="comprarPaaS">
    <table class="table table-bordered table-large-sa">
        <thead>
            <tr>
                <th>IP</th>
                <th>nº IP</th>
                <th>Tipus RAM</th>
                <th>GB RAM </th>
                <th>Tipus DD</th>
                <th>GB DD</th>
                <th>Model CPU</th>
                <th>nNuclis</th>
                <th>Nom SO</th>  
                <th>Mesos</th> 
                <th>Preu (€)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <select name="ip" class="form-control" onchange="updateIpOptions(this)">
                        <option value="" disabled selected>Selecciona una opción</option>
                        <option value="IPv4">IPv4</option>
                        <option value="IPv6">IPv6</option>
                    </select>
                </td>
                <td>
                    <select name="ipValue" class="form-control mt-2">
                        <option value="" disabled selected>Selecciona una opción</option>
                        <?php foreach ($ipv4Options as $option): ?>
                            <option class="IPv4" value="<?php echo $option['iPv4']; ?>"><?php echo $option['iPv4']; ?></option>
                        <?php endforeach; ?>
                        <?php foreach ($ipv6Options as $option): ?>
                            <option class="IPv6" value="<?php echo $option['iPv6']; ?>"><?php echo $option['iPv6']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td>
                    <select name="tipusRAM" class="form-control" onchange="calculatePricePaaS()" onclick="showOptions(this)">
                        <option value="" disabled selected>Selecciona una opción</option>
                        <?php foreach ($tipusRAM as $item): ?>
                            <option value="<?php echo ($item['tipus']); ?>" data-precio="<?php echo ($item['preu']); ?>"><?php echo ($item['tipus']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td>
                    <select name="gbRAM" class="form-control" onchange="calculatePricePaaS()" onclick="showOptions(this)">
                        <option value="" disabled selected>Selecciona una opción</option>
                        <?php foreach ($gbRAM as $item): ?>
                            <option value="<?php echo ($item['GB']); ?>" data-precio="<?php echo ($item['preu']); ?>"><?php echo ($item['GB']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td>
                    <select name="tipusDD" class="form-control" onchange="calculatePricePaaS()" onclick="showOptions(this)">
                        <option value="" disabled selected>Selecciona una opción</option>
                        <?php foreach ($tipusDD as $item): ?>
                            <option value="<?php echo ($item['tipus']); ?>" data-precio="<?php echo ($item['preu']); ?>"><?php echo ($item['tipus']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td>
                    <select name="gbDD" class="form-control" onchange="calculatePricePaaS()" onclick="showOptions(this)">
                        <option value="" disabled selected>Selecciona una opción</option>
                        <?php foreach ($gbDD as $item): ?>
                            <option value="<?php echo ($item['GB']); ?>" data-precio="<?php echo ($item['preu']); ?>"><?php echo ($item['GB']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td>
                    <select name="modelCPU" class="form-control" onchange="calculatePricePaaS()" onclick="showOptions(this)">
                        <option value="" disabled selected>Selecciona una opción</option>
                        <?php foreach ($modelCPU as $item): ?>
                            <option value="<?php echo ($item['model']); ?>" data-precio="<?php echo ($item['preu']); ?>"><?php echo ($item['model']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td>
                    <select name="nNuclis" class="form-control" onchange="calculatePricePaaS()" onclick="showOptions(this)">
                        <option value="" disabled selected>Selecciona una opción</option>
                        <?php foreach ($nNuclis as $item): ?>
                            <option value="<?php echo ($item['nNuclis']); ?>" data-precio="<?php echo ($item['preu']); ?>"><?php echo ($item['nNuclis']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td>
                    <select name="nomSO" class="form-control" onchange="calculatePricePaaS()" onclick="showOptions(this)">
                        <option value="" disabled selected>Selecciona una opción</option>
                        <?php foreach ($nomSO as $item): ?>
                            <option value="<?php echo ($item['nom']); ?>" data-precio="<?php echo ($item['preu']); ?>"><?php echo ($item['nom']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td>
                    <select name="mesos" class="form-control" onchange="calculatePricePaaS()">
                        <option value="" disabled selected>Selecciona una opción</option>
                        <?php foreach ($mesosOptions as $option): ?>
                            <option value="<?php echo $option['mesos']; ?>"><?php echo $option['mesos']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td>
                    <input type="text" name="preu" class="form-control" id="precioTotalPaaS" readonly>
                </td>
            </tr>
        </tbody>
    </table>
        <button type="submit" class="btn btn-primary">Comprar</button>
    </form>
</div>
    
        <div class="container">
            <div class="row d-flex">
                <!-- Columna izquierda: Formulario de selección -->
                <div class="col-md-8">
                    <form action="servicesSaaSform.php" method="POST">                    
                    <!-- añadir más componentes -->                    
                    </form>
                </div>
                <!-- Columna derecha: Resumen de selección -->
                <div class="col-md-4">
                    <div class="sticky-top">
                       
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

        function calculatePricePaaS() {
            let total = 0;
            const selects = document.querySelectorAll('#productFormPaaS select');
            selects.forEach(select => {
                const selectedOption = select.options[select.selectedIndex];
                const precio = parseFloat(selectedOption.getAttribute('data-precio')) || 0;
                total += precio;
            });
            const meses = parseInt(document.querySelector('#productFormPaaS select[name="mesos"]').value) || 1;
            total *= meses;
            document.getElementById('precioTotalPaaS').value = total.toFixed(2) + ' €';
        }

        function calculatePriceSaaS() {
        let total = 0;
        const selects = document.querySelectorAll('#productFormSaaS select');
        selects.forEach(select => {
            const selectedOption = select.options[select.selectedIndex];
            const precio = parseFloat(selectedOption.getAttribute('data-precio')) || 0;
            total += precio;
        });
        const meses = parseInt(document.querySelector('#productFormSaaS select[name="mesos"]').value) || 1;
        total *= meses;
        document.getElementById('precioTotalSaaS').value = total.toFixed(2) + ' €';
    }

    function updateIpOptions(select) {
        var ipType = select.value;
        var ipValueSelect = document.querySelector('select[name="ipValue"]');
        var options = ipValueSelect.querySelectorAll('option');

        options.forEach(function(option) {
            if (option.classList.contains(ipType)) {
                option.style.display = 'block';
            } else {
                option.style.display = 'none';
            }
        });

        ipValueSelect.value = '';
    }

        // Llamar a las funciones de cálculo al cargar la página para inicializar los precios
        window.onload = function() {
            calculatePricePaaS();
            calculatePriceSaaS();
        };

        function validateForm(formId) {
            const form = document.getElementById(formId);
            const selects = form.querySelectorAll('select');
            for (let select of selects) {
                if (select.value === "") {
                    alert('Por favor, selecciona una opción en todos los campos.');
                    return false;
                }
            }
            return true;
        }

    </script>
</body>
</html>