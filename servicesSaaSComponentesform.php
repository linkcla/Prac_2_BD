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

    <section class="about_section layout_paddingAbout"  >
        <div class="container">
            <h2 class="text-uppercase">
                Servicios SaaS - COMPONENTES
            </h2>
            <form>
                <div class="container">
                    <button type="submit" class="btn btn-primary" formaction="servicesSaaSViewform.php">Atras</button>
                    <button type="submit" class="btn btn-primary" formaction="servicesSaaSContratosform.php">Contratos SaaS</button>
                    <button type="submit" class="btn btn-primary" formaction="servicesSaaSTestform.php">Test</button>
                    <button type="submit" class="btn btn-primary" formaction="servicesSaaSComponentesform.php">Componentes SaaS</button>
                </div>
            </form>
        </div>
    </section>

    <section class="about_section layout_paddingAbout">
        <div class="container">
            <h4 class="text-uppercase">
                Añadir Componentes
            </h4>
            <form id="componentForm" action="" method="POST">
                <input type="hidden" id="accio" name="accio" value="crearS">
                <div class="form-group">
                    <label for="component">Selecciona Componente</label>
                    <select name="component" id="component" class="form-control" onchange="this.form.submit()">
                        <option value="">Selecciona Componente</option>
                        <option value="CMS" <?php if(isset($_POST['component']) && $_POST['component'] == 'CMS') echo 'selected'; ?>>CMS</option>
                        <option value="CDN" <?php if(isset($_POST['component']) && $_POST['component'] == 'CDN') echo 'selected'; ?>>CDN</option>
                        <option value="SSL" <?php if(isset($_POST['component']) && $_POST['component'] == 'SSL') echo 'selected'; ?>>SSL</option>
                        <option value="SGBD" <?php if(isset($_POST['component']) && $_POST['component'] == 'SGBD') echo 'selected'; ?>>SGBD</option-->
                        <option value="RAM" <?php if(isset($_POST['component']) && $_POST['component'] == 'RAM') echo 'selected'; ?>>RAM</option>
                        <option value="DISC_DUR" <?php if(isset($_POST['component']) && $_POST['component'] == 'DISC_DUR') echo 'selected'; ?>>DISC DUR</option>
                    </select>
                </div>

                <!-- Formulario para añadir CMS -->                
                <?php if(isset($_POST['component']) && $_POST['component'] == 'CMS'): ?>
                <fieldset>
                    <legend> Modulo CMS</legend>
                    <div class="form-group">
                        <label for="tipo_cms">Tipo de Modulo CMS</label>
                        <input type="text" name="tipotipo" id="tipo_cms" class="form-control" placeholder="Ej: Joomla">
                    </div>
                </fieldset>
                <?php endif; ?>

                <!-- Formulario para añadir CDN -->                
                <?php  if(isset($_POST['component']) && $_POST['component'] == 'CDN'): ?>
                <fieldset>
                    <legend>CDN</legend>
                    <div class="form-group">
                        <label for="tipo_cdn">Tipo de CDN</label>
                        <select name="tipo" id="tipo_cdn" class="form-control">
                            <option value="Bàsic">Bàsic</option>
                            <option value="Protegit">Protegit</option>
                            <option value="Avançat">Avançat</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="precio_cdn">Precio</label>
                        <input type="text" name="precio" id="precio_cdn" class="form-control" placeholder="Max: 99.99">
                    </div>
                </fieldset>
                <?php endif; ?>

                <!-- Formulario para añadir SSL -->                
                <?php if(isset($_POST['component']) && $_POST['component'] == 'SSL'): ?>
                <fieldset>
                    <legend>SSL</legend>
                    <div class="form-group">
                        <label for="tipo_ssl">Tipo de SSL</label>
                        <select name="tipo" id="tipo_ssl" class="form-control">
                            <option value="Bàsic">Bàsic</option>
                            <option value="Professional">Professional</option>
                            <option value="Avançat">Avançat</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="precio_ssl">Precio</label>
                        <input type="text" name="precio" id="precio_ssl" class="form-control" placeholder="Max: 99.99">
                    </div>
                </fieldset>
                <?php endif; ?>

                <!-- Formulario para añadir SGBD -->                
                <?php if(isset($_POST['component']) && $_POST['component'] == 'SGBD'): ?>
                <fieldset>
                    <legend>SGBD</legend>
                    <div class="form-group">
                        <label for="tipo_sgbd">Tipo de SGBD</label>
                        <input type="text" name="tipotipo" id="tipo_sgbd" class="form-control" placeholder="Ej: PostgreSQL">
                    </div>
                </fieldset>
                <?php endif; ?>

                <!-- Formulario para añadir RAM -->                
                <?php 
                // Obtener tipos de RAM desde la base de datos
                $ramTypesQuery = "SELECT DISTINCT tipus FROM RAM";
                $ramTypesResult = mysqli_query($conn, $ramTypesQuery);
                $ramTypes = [];
                while ($row = mysqli_fetch_assoc($ramTypesResult)) {
                    $ramTypes[] = $row['tipus'];
                }
                
                if(isset($_POST['component']) && $_POST['component'] == 'RAM'): ?>
                <fieldset>
                    <legend>RAM</legend>
                    <div class="form-group">
                        <label for="tipo_ram">Tipo de RAM</label>
                        <select name="tipo" id="tipo_ram" class="form-control">
                            <option value="">Selecciona Tipo de Ram ya creada o crea una nueva</option>
                            <?php foreach ($ramTypes as $ramType): ?>
                                <option value="<?php echo $ramType; ?>"><?php echo $ramType; ?></option>
                            <?php endforeach; ?>
                        </select>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="tipo_ram">Nuevo tipo de Ram</label>
                            </div>
                            <div class="form-group">
                                <input type="text" name="tipotipo" id="tipo_ram" class="form-control" placeholder="Ej: PostgreSQL">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="gb_ram">GB de RAM</label>
                        <select name="gb" id="gb_ram" class="form-control">
                            <option value="4">4 GB</option>
                            <option value="8">8 GB</option>
                            <option value="16">16 GB</option>
                            <option value="32">32 GB</option>
                            <option value="64">64 GB</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="precio_ram">Precio</label>
                        <input type="text" name="precio" id="precio_ram" class="form-control" placeholder="Max: 99.99">
                    </div>
                </fieldset>
                <?php endif; ?>

                <!-- Formulario para añadir DISC DUR -->
                <?php if(isset($_POST['component']) && $_POST['component'] == 'DISC_DUR'): ?>
                <fieldset>
                    <legend>DISC DUR</legend>
                    <div class="form-group">
                        <label for="tipo_disc_dur">Tipo de DISC DUR</label>
                        <select name="tipo" id="tipo_disc_dur" class="form-control">
                            <option value="HDD">HDD</option>
                            <option value="SSD">SSD</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="gb_disc_dur">GB de DISC DUR</label>
                        <select name="gb" id="gb_disc_dur" class="form-control">
                            <option value="128">128 GB</option>
                            <option value="256">256 GB</option>
                            <option value="512">512 GB</option>
                            <option value="1024">1024 GB</option>
                            <option value="2048">2048 GB</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="precio_disc_dur">Precio</label>
                        <input type="text" name="precio" id="precio_disc_dur" class="form-control" placeholder="Max: 999.99">
                    </div>
                </fieldset>
                <?php endif; ?>

                <button type="submit" id="addComponentButton" class="btn btn-primary" >Añadir Componente</button>
            </form>
             <script>
                document.getElementById('componentForm').addEventListener('submit', function(event) {
                    let valid = true;
                    const component = document.getElementById('component').value;
                    if (!component) {
                        valid = false;
                        alert('Por favor, selecciona un componente.');
                    }

                    tipo = document.querySelector('select[name="tipo"]');
                    const tipotipo = document.querySelector('input[name="tipotipo"]');
                    const precio = document.querySelector('input[name="precio"]');
                    const gb = document.querySelector('select[name="gb"]');

                    if ((component === 'CMS' || component === 'SGBD') && (!tipotipo || !tipotipo.value)) {
                        valid = false;
                        alert('Por favor, ingresa el tipo.');
                    }

                    if (((component === 'CDN' || component === 'SSL')||(component === 'DISC_DUR' && (!gb.value))) && 
                        (!tipo || !tipo.value) && (!precio || !precio.value)) {
                        valid = false;
                        alert('Por favor, selecciona todos los campos.');
                    }

                    if (component === 'RAM'){
                        
                        if (!gb.value || !precio || !precio.value) {
                            valid = false;
                            alert('Por favor, la cantidad de GB y el precio.');
                        }else if (tipo && tipo.value && tipotipo && tipotipo.value) {
                            valid = false;
                            alert('Por favor, selecciona un tipo de RAM ya creado o crea uno, pero no los dos a la vez .');
                        }else if ((!tipo || !tipo.value) && (!tipotipo || !tipotipo.value)) {
                            valid = false;
                            alert('Por favor, selecciona un tipo de RAM.');
                        }
                    }

                    if (!valid) {
                        event.preventDefault();
                    } else {
                        this.action = './src/vista/componentesVista.php';
                    }
                });
            </script>
        </div>

    </section>
   
    <section class="about_section layout_paddingAbout">
        <div class="container">
            <h4 class="text-uppercase">
                Editar Precios/ Eliminar Componentes
            </h4>
        </div>
        <div class="container">
            <form action=" " method="POST" onsubmit="return validateForm(event)">
                <input type="hidden" name="accio" id="accio" value=" ">
                    <!-- Tabla para mostrar los datos de CONTRACTE -->
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Seleccionar</th>
                                <th>Componente</th>
                                <th>Tipo</th>
                                <th>Precio</th>
                                <th>GB</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Consultas para obtener datos de cada componente
                            $queries = [
                                "CMS" => "SELECT 'Modul CMS' as componente, tipus as tipo, '' as preu, '' as GB FROM MODUL_CMS",
                                "CDN" => "SELECT 'CDN' as componente, tipus as tipo, preu, '' as GB FROM CDN",
                                "SSL" => "SELECT 'Certificado SSL' as componente, tipus as tipo, preu, '' as GB FROM C_SSL",
                                "SGBD" => "SELECT 'Sistema de Gestion de Base de Datos' as componente, tipus as tipo, '' as preu, '' as GB FROM SIST_GESTIO_BD",
                                "RAM" => "SELECT 'RAM' as componente, tipus as tipo, preu, GB FROM RAM",
                                "DISC_DUR" => "SELECT 'Disco Duro' as componente, tipus as tipo, preu, GB FROM DISC_DUR"
                            ];
                            foreach ($queries as $query) {
                                $resultado = mysqli_query($conn, $query);
                                if (!$resultado) {
                                    die("Error al obtener datos: " . mysqli_error($conn));
                                }
                                while ($row = mysqli_fetch_assoc($resultado)) {
                                    $value = $row['componente'] . '|' . $row['tipo']. '|' . $row['preu']. '|' . $row['GB'];
                                        echo "<tr>
                                        <td>
                                            <input type='radio' id='selectedRow' name='selectedRow' value='{$value}'>
                                        </td>
                                        <td>{$row['componente']}</td>
                                        <td>{$row['tipo']}</td>
                                        <td>{$row['preu']}</td>
                                        <td>{$row['GB']}</td>
                                        </tr>";
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                    <div class="container d-flex justify-content-center align-items-center">
                        <div class="form-row align-items-center3">
                            <div class="col-auto2">
                                <label for="precio_ssl">Precio</label>
                            </div>
                            <div class="col-auto2">
                                <input type="text" name="precio" id="precio_ssl" class="form-control" placeholder="Max: 99.99">
                            </div>
                            <div class="col-auto2">
                                <button type="submit" class="btn btn-primary mb-3" name="edit">Actualizar Precio </button>
                                <button type="submit" class="btn btn-primary mb-3" name="elimar">Eliminar Componente</button>
                            </div>
                        </div>
                    </div>
            </form>
            <script>
            function validateForm(event) {
                const selectedRow = document.querySelector('input[name="selectedRow"]:checked');
                const precio = document.querySelector('input[name="precio"]');
                const buttonClicked = event.submitter.name;

                if (buttonClicked === 'edit') {
                    if (!selectedRow || !precio || !precio.value) {
                        alert('Por favor, selecciona un componente y un precio.');
                        return false;
                    } else {
                        document.getElementById('accio').value = 'editarS';
                    }
                } 
                if (buttonClicked === 'elimar') {
                    if (!selectedRow) {
                        alert('Por favor, selecciona un componente.');
                        return false;
                    } else {
                        document.getElementById('accio').value = 'eliminarS';
                    }
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



<!-- @Author: Blanca Atienzar Martinez (HTML, CSS y funcionalidad de SaaS) -->
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $selectedRow = $_POST['selectedRow'];
    list($componente, $tipo, $precio, $gb) = explode('|', $selectedRow);
    echo "Debug: eliminarSaaS - Componente: $componente, Tipo: $tipo, Precio: $precio, GB: $gb<br>";


    // if ($_POST['accio'] == 'editarS') {
        switch ($componente) {
            case 'Modul CMS':
                $query = "DELETE FROM MODUL_CMS WHERE tipus='$tipo'";
                break;
            case 'CDN':
                $query = "DELETE FROM CDN WHERE tipus='$tipo'";
                break;
            case 'Certificado SSL':
                $query = "DELETE FROM C_SSL WHERE tipus='$tipo'";
                break;
            case 'Sistema de Gestion de Base de Datos':
                $query = "DELETE FROM SIST_GESTIO_BD WHERE tipus='$tipo'";
                break;
            case 'RAM':
                $query = "DELETE FROM RAM WHERE tipus='$tipo'";
                break;
            case 'Disco Duro':
                $query = "DELETE FROM DISC_DUR WHERE tipus='$tipo'";
                break;
        }
        
        $resultado = mysqli_query($conn, $query);
        if (!$resultado) {
            $_SESSION["error_msg"] = "No se pudo eliminar el componente.";
            return false;
        }
        $_SESSION["success_msg"] = "Componente eliminado.";
        return true;
    // }
        
}
?>

</html>