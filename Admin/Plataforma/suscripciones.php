<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <link rel="apple-touch-icon" sizes="76x76" href="./assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="./assets/img/favicon.png">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <title>
    Vitallica - Suscripciones
  </title>
  <meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
  <!--     Fonts and icons     -->
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
  <!-- CSS Files -->
  <link href="./assets/css/material-dashboard.css?v=2.1.2" rel="stylesheet" />
  <link href="./assets/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
</head>

<?php
  //SESION
  session_start();
  if (!isset($_SESSION['loggedin'])) 
  {  
    echo "<meta http-equiv='refresh' content='0; URL=login.php'>";
    exit; 
  }

  $now = time();           
  if ($now > $_SESSION['fin']) 
  {
      session_destroy();
      echo "<meta http-equiv='refresh' content='0; URL=login.php'>";
      exit;
  }
?>

<body class="">
  <div class="wrapper ">
    <?php $pag = "suscripciones"; include("sidebar.php"); ?>
    <div class="main-panel">
      <!-- Navbar -->
      <nav class="navbar navbar-expand-lg navbar-transparent navbar-absolute fixed-top ">
        <div class="container-fluid">
          <div class="navbar-wrapper">
            <a class="navbar-brand" href="javascript:;">Suscripciones</a>
          </div>
        </div>
      </nav>
      <!-- End Navbar -->
      <div class="content">
        <div class="container-fluid">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header card-header-tabs card-header-primary">
                <div class="nav-tabs-navigation">
                  <div class="nav-tabs-wrapper">
                    <span class="nav-tabs-title">Suscripciones :</span>
                    <ul class="nav nav-tabs" data-tabs="tabs">
                      <li class="nav-item">
                        <a class="nav-link active" href="#activas" data-toggle="tab">
                          <i class="material-icons">thumb_up</i> Activas
                          <div class="ripple-container"></div>
                        </a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" href="#finalizadas" data-toggle="tab">
                          <i class="material-icons">check_circle</i> Finalizadas
                          <div class="ripple-container"></div>
                        </a>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
              <div class="card-body">
                <div class="tab-content">
                  <div class="tab-pane active" id="activas">
                    <table class="table" id="table-activas" width="100%">
                      <!-- TABLA ACTIVAS -->
                      <thead class="text-primary">
                        <th>Id Suscripción</th>
                        <th>Cliente</th>
                        <th>Clases disponibles</th>
                        <th>Fecha alta</th>
                        <th>Acción</th>
                      </thead>
                      <tbody id="table-activas-body">
                      </tbody>
                    </table>
                  </div>
                  <div class="tab-pane" id="finalizadas">
                    <table class="table" id="table-finalizadas" width="100%">
                      <!-- TABLA FINALIZADAS -->
                      <thead class="text-primary">
                        <th>Id Suscripción</th>
                        <th>Cliente</th>
                        <th>Clases disponibles</th>
                        <th>Fecha alta</th>
                        <th>Acción</th>
                      </thead>
                      <tbody id="table-finalizadas-body" />
                    </table>
                  </div>
                </div>
              </div>
             </div>
          </div>
        </div>
      </div>
      <footer class="footer">
        <div class="container-fluid">
          <div class="copyright float-right">
            &copy;
            <script>
              document.write(new Date().getFullYear())
            </script>, desarrollado por SERLO Software
          </div>
        </div>
      </footer>
    </div>
  </div>
  <div id="ventana-suscripcion" class="fixed-top" style="visibility: hidden;">
    <div class="row justify-content-center">
      <div class="col-md-5">
        <div class="card card-chart">
          <div class="card-header card-header-info">
            <i id="close-ventana" class="material-icons" style="cursor:pointer;">close</i>
            <h4 class="card-title" id="suscripcion-header"></h4>
            <p class="card-category">Checa toda la información de la suscripción</p>
          </div>
          <div class="card-body">
            <h4 class="card-title">Información de la suscripcion</h4><hr>
            <p class="card-category">
              <div class="row">
                <div class="col-md-5">
                  <h6>Cliente</h6><p id="cliente"></p>
                  <h6>Teléfono</h6><p id="telefono"></p>
                  <h6>Clases disponibles</h6><p id="clases-disponibles"></p>
                  <h6>Subtotal</h6><p id="subtotal-v-sus"></p>
                  <h6>Total</h6><p id="total-v-sus"></p>
                  <h6>Fecha alta</h6><p id="fecha-alta-v-sus"></p>
                </div>
                <div class="col-md-7">
                  <div class="table-responsive">
                    <table id="tabla-suscripcion-info" class="table table-hover" width="100%">
                      <thead>
                        <tr>
                          <th><b>Clase</b></th>
                          <th><b>Instructor</b></th>
                          <th><b>Horario clase</b></th>
                        </tr>
                      </thead>
                      <tbody id="table-suscripcion-body"></tbody>
                    </table>
                  </div>
                </div>
              </div>
            </p>
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
  <!--  Plugin for Sweet Alert -->
  <script src="./assets/js/plugins/sweetalert2.js"></script>
  <!-- Forms Validations Plugin -->
  <script src="./assets/js/plugins/jquery.validate.min.js"></script>
  <!-- Plugin for the Wizard, full documentation here: https://github.com/VinceG/twitter-bootstrap-wizard -->
  <script src="./assets/js/plugins/jquery.bootstrap-wizard.js"></script>
  <!--  Plugin for Select, full documentation here: http://silviomoreto.github.io/bootstrap-select -->
  <script src="./assets/js/plugins/bootstrap-selectpicker.js"></script>
  <!--  Plugin for the DateTimePicker, full documentation here: https://eonasdan.github.io/bootstrap-datetimepicker/ -->
  <script src="./assets/js/plugins/bootstrap-datetimepicker.min.js"></script>
  <!--  DataTables.net Plugin, full documentation here: https://datatables.net/  -->
  <!--<script src="./assets/js/plugins/jquery.dataTables.min.js"></script>-->
  <script src="./assets/datatables/jquery.dataTables.min.js"></script>
  <script src="./assets/datatables/dataTables.bootstrap4.min.js"></script>
  <!--  Plugin for Tags, full documentation here: https://github.com/bootstrap-tagsinput/bootstrap-tagsinputs  -->
  <script src="./assets/js/plugins/bootstrap-tagsinput.js"></script>
  <!-- Plugin for Fileupload, full documentation here: http://www.jasny.net/bootstrap/javascript/#fileinput -->
  <script src="./assets/js/plugins/jasny-bootstrap.min.js"></script>
  <!--  Full Calendar Plugin, full documentation here: https://github.com/fullcalendar/fullcalendar    -->
  <script src="./assets/js/plugins/fullcalendar.min.js"></script>
  <!-- Vector Map plugin, full documentation here: http://jvectormap.com/documentation/ -->
  <script src="./assets/js/plugins/jquery-jvectormap.js"></script>
  <!--  Plugin for the Sliders, full documentation here: http://refreshless.com/nouislider/ -->
  <script src="./assets/js/plugins/nouislider.min.js"></script>
  <!-- Include a polyfill for ES6 Promises (optional) for IE11, UC Browser and Android browser support SweetAlert -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/core-js/2.4.1/core.js"></script>
  <!-- Library for adding dinamically elements -->
  <script src="./assets/js/plugins/arrive.min.js"></script>
  <!--  Notifications Plugin    -->
  <script src="./assets/js/plugins/bootstrap-notify.js"></script>
  <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="./assets/js/material-dashboard.js?v=2.1.2" type="text/javascript"></script>
  <script type="text/javascript">
    function showVentanaSuscripcion(id_suscripcion)
    {
      var post_url = "../Backend/App.php";
      var form_data = "op=GetSuscripcion&id_suscripcion=" + id_suscripcion;
      $.post(post_url, form_data, function(json)
      {
        $("#ventana-suscripcion").show();
        $("#ventana-suscripcion").css("visibility", "visible");
        $("#cliente").html(json["suscripcion"]["cliente"]);
        $("#suscripcion-header").html("Suscripción #" + json["suscripcion"]["id_suscripcion"]);
        $("#telefono").html(json["suscripcion"]["telefono"]);
        $("#clases-disponibles").html(json["suscripcion"]["clases_disponibles"]);
        $("#subtotal-v-sus").html("$" + json["suscripcion"]["subtotal"] + " MXN");
        $("#total-v-sus").html("$" + json["suscripcion"]["total"] + " MXN");
        $("#fecha-alta-v-sus").html(json["suscripcion"]["fecha_alta"]);
        var clases = json["suscripcion"]["clases"];
        var table_body = "";
        for(var i = 0; i < clases.length; i++)
        {
          table_body += "<tr><td>" + clases[i]["clase"] + "</td>" + "<td>" + clases[i]["instructor"] + "</td>" + "<td>" + clases[i]["horario_clase"] + "</td>" +"</tr>";
        }
        $("#table-suscripcion-body").html(table_body);
      });
    }

    function loadTabla(tabla, op)
    {
      $.ajax({
        "url": "../Backend/App.php", 
        "type": "POST",
        "data": {
          op: op
        },     
        success : function(data) {
          $(tabla).DataTable({
            data: data["suscripciones"],
            columns: [
              { data: "id_suscripcion"},
              { data: "cliente"},
              { data: "clases_disponibles"},
              { data: "fecha_alta"},
              { data: "otros"}
            ]
          });
        } 
      });
    }
  </script>
  <script>
    $(document).ready(function() 
    { 
      //LOAD TABLAS
      loadTabla("#table-activas", "GetSuscripcionesActivas");
      loadTabla("#table-finalizadas", "GetSuscripcionesFinalizadas");

      //cerrar ventana suscripcion
      $("#close-ventana").click(function(event)
      {
        $("#ventana-suscripcion").hide();
        $("#ventana-suscripcion").css("visibility", "hidden"); 
      }); 

      $('#tabla-suscripcion-info').DataTable({
        "scrollY":"300px",
        "scrollCollapse":true,
        "paging":false,
        "searching":false,
        "ordering":false,
        "info":false
      });

    });
  </script>
</body>

</html>