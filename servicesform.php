<!-- @Author: Blanca Atienzar Martinez -->

<?php 
session_start();
if ($_SESSION['esUsuari']) {
    header("Location: iniciUsuariForm.php");
    exit();
}

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
                  <div class="about_card-container layout_padding2-top">
                  <?php
                      if ($_SESSION['esUsuari']) {
                        echo '<a href="servicesUsuariform.php">Usuari</a>';
                      }
                  ?>
                  </div>
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
          Nuestros Servicios
        </h2>
        <a href="servicesPersonalForm.php" class="btn btn-primary">Editar perfil</a>
      </div>


      <div class="container">
        <div class="about_card-container layout_padding2-top">
          <?php
              if ($_SESSION['esUsuari']) {
                echo '<div class="about_card">
              <div class="about-detail">
                <div class="about_img-container">
                  <div class="about_img-box">
                    <a href="servicesSaaSform.php"><img src="images/work2.png" alt="" /></a>
                  </div>
                </div>
                <div class="card_detail-ox">
                  <h4>
                    <a href="servicesSaaSform.php">Servicios SaaS</a>
                  </h4>
                  
                </div>
              </div>
            </div>';
              }
          ?>

          <?php
            if ($_SESSION['esUsuari']) {
              echo '<div class="about_card">
            <div class="about-detail">
              <div class="about_img-container">
                <div class="about_img-box">
                  <a href="servicesPaaSform.php"><img src="images/work3.png" alt="" /></a>
                </div>
              </div>
              <div class="card_detail-ox">
                <h4>
                  <a href="servicesPaaSform.php">Servicios PaaS</a>
                </h4>
                
              </div>
            </div>
          </div>';
            }
          ?>  

          <?php
            if (!$_SESSION['esUsuari']) {
              echo '<div class="about_card">
            <div class="about-detail">
              <div class="about_img-container">
                <div class="about_img-box">
                  <a href="servicesSaaSViewform.php"><img src="images/work2.png" alt="" /></a>
                </div>
              </div>
              <div class="card_detail-ox">
                <h4>
                  <a href="servicesSaaSViewform.php">Servicios SaaS</a>
                </h4>
                
              </div>
            </div>
          </div>';
            }
          ?>

          <?php
            if (!$_SESSION['esUsuari']) {
              echo '<div class="about_card">
            <div class="about-detail">
              <div class="about_img-container">
                <div class="about_img-box">
                  <a href="servicesPaaSPersonalInicioEditform.php"><img src="images/work3.png" alt="" /></a>
                </div>
              </div>
              <div class="card_detail-ox">
                <h4>
                  <a href="servicesPaaSPersonalInicioEditform.php">Servicios PaaS</a>
                </h4>
                
              </div>
            </div>
          </div>';
            }
          ?>
          
          <?php
            if (!$_SESSION['esUsuari']) {
              echo '<div class="about_card">
                <div class="about-detail">
                  <div class="about_img-container">
                    <div class="about_img-box">
                      <a href="gestOrgForm.php"><img src="images/work1.png" alt="" /></a>
                    </div>
                  </div>
                  <div class="card_detail-ox">
                    <h4>
                      <a href="gestOrgForm.php">Gestionar Organitzacións</a>
                    </h4>
                    
                  </div>
                </div>
              </div>';
            }
          ?>
          <?php
            if (!$_SESSION['esUsuari']) {
              echo '<div class="about_card">
                <div class="about-detail">
                  <div class="about_img-container">
                    <div class="about_img-box">
                      <a href="mostrarHistorialform.php"><img src="images/f3.png" alt="" /></a>
                    </div>
                  </div>
                  <div class="card_detail-ox">
                    <h4>
                      <a href="mostrarHistorialform.php">Historial</a>
                    </h4>
                    
                  </div>
                </div>
              </div>';
            }
          ?>
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
