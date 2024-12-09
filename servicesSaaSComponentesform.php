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


// Verificar si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['component'])) {
        $component = $_POST['component'];
        $tipo = isset($_POST['tipo']) ? $_POST['tipo'] : null;
        $gb = isset($_POST['gb']) ? $_POST['gb'] : null;
        $precio = isset($_POST['precio']) ? $_POST['precio'] : null;

        // Verificar que los campos obligatorios no estén vacíos
        if ($tipo && $precio) {
            // Validar que el precio sea un número válido
            if (!is_numeric($precio) || $precio < 0 || $precio > 999.99) {
                echo "<div class='alert alert-danger' role='alert'>El precio debe ser un número entre 0 y 999.99</div>";
            } else {
                $query = "";
                $existsQuery = "";

                // Construir las consultas SQL según el tipo de componente
                switch ($component) {
                    case 'CMS':
                        if ($gb) {
                            $existsQuery = "SELECT * FROM MODUL_CMS WHERE tipus = '$tipo'";
                            $query = "INSERT INTO MODUL_CMS (tipus, GB, preu) VALUES ('$tipo')";
                        }
                        break;
                    case 'CDN':
                        if ($gb) {
                            $existsQuery = "SELECT * FROM CDN WHERE tipus = '$tipo";
                            $query = "INSERT INTO CDN (tipus, preu) VALUES ('$tipo', $precio)";
                        }
                        break;
                    case 'SSL':
                        if ($gb) {
                            $existsQuery = "SELECT * FROM C_SSL WHERE tipus = '$tipo'";
                            $query = "INSERT INTO C_SSL (tipus, preu) VALUES ('$tipo', $precio)";
                        }
                        break;
                    case 'SGBD':
                        if ($gb) {
                            $existsQuery = "SELECT * FROM SIST_GESTIO_BD WHERE tipus = '$tipo'";
                            $query = "INSERT INTO SIST_GESTIO_BD (tipus,) VALUES ('$tipo')";
                        }
                        break;
                    case 'RAM':
                        if ($gb) {
                            $existsQuery = "SELECT * FROM RAM WHERE tipus = '$tipo' AND GB = $gb";
                            $query = "INSERT INTO RAM (tipus, GB, preu) VALUES ('$tipo', $gb, $precio)";
                        }
                        break;
                    case 'DISC_DUR':
                        if ($gb) {
                            $existsQuery = "SELECT * FROM DISC_DUR WHERE tipus = '$tipo' AND GB = $gb";
                            $query = "INSERT INTO DISC_DUR (tipus, GB, preu) VALUES ('$tipo', $gb, $precio)";
                        }
                        break;
                }

                // Verificar si el componente ya existe en la base de datos
                if ($existsQuery) {
                    $existsResult = mysqli_query($conn, $existsQuery);
                    if (mysqli_num_rows($existsResult) > 0) {
                        echo "<div class='alert alert-danger' role='alert'>El componente ya existe.</div>";
                    } else {
                        // Insertar el nuevo componente en la base de datos 
                        if (mysqli_query($conn, $query)) {
                            echo "<div class='alert alert-success' role='alert'>Componente añadido exitosamente.</div>";
                        } else {
                            echo "<p>Error al añadir el componente: " . mysqli_error($conn) . "</p>";
                        }
                    }
                }
            }
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new_cms'])) {
    $tipo = $_POST['tipo1'];

    if ($tipo) {
        $query = "INSERT INTO MODUL_CMS (tipus) VALUES ('$tipo')";
        if (mysqli_query($conn, $query)) {
            echo "<div class='alert alert-success' role='alert'>Nuevo modulo CMS añadido exitosamente.</div>";
        } else {
            echo "<div class='alert alert-danger' role='alert'>Error al añadir el nuevo modulo CMS: " . mysqli_error($conn) . "</div>";
        }
    } else {
        echo "<div class='alert alert-warning' role='alert'>El campo modulo CMS es obligatorio.</div>";
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new_sgbd'])) {
    $tipo = $_POST['tipo'];

    if ($tipo) {
        $query = "INSERT INTO SIST_GESTIO_BD (tipus) VALUES ('$tipo')";
        if (mysqli_query($conn, $query)) {
            echo "<div class='alert alert-success' role='alert'>Nuevo tipo de RAM añadido exitosamente.</div>";
        } else {
            echo "<div class='alert alert-danger' role='alert'>Error al añadir el nuevo tipo de RAM: " . mysqli_error($conn) . "</div>";
        }
    } else {
        echo "<div class='alert alert-warning' role='alert'>El campo Tipo de RAM es obligatorio.</div>";
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new_ram'])) {
    $tipo = $_POST['tipo'];

    if ($tipo) {
        $query = "INSERT INTO RAM (tipus, GB, preu) VALUES ('$tipo', 0, 0)";
        if (mysqli_query($conn, $query)) {
            echo "<div class='alert alert-success' role='alert'>Nuevo tipo de RAM añadido exitosamente.</div>";
        } else {
            echo "<div class='alert alert-danger' role='alert'>Error al añadir el nuevo tipo de RAM: " . mysqli_error($conn) . "</div>";
        }
    } else {
        echo "<div class='alert alert-warning' role='alert'>El campo Tipo de RAM es obligatorio.</div>";
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
        <div class="container">
            <h4 class="text-uppercase">
                Añadir Componentes
            </h4>
            <form action=" " method="POST">
                <div class="form-group">
                    <label for="component">Selecciona Componente</label>
                    <select name="component" id="component" class="form-control" onchange="this.form.submit()">
                        <option value="">Selecciona Componente</option>
                        <option value="CMS" <?php if(isset($_POST['component']) && $_POST['component'] == 'CMS') echo 'selected'; ?>>Modulo CMS</option>
                        <option value="CDN" <?php if(isset($_POST['component']) && $_POST['component'] == 'CDN') echo 'selected'; ?>>CDN</option>
                        <option value="SSL" <?php if(isset($_POST['component']) && $_POST['component'] == 'SSL') echo 'selected'; ?>>SSL</option>
                        <option value="SGBD" <?php if(isset($_POST['component']) && $_POST['component'] == 'SGBD') echo 'selected'; ?>>SGBD</option>
                        <option value="RAM" <?php if(isset($_POST['component']) && $_POST['component'] == 'RAM') echo 'selected'; ?>>RAM</option>
                        <option value="DISC_DUR" <?php if(isset($_POST['component']) && $_POST['component'] == 'DISC_DUR') echo 'selected'; ?>>DISC DUR</option>
                    </select>
                </div>

                <!-- Formulario para añadir CMS -->                
                <?php 
                // Obtener tipos de CMS desde la base de datos
                $cmsTypesQuery = "SELECT DISTINCT tipus FROM MODUL_CMS";
                $cmsTypesResult = mysqli_query($conn, $cmsTypesQuery);
                $cmsTypes = [];
                while ($row = mysqli_fetch_assoc($cmsTypesResult)) {
                    $cmsTypes[] = $row['tipus'];
                }
                if(isset($_POST['component']) && $_POST['component'] == 'CMS'): ?>
                <fieldset>
                    <legend>Modulo CMS</legend>
                    <div class="form-group">
                        <label for="tipo_cms">Tipo de CMS</label>
                        <select name="tipo" id="tipo_cms" class="form-control">
                            <?php foreach ($cmsTypes as $cmsType): ?>
                                <option value="<?php echo $cmsType; ?>"><?php echo $cmsType; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </fieldset>
                <?php endif; ?>

                <!-- Formulario para añadir CDN -->                
                <?php 
                // Obtener tipos de CDN desde la base de datos
                $cdnTypesQuery = "SELECT DISTINCT tipus, preu FROM CDN";
                $cdnTypesResult = mysqli_query($conn, $cdnTypesQuery);
                $cdnTypes = [];
                while ($row = mysqli_fetch_assoc($cdnTypesResult)) {
                    $cdnTypes[] = $row['tipus'];
                }
                
                if(isset($_POST['component']) && $_POST['component'] == 'CDN'): ?>
                <fieldset>
                    <legend>CDN</legend>
                    <div class="form-group">
                        <label for="tipo_cdn">Tipo de CDN</label>
                        <select name="tipo" id="tipo_cdn" class="form-control">
                            <?php foreach ($cdnTypes as $cdnType): ?>
                                <option value="<?php echo $cdnType; ?>"><?php echo $cdnType; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="precio_cdn">Precio</label>
                        <input type="text" name="precio" id="precio_cdn" class="form-control" placeholder="Max: 99.99">
                    </div>
                </fieldset>
                <?php endif; ?>

                <!-- Formulario para añadir SSL -->                
                <?php 
                // Obtener tipos de SSL desde la base de datos
                $sslTypesQuery = "SELECT DISTINCT tipus, preu FROM C_SSL";
                $sslTypesResult = mysqli_query($conn, $sslTypesQuery);
                $sslTypes = [];
                while ($row = mysqli_fetch_assoc($sslTypesResult)) {
                    $sslTypes[] = $row['tipus'];
                }
                
                if(isset($_POST['component']) && $_POST['component'] == 'SSL'): ?>
                <fieldset>
                    <legend>SSL</legend>
                    <div class="form-group">
                        <label for="tipo_ssl">Tipo de SSL</label>
                        <select name="tipo" id="tipo_ssl" class="form-control">
                            <?php foreach ($sslTypes as $sslType): ?>
                                <option value="<?php echo $sslType; ?>"><?php echo $sslType; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="precio_ssl">Precio</label>
                        <input type="text" name="precio" id="precio_ssl" class="form-control" placeholder="Max: 99.99">
                    </div>
                </fieldset>
                <?php endif; ?>

                <!-- Formulario para añadir SGBD -->                
                <?php 
                // Obtener tipos de SGBD desde la base de datos
                $sgbdTypesQuery = "SELECT DISTINCT tipus FROM SIST_GESTIO_BD";
                $sgbdTypesResult = mysqli_query($conn, $sgbdTypesQuery);
                $sgbdTypes = [];
                while ($row = mysqli_fetch_assoc($sgbdTypesResult)) {
                    $sgbdTypes[] = $row['tipus'];
                }
                if(isset($_POST['component']) && $_POST['component'] == 'SGBD'): ?>
                <fieldset>
                    <legend>SGBD</legend>
                    <div class="form-group">
                        <label for="tipo_sgbd">Tipo de SGBD</label>
                        <select name="tipo" id="tipo_sgbd" class="form-control">
                            <?php foreach ($sgbdTypes as $sgbdType): ?>
                                <option value="<?php echo $sgbdType; ?>"><?php echo $sgbdType; ?></option>
                            <?php endforeach; ?>
                        </select>
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
                            <?php foreach ($ramTypes as $ramType): ?>
                                <option value="<?php echo $ramType; ?>"><?php echo $ramType; ?></option>
                            <?php endforeach; ?>
                        </select>
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

                <button type="submit" class="btn btn-primary">Añadir Componente</button>
            </form>
        </div>

    </section>

    <section class="about_section layout_paddingAbout">
        <div class="container">
            <h4 class="text-uppercase">
                Crear Nuevos Componentes
            </h4>
        </div>
        <div class="container d-flex justify-content-center">
            <form method="POST">
                <div class="form-row align-items-center">
                    <div class="col-auto">
                        <label for="tipo1">Modulos CMS</label>
                    </div>
                    <div class="col-auto">
                        <input type="text" name="tipo1" id="tipo1" class="form-control mb-2" required>
                    </div>
                    <div class="col-auto">
                        <button type="submit" name="new_cms" class="btn btn-primary mb-2">Añadir</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="container d-flex justify-content-center">
            <form method="POST">
                <div class="form-row align-items-center">
                    <div class="col-auto">
                        <label for="tipo1">Sistema de gestion de Base de datos</label>
                    </div>
                    <div class="col-auto">
                        <input type="text" name="tipo1" id="tipo1" class="form-control mb-2" required>
                    </div>
                    <div class="col-auto">
                        <button type="submit" name="new_sgbd" class="btn btn-primary mb-2">Añadir</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="container d-flex justify-content-center">
            <form method="POST">
                <div class="form-row align-items-center">
                    <div class="col-auto">
                        <label for="tipo1">Tipo de RAM</label>
                    </div>
                    <div class="col-auto">
                        <input type="text" name="tipo1" id="tipo1" class="form-control mb-2" required>
                    </div>
                    <div class="col-auto">
                        <button type="submit" name="new_ram" class="btn btn-primary mb-2">Añadir</button>
                    </div>
                </div>
            </form>
        </div>
    </section>

    <section class="about_section layout_paddingAbout">
        <div class="container">
            <h4 class="text-uppercase">
                Editar Precios Componentes
            </h4>
        </div>
        <div class="container d-flex justify-content-center">
            <form method="POST">
                <div class="form-group">
                    <label for="component1">Selecciona Componente</label>
                    <select name="component1" id="component1" class="form-control" onchange="this.form.submit()">
                        <option value="">Selecciona Componente</option>
                        <option value="CDN" <?php if(isset($_POST['component1']) && $_POST['component1'] == 'CDN') echo 'selected'; ?>>CDN</option>
                        <option value="SSL" <?php if(isset($_POST['component1']) && $_POST['component1'] == 'SSL') echo 'selected'; ?>>SSL</option>
                        <option value="RAM" <?php if(isset($_POST['component1']) && $_POST['component1'] == 'RAM') echo 'selected'; ?>>RAM</option>
                        <option value="DISC_DUR" <?php if(isset($_POST['component1']) && $_POST['component1'] == 'DISC_DUR') echo 'selected'; ?>>DISC DUR</option>
                    </select>
                </div>
                
            </form>
        </div>
    </section>

    <section class="about_section layout_paddingAbout">
        <div class="container">
            <h4 class="text-uppercase">
                Eliminar Componentes
            </h4>
        </div>
        <div class="container d-flex justify-content-center">
            <form method="POST">
                <div class="form-group">
                    <label for="component2">Selecciona Componente</label>
                    <select name="component2" id="component2" class="form-control" onchange="this.form.submit()">
                        <option value="">Selecciona Componente</option>
                        <option value="CMS" <?php if(isset($_POST['component2']) && $_POST['component2'] == 'CMS') echo 'selected'; ?>>Modulo CMS</option>
                        <option value="CDN" <?php if(isset($_POST['component2']) && $_POST['component2'] == 'CDN') echo 'selected'; ?>>CDN</option>
                        <option value="SSL" <?php if(isset($_POST['component2']) && $_POST['component2'] == 'SSL') echo 'selected'; ?>>SSL</option>
                        <option value="SGBD" <?php if(isset($_POST['component2']) && $_POST['component2'] == 'SGBD') echo 'selected'; ?>>SGBD</option>
                        <option value="RAM" <?php if(isset($_POST['component2']) && $_POST['component2'] == 'RAM') echo 'selected'; ?>>RAM</option>
                        <option value="DISC_DUR" <?php if(isset($_POST['component2']) && $_POST['component2'] == 'DISC_DUR') echo 'selected'; ?>>DISC DUR</option>
                    </select>
                </div>

                <!-- Formulario para añadir CMS -->                
                <?php 
                // Obtener tipos de CMS desde la base de datos
                $cmsTypesQuery = "SELECT DISTINCT tipus FROM MODUL_CMS";
                $cmsTypesResult = mysqli_query($conn, $cmsTypesQuery);
                $cmsTypes = [];
                while ($row = mysqli_fetch_assoc($cmsTypesResult)) {
                    $cmsTypes[] = $row['tipus'];
                }
                if(isset($_POST['component2']) && $_POST['component2'] == 'CMS'): ?>
                <fieldset>
                    <legend>Modulo CMS</legend>
                    <div class="form-group">
                        <label for="tipo2_cms">Tipo de CMS</label>
                        <select name="tipo2" id="tipo2_cms" class="form-control">
                            <?php foreach ($cmsTypes as $cmsType): ?>
                                <option value="<?php echo $cmsType; ?>"><?php echo $cmsType; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </fieldset>
                <?php endif; ?>

                <!-- Formulario para añadir CDN -->                
                <?php 
                // Obtener tipos de CDN desde la base de datos
                $cdnTypesQuery = "SELECT DISTINCT tipus, preu FROM CDN";
                $cdnTypesResult = mysqli_query($conn, $cdnTypesQuery);
                $cdnTypes = [];
                while ($row = mysqli_fetch_assoc($cdnTypesResult)) {
                    $cdnTypes[] = $row['tipus'];
                }
                
                if(isset($_POST['component2']) && $_POST['component2'] == 'CDN'): ?>
                <fieldset>
                    <legend>CDN</legend>
                    <div class="form-group">
                        <label for="tipo2_cdn">Tipo de CDN</label>
                        <select name="tipo2" id="tipo2_cdn" class="form-control">
                            <?php foreach ($cdnTypes as $cdnType): ?>
                                <option value="<?php echo $cdnType; ?>"><?php echo $cdnType; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="precio_cdn">Precio</label>
                        <input type="text" name="precio2" id="precio_cdn" class="form-control" placeholder="Max: 99.99">
                    </div>
                </fieldset>
                <?php endif; ?>

                <!-- Formulario para añadir SSL -->                
                <?php 
                // Obtener tipos de SSL desde la base de datos
                $sslTypesQuery = "SELECT DISTINCT tipus, preu FROM C_SSL";
                $sslTypesResult = mysqli_query($conn, $sslTypesQuery);
                $sslTypes = [];
                while ($row = mysqli_fetch_assoc($sslTypesResult)) {
                    $sslTypes[] = $row['tipus'];
                }
                
                if(isset($_POST['component2']) && $_POST['component2'] == 'SSL'): ?>
                <fieldset>
                    <legend>SSL</legend>
                    <div class="form-group">
                        <label for="tipo2_ssl">Tipo de SSL</label>
                        <select name="tipo2" id="tipo2_ssl" class="form-control">
                            <?php foreach ($sslTypes as $sslType): ?>
                                <option value="<?php echo $sslType; ?>"><?php echo $sslType; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="precio_ssl">Precio</label>
                        <input type="text" name="precio2" id="precio_ssl" class="form-control" placeholder="Max: 99.99">
                    </div>
                </fieldset>
                <?php endif; ?>

                <!-- Formulario para añadir SGBD -->                
                <?php 
                // Obtener tipos de SGBD desde la base de datos
                $sgbdTypesQuery = "SELECT DISTINCT tipus FROM SIST_GESTIO_BD";
                $sgbdTypesResult = mysqli_query($conn, $sgbdTypesQuery);
                $sgbdTypes = [];
                while ($row = mysqli_fetch_assoc($sgbdTypesResult)) {
                    $sgbdTypes[] = $row['tipus'];
                }
                if(isset($_POST['component2']) && $_POST['component2'] == 'SGBD'): ?>
                <fieldset>
                    <legend>SGBD</legend>
                    <div class="form-group">
                        <label for="tipo2_sgbd">Tipo de SGBD</label>
                        <select name="tipo2" id="tipo2_sgbd" class="form-control">
                            <?php foreach ($sgbdTypes as $sgbdType): ?>
                                <option value="<?php echo $sgbdType; ?>"><?php echo $sgbdType; ?></option>
                            <?php endforeach; ?>
                        </select>
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
                
                if(isset($_POST['component2']) && $_POST['component2'] == 'RAM'): ?>
                <fieldset>
                    <legend>RAM</legend>
                    <div class="form-group">
                        <label for="tipo2_ram">Tipo de RAM</label>
                        <select name="tipo2" id="tipo2_ram" class="form-control">
                            <?php foreach ($ramTypes as $ramType): ?>
                                <option value="<?php echo $ramType; ?>"><?php echo $ramType; ?></option>
                            <?php endforeach; ?>
                        </select>
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
                        <input type="text" name="precio2" id="precio_ram" class="form-control" placeholder="Max: 99.99">
                    </div>
                </fieldset>
                <?php endif; ?>

                <!-- Formulario para añadir DISC DUR -->
                <?php if(isset($_POST['component2']) && $_POST['component2'] == 'DISC_DUR'): ?>
                <fieldset>
                    <legend>DISC DUR</legend>
                    <div class="form-group">
                        <label for="tipo2_disc_dur">Tipo de DISC DUR</label>
                        <select name="tipo2" id="tipo2_disc_dur" class="form-control">
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
                        <input type="text" name="precio2" id="precio_disc_dur" class="form-control" placeholder="Max: 999.99">
                    </div>
                </fieldset>
                <?php endif; ?>

                
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