<!-- @Author: Blanca Atienzar Martinez -->
<?php session_start();
require_once "./src/conexio.php";
$conn = Conexion::getConnection();
?>

<html>
  <head>
    <!-- Basic -->
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!-- Mobile Metas -->
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1, shrink-to-fit=no"
    />
    <!-- Site Metas -->
    <meta name="keywords" content="" />
    <meta name="description" content="" />
    <meta name="author" content="" />

    <title>MPHB</title>

    <!-- slider stylesheet -->
    <link
      rel="stylesheet"
      type="text/css"
      href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.1.3/assets/owl.carousel.min.css"
    />

    <!-- bootstrap core css -->
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css" />

    <!-- fonts style -->
    <link
      href="https://fonts.googleapis.com/css?family=Poppins|Raleway:400,600|Righteous&display=swap"
      rel="stylesheet"
    />
    <!-- Custom styles for this template -->
    <link href="css/style.css" rel="stylesheet" />
    <!-- responsive style -->
    <link href="css/responsive.css" rel="stylesheet" />
  </head>

  <body>
    <?php
      if (isset($_SESSION['success_msg'])) {
        echo '
          <div class="alert alert-success alert-dismissible fade show" role="alert">'
          . $_SESSION["success_msg"] .
          '</div>';
        unset($_SESSION['success_msg']);
      } 
      if(isset($_SESSION['error_msg'])) {
        echo '
          <div class="alert alert-danger alert-dismissible fade show" role="alert">'
          . $_SESSION["error_msg"] .
          '</div>';
        unset($_SESSION['error_msg']);
      }
      if(isset($_SESSION['usuariCreat'])) {
        echo '
        <div class="alert alert-success alert-dismissible fade show" role="alert">'
        . $_SESSION["usuariCreat"] .
        '</div>';
      unset($_SESSION['usuariCreat']);
      }
    ?>
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
       
      <!-- slider section -->
      
    <section class="contact_section layout_padding"  style="min-height: calc(100vh - 200px);">
      <div class="container d-flex justify-content-center">
        <h2 class="text-uppercase">
          Login
        </h2>
      </div>
      <div class="container-fluid layout_padding-top">
        <div class="row">
          <div class="col-md-6">
            <div class="col-md-10 offset-md-2">
              <div class="contact_img-box">
                <img src="images/form-img.jpg" alt="" />
              </div>
            </div>
          </div>
          <div class="col-md-6 form_bg px-0">
            <div class="col-md-10 px-0">
                <div class="contact_form-container">
                  <div>
                    <form action="loginbd.php" method="post">
                      <div>
                        <input name="email" placeholder=" Email" />
                      </div>
                      <div>
                        <input name="contrasenya" placeholder=" Password"  type="password"/>
                      </div>
                      <div>
                        <button type="submit" class="btn btn-primaryLogin">
                          Login
                          <!--a href="servicesform.php">Login</a-->
                        </button>
                      </div>
                    </form>
                    <div class="card_usuari">
                      <h4>
                        Usuari nou?
                      </h4>
                    </div>
                    <div>
                      <button type="button" class="btn btn-primaryRegistrarse">
                        <a href="insereixusuariform.php">Registrarse</a>
                      </button>
                    </div>
                  </div>
                </div>
            </div>
          </div>
        </div>
      </div>
    </section>
   
      <!-- end slider section -->
    </div>

  

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
