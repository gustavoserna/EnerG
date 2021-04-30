<?php
session_start();
if(!isset($_SESSION["loggedin"])){
  echo "<meta http-equiv='refresh' content='0; URL=login.php'>"; 
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <title>Vitallica - Mi cuenta</title>
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <meta content="" name="keywords">
  <meta content="" name="description">

  <!-- Favicons -->
  <link href="img/favicon.png" rel="icon">
  <link href="img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,700,700i|Raleway:300,400,500,700,800" rel="stylesheet">

  <!-- Bootstrap CSS File -->
  <link href="lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">

  <!-- Libraries CSS Files -->
  <link href="lib/font-awesome/css/font-awesome.min.css" rel="stylesheet">
  <link href="lib/animate/animate.min.css" rel="stylesheet">
  <link href="lib/venobox/venobox.css" rel="stylesheet">
  <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

  <!-- Main Stylesheet File -->
  <link href="css/style.css" rel="stylesheet">
  
</head>

<body>

  <!--==========================
    Header
  ============================-->
  <header id="header" style="background:#000;">
    <div class="container">

      <div id="logo" class="pull-left">
        <a href="index.html" class="scrollto"><img src="img/logo.png" alt="" title=""></a>
      </div>

      <nav id="nav-menu-container">
        <ul class="nav-menu">
          <li class="menu-active"><a href="index.html">Inicio</a></li>
          <li><a href="#contact">Actualizar perfil</a></li>
          <li><a href="#schedule">Mis clases</a></li>
        </ul>
      </nav><!-- #nav-menu-container -->
    </div>
  </header><!-- #header -->

  <main id="main">
      
    <!--==========================
      Seccion Perfil
    ============================-->
    <section id="contact" class="section-bg wow fadeInUp">

      <div class="container">

        <div class="section-header">
          <br><br><br><br><div class="text-center"><img src="img/avatar.png" height="150px" /></div><br>
          <h2>Mi cuenta</h2>
          <p>Actualiza tu perfil aquí.</p>
        </div>

        <div class="form">
          <div id="sendmessage">Login</div>
          <form id="perfil-form" action="" method="post" role="form" class="contactForm">
          <div class="form-group">
              <input type="text" class="form-control" name="nombre" id="nombre" placeholder="Nombre" />
              <div class="validation"></div>
            </div>
            <div class="form-group">
              <input type="text" class="form-control" name="apellido" id="apellido" placeholder="Apellido" />
              <div class="validation"></div>
            </div>
            <div class="form-group">
              <input type="text" class="form-control" name="mail" id="mail" placeholder="Correo electrónico" disabled />
              <div class="validation"></div>
            </div>
            <div class="form-group">
              <input type="text" class="form-control" name="telefono" id="telefono" placeholder="Teléfono" disabled />
              <div class="validation"></div>
            </div>
            <div class="form-group">
              <input type="text" class="form-control" name="username" id="username" placeholder="Nombre de usuario" disabled />
              <div class="validation"></div>
            </div>
            <div class="form-group">
              <input type="password" class="form-control" name="password" id="password" placeholder="Contraseña" />
              <div class="validation"></div>
            </div>
            <div class="form-group">
              <input type="password" class="form-control" name="password2" id="password2" placeholder="Confirmar contraseña" />
              <div class="validation"></div>
            </div>
            <div class="text-center"><button type="submit">Actualizar perfil</button></div><br>
          </form>
        </div>

      </div>
    </section><!-- #perfil-->

    <!--==========================
      Seccion mis clases
    ============================-->
    <section id="schedule" class="section-with-bg">
      <div class="container wow fadeInUp">
        <div class="section-header">
          <h2>Mis clases</h2>
          <p>Checa tus clases programadas y completadas.</p>
        </div>

        <ul class="nav nav-tabs" role="tablist">
          <li class="nav-item">
            <a class="nav-link active" href="#programadas" role="tab" data-toggle="tab">Programadas</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#completadas" role="tab" data-toggle="tab">Completadas</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#canceladas" role="tab" data-toggle="tab">Canceladas</a>
          </li>
        </ul>

        <h3 class="sub-heading">En esta sección encontrarás información sobre tus clases programadas y completadas, también puedes cancelar clases si así lo prefieres.<br>¡Es muy fácil!</h3>
        
        <!-- Horarios -->
        <div class="tab-content row justify-content-center">
          <div role="tabpanel" class="col-lg-9 tab-pane fade show active" id="programadas"></div>
          <div role="tabpanel" class="col-lg-9 tab-pane fade" id="completadas"></div>
          <div role="tabpanel" class="col-lg-9 tab-pane fade" id="canceladas"></div>
        </div>

      </div>

      <!-- Modal Order Form -->
      <div id="cancelar-modal" class="modal fade">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Cancelar esta clase</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form id="cancelar-form" action="" method="post" role="form" class="contactForm">
                <div class="form-group">
                  <input type="text" class="form-control" name="clase" placeholder="Clase" disabled>
                </div>
                <div class="form-group">
                  <input type="text" class="form-control" name="instructor" placeholder="Instructor" disabled>
                </div>
                <div class="form-group">
                  <input type="text" class="form-control" name="fecha" placeholder="Fecha" disabled>
                </div>
                <div class="text-center">
                  <button id="comprar-plan" type="submit" class="btn">Cancelar</button>
                </div>
              </form>
            </div>
          </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
      </div><!-- /.modal -->

      <br><br><br><div class="text-center"><button class="btn" onclick="cerrarSesion()">Cerrar sesión</button></div><br>

    </section>

  </main>

  <!--==========================
    Footer
  ============================-->
  <footer id="footer">
    <div class="footer-top">
      <div class="container">
        <div class="row">

          <div class="col-lg-6 col-md-6 footer-info">
            <img src="img/logo.png" alt="Vitallica"><br><br>
            <iframe src="https://maps.google.com/maps?q=itrain,%20san%20isidro,%20torreon&t=&z=13&ie=UTF8&iwloc=&output=embed" frameborder="0" style="border:0" allowfullscreen></iframe>
          </div>

          <div class="col-lg-3 col-md-6 footer-links">
            <h4>Links</h4>
            <ul>
              <li><i class="fa fa-angle-right"></i> <a href="#intro">Inicio</a></li>
              <li><i class="fa fa-angle-right"></i> <a href="#about">Acerca de</a></li>
              <li><i class="fa fa-angle-right"></i> <a href="#schedule">Reserva</a></li>
            </ul>
          </div>

          <div class="col-lg-3 col-md-6 footer-contact">
            <h4>Contáctanos</h4>
            <p>
              Calle Hamburgo #611, San Isidro <br>
              Torreón Coahuila<br>
              México <br>
              <strong>Phone:</strong> 871 402 6482<br>
              <strong>Email:</strong> info@example.com<br>
            </p>

            <div class="social-links">
              <a href="#" class="twitter"><i class="fa fa-twitter"></i></a>
              <a href="#" class="facebook"><i class="fa fa-facebook"></i></a>
              <a href="#" class="instagram"><i class="fa fa-instagram"></i></a>
              <a href="#" class="google-plus"><i class="fa fa-google-plus"></i></a>
              <a href="#" class="linkedin"><i class="fa fa-linkedin"></i></a>
            </div>

          </div>

        </div>
      </div>
    </div>

    <div class="container">
      <div class="copyright">
        &copy; Copyright <strong>Vitallica</strong>.
      </div>
      <div class="credits">
        <script>
          document.write(new Date().getFullYear())
        </script>, desarrollado por SERLO Software
      </div>
    </div>
  </footer><!-- #footer -->

  <a href="#" class="back-to-top"><i class="fa fa-angle-up"></i></a>

  <!-- JavaScript Libraries -->
  <script src="lib/jquery/jquery.min.js"></script>
  <script src="lib/jquery/jquery-migrate.min.js"></script>
  <script src="lib/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="lib/easing/easing.min.js"></script>
  <script src="lib/superfish/hoverIntent.js"></script>
  <script src="lib/superfish/superfish.min.js"></script>
  <script src="lib/wow/wow.min.js"></script>
  <script src="lib/venobox/venobox.min.js"></script>
  <script src="lib/owlcarousel/owl.carousel.min.js"></script>

  <!-- Contact Form JavaScript File -->
  <script src="contactform/contactform.js"></script>

  <!-- Template Main Javascript File -->
  <script src="js/main.js"></script>

  <!-- BE Controller -->
  <script src="js/perfilController.js"></script>
</body>

</html>
