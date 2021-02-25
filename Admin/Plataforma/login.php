<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8" />
  <link rel="apple-touch-icon" sizes="76x76" href="./assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="./assets/img/favicon.png">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <title>
      EnerG - Iniciar sesión
  </title>
  <meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
  <!--     Fonts and icons     -->
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
  <!-- CSS Files -->
  <link href="./assets/css/material-dashboard.css?v=2.1.2" rel="stylesheet" />
  <!-- CSS Just for demo purpose, don't include it in your project -->
  <link href="./assets/demo/demo.css" rel="stylesheet" />

  <?php
  if(isset($_SESSION["loggedin"]))
  {
    echo "<meta http-equiv='refresh' content='0; URL=dashboard.php'>"; 
    exit;
  }
  ?>
</head>

<body class="">
  <div class="wrapper ">
    <div class="">
      <div class="">
        <div class="container-fluid">
          <div class="row justify-content-center">
            <div class="col-xl-10 col-lg-12 col-md-9">
              <div class="card">
                <div class="card-header card-header-primary">
                  <h4 class="card-title">Inicia sesión</h4>
                  <p class="card-category">Ingresa las credenciales de tu negocio</p>
                </div>
                <div class="card-body">
                  <form id="form-login" action="../Backend/App.php" method="post">
                    <div class="form-group">
                      <label class="bmd-label-floating">Nombre de usuario</label>
                      <input type="text" class="form-control" id="usuario" name="usuario">
                    </div>
                    <div class="form-group">
                      <label class="bmd-label-floating">Contraseña</label>
                      <input type="password" class="form-control" id="contraseña" name="contraseña">
                    </div>
                    <button type="submit" class="btn btn-primary pull-right">Iniciar sesión</button>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!--   Core JS Files   -->
  <script src="./assets/js/core/jquery.min.js"></script>
  <script src="./assets/js/core/popper.min.js"></script>
  <script src="./assets/js/core/bootstrap-material-design.min.js"></script>
  <script src="./assets/js/plugins/perfect-scrollbar.jquery.min.js"></script>
  <!-- Plugin for the momentJs  -->
  <script src="./assets/js/plugins/moment.min.js"></script>
  <!-- Forms Validations Plugin -->
  <script src="./assets/js/plugins/jquery.validate.min.js"></script>
  <!-- Plugin for the Wizard, full documentation here: https://github.com/VinceG/twitter-bootstrap-wizard -->
  <script src="./assets/js/plugins/jquery.bootstrap-wizard.js"></script>
  <!-- Include a polyfill for ES6 Promises (optional) for IE11, UC Browser and Android browser support SweetAlert -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/core-js/2.4.1/core.js"></script>
  <!-- Library for adding dinamically elements -->
  <script src="./assets/js/plugins/arrive.min.js"></script>
  <script type="text/javascript">

    $("#form-login").on("submit", function(e)
    {
        e.preventDefault();
        var f = $(this);
        var formData = new FormData(document.getElementById("form-login"));
        formData.append("op", "IniciarSesion");
        //formData.append(f.attr("name"), $(this)[0].files[0]);
        $.ajax({
          url: "../Backend/App.php",
          type: "post",
          dataType: "html",
          data: formData,
          cache: false,
          contentType: false,
          processData: false
        })
        .done(function(res)
        {
          if(res.trim() == "no")
          {
            alert("Credenciales incorrectas.");
          }
          else
          {
            window.location.href = 'dashboard.php';
          }
        });
    });
  </script>
</body>

</html>