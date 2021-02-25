<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <link rel="apple-touch-icon" sizes="76x76" href="./assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="./assets/img/favicon.png">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <title>
    Abastos - Pedidos
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
  else if($_SESSION["usuario"] != "gustavo")
  {
    echo "<meta http-equiv='refresh' content='0; URL=reportes.php'>";
    exit;
  }
?>

<body class="">
  <div class="wrapper ">
    <?php $pag = "pedidos"; include("sidebar.php"); ?>
    <div class="main-panel">
      <!-- Navbar -->
      <nav class="navbar navbar-expand-lg navbar-transparent navbar-absolute fixed-top ">
        <div class="container-fluid">
          <div class="navbar-wrapper">
            <a class="navbar-brand" href="javascript:;">Pedidos</a>
          </div>
          <button class="navbar-toggler" type="button" data-toggle="collapse" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
            <span class="sr-only">Toggle navigation</span>
            <span class="navbar-toggler-icon icon-bar"></span>
            <span class="navbar-toggler-icon icon-bar"></span>
            <span class="navbar-toggler-icon icon-bar"></span>
          </button>
          <div class="collapse navbar-collapse justify-content-end">
            <form class="navbar-form">
              <div class="input-group no-border">
                <input type="text" value="" class="form-control" placeholder="Search...">
                <button type="submit" class="btn btn-white btn-round btn-just-icon">
                  <i class="material-icons">search</i>
                  <div class="ripple-container"></div>
                </button>
              </div>
            </form>
            <ul class="navbar-nav">
              <li class="nav-item">
                <a class="nav-link" href="javascript:;">
                  <i class="material-icons">dashboard</i>
                  <p class="d-lg-none d-md-block">
                    Stats
                  </p>
                </a>
              </li>
              <li class="nav-item dropdown">
                <a class="nav-link" href="http://example.com" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="material-icons">notifications</i>
                  <span class="notification">5</span>
                  <p class="d-lg-none d-md-block">
                    Some Actions
                  </p>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                  <a class="dropdown-item" href="#">Mike John responded to your email</a>
                  <a class="dropdown-item" href="#">You have 5 new tasks</a>
                  <a class="dropdown-item" href="#">You're now friend with Andrew</a>
                  <a class="dropdown-item" href="#">Another Notification</a>
                  <a class="dropdown-item" href="#">Another One</a>
                </div>
              </li>
              <li class="nav-item dropdown">
                <a class="nav-link" href="javascript:;" id="navbarDropdownProfile" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="material-icons">person</i>
                  <p class="d-lg-none d-md-block">
                    Account
                  </p>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownProfile">
                  <a class="dropdown-item" href="#">Profile</a>
                  <a class="dropdown-item" href="#">Settings</a>
                  <div class="dropdown-divider"></div>
                  <a class="dropdown-item" href="#">Log out</a>
                </div>
              </li>
            </ul>
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
                    <span class="nav-tabs-title">Pedidos :</span>
                    <ul class="nav nav-tabs" data-tabs="tabs">
                      <li class="nav-item">
                        <a class="nav-link active" href="#confirmados" data-toggle="tab">
                          <i class="material-icons">thumb_up</i> Confirmados
                          <div class="ripple-container"></div>
                        </a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" href="#listos-recoleccion" data-toggle="tab">
                          <i class="material-icons">alarm_on</i> Listos para recolección
                          <div class="ripple-container"></div>
                        </a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" href="#recolectados" data-toggle="tab">
                          <i class="material-icons">shopping_cart</i> Recolectados
                          <div class="ripple-container"></div>
                        </a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" href="#en-camino" data-toggle="tab">
                          <i class="material-icons">local_shipping</i> En camino
                          <div class="ripple-container"></div>
                        </a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" href="#finalizados" data-toggle="tab">
                          <i class="material-icons">check_circle</i> Finalizados
                          <div class="ripple-container"></div>
                        </a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" href="#cancelados" data-toggle="tab">
                          <i class="material-icons">cancel</i> Cancelados
                          <div class="ripple-container"></div>
                        </a>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
              <div class="card-body">
                <div class="tab-content">
                  <div class="tab-pane active" id="confirmados">
                    <table class="table" id="table-confirmados" width="100%">
                      <!-- TABLA CONFIRMADOS -->
                      <thead class="text-primary">
                        <th>Id Pedido</th>
                        <th>Cliente</th>
                        <th>SubTotal</th>
                        <th>Fecha de pedido</th>
                        <th>Estado</th>
                        <th>Acción</th>
                      </thead>
                      <tbody id="table-confirmados-body">
                      </tbody>
                    </table>
                  </div>
                  <div class="tab-pane" id="listos-recoleccion">
                    <table class="table" id="table-listos-recoleccion" width="100%">
                      <!-- TABLA LISTOS RECOLECCION -->
                      <thead class="text-primary">
                        <th>Id Pedido</th>
                        <th>Cliente</th>
                        <th>SubTotal</th>
                        <th>Fecha de pedido</th>
                        <th>Estado</th>
                        <th>Acción</th>
                      </thead>
                      <tbody id="table-listos-recoleccion-body">
                      </tbody>
                    </table>
                  </div>
                  <div class="tab-pane" id="recolectados">
                    <table class="table" id="table-recolectados" width="100%">
                      <!-- TABLA RECOLECTADOS -->
                      <thead class="text-primary">
                        <th>Id Pedido</th>
                        <th>Cliente</th>
                        <th>SubTotal</th>
                        <th>Fecha de pedido</th>
                        <th>Estado</th>
                        <th>Acción</th>
                      </thead>
                      <tbody id="table-recolectados-body" />
                    </table>
                  </div>
                  <div class="tab-pane" id="en-camino">
                    <table class="table" id="table-en-camino" width="100%">
                      <!-- TABLA EN CAMINO -->
                      <thead class="text-primary">
                        <th>Id Pedido</th>
                        <th>Cliente</th>
                        <th>SubTotal</th>
                        <th>Fecha de pedido</th>
                        <th>Estado</th>
                        <th>Acción</th>
                      </thead>
                      <tbody id="table-en-camino-body" />
                    </table>
                  </div>
                  <div class="tab-pane" id="finalizados">
                    <table class="table" id="table-finalizados" width="100%">
                      <!-- TABLA FINALIZADOS -->
                      <thead class="text-primary">
                        <th>Id Pedido</th>
                        <th>Cliente</th>
                        <th>SubTotal</th>
                        <th>Fecha de pedido</th>
                        <th>Estado</th>
                        <th>Acción</th>
                      </thead>
                      <tbody id="table-finalizados-body" />
                    </table>
                  </div>
                  <div class="tab-pane" id="cancelados">
                    <table class="table" id="table-cancelados" width="100%">
                      <!-- TABLA CANCELADOS -->
                      <thead class="text-primary">
                        <th>Id Pedido</th>
                        <th>Cliente</th>
                        <th>SubTotal</th>
                        <th>Fecha de pedido</th>
                        <th>Estado</th>
                        <th>Acción</th>
                      </thead>
                      <tbody id="table-cancelados-body" />
                    </table>
                  </div>
                </div>
              </div>
             </div>
          </div>
        </div>
      </div>
      <!--<footer class="footer">
        <div class="container-fluid">
          <nav class="float-left">
            <ul>
              <li>
                <a href="https://www.creative-tim.com">
                  Creative Tim
                </a>
              </li>
              <li>
                <a href="https://creative-tim.com/presentation">
                  About Us
                </a>
              </li>
              <li>
                <a href="http://blog.creative-tim.com">
                  Blog
                </a>
              </li>
              <li>
                <a href="https://www.creative-tim.com/license">
                  Licenses
                </a>
              </li>
            </ul>
          </nav>
          <div class="copyright float-right">
            &copy;
            <script>
              document.write(new Date().getFullYear())
            </script>, made with <i class="material-icons">favorite</i> by
            <a href="https://www.creative-tim.com" target="_blank">Creative Tim</a> for a better web.
          </div>
        </div>
      </footer>-->
    </div>
  </div>
  <div id="ventana-pedido-pendiente" class="fixed-top" style="visibility: hidden;">
    <div class="row justify-content-center">
      <div class="col-md-5">
        <div class="card card-chart">
          <div class="card-header card-header-info">
            <i id="close-ventana" class="material-icons" style="cursor:pointer;">close</i>
            <h4 class="card-title" id="pedido-header"></h4>
            <p class="card-category">Checa toda la información del pedido</p>
          </div>
          <div class="card-body">
            <h4 class="card-title">Información del pedido</h4><hr>
            <p class="card-category">
              <div class="row">
                <div class="col-md-5">
                  <h6>Cliente</h6><p id="cliente"></p>
                  <h6>Teléfono</h6><p id="telefono"></p>
                  <h6>Estatus del pedido</h6><p id="estatus-pedido"></p>
                  <h6>Dirección de entrega</h6><p id="direccion-entrega"></p>
                  <h6>Vehículo de entrega</h6><p id="vehiculo"></p>
                  <h6>Repartidor</h6><p id="repartidor"></p>
                </div>
                <div class="col-md-7">
                  <div class="table-responsive">
                    <table id="tabla-pedido-info" class="table table-hover" width="100%">
                      <thead>
                        <tr>
                          <th><b>Artículo</b></th>
                          <th><b>Unidad</b></th>
                          <th><b>Cantidad</b></th>
                          <th><b>Precio</b></th>
                        </tr>
                      </thead>
                      <tbody id="table-pedido-body"></tbody>
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
    function showVentanaPedido(id_pedido)
    {
      var post_url = "../Proveedor/App.php";
      var form_data = "op=GetPedido&id_pedido=" + id_pedido;
      $.post(post_url, form_data, function(json)
      {
        $("#ventana-pedido-pendiente").show();
        $("#ventana-pedido-pendiente").css("visibility", "visible");
        $("#cliente").html(json["pedido"]["cliente"]);
        $("#pedido-header").html("Pedido #" + json["pedido"]["id_venta"]);
        $("#estatus-pedido").html("Órden " + json["pedido"]["pedido_estado"]);
        $("#direccion-entrega").html(json["pedido"]["direccion_entrega"]);
        $("#telefono").html(json["pedido"]["telefono"]);
        $("#vehiculo").html(json["pedido"]["vehiculo"]);
        $("#repartidor").html(json["pedido"]["nombre_repartidor"]);
        var arts = json["pedido"]["articulos"];
        var table_body = "";
        for(var i = 0; i < arts.length; i++)
        {
          table_body += "<tr><td>" + arts[i]["articulo"] + "</td>" + "<td>" + arts[i]["unidad"] + "</td>" + "<td>" + arts[i]["cantidad"] + "</td><td>" + arts[i]["precio"] + "</td>" +"</tr>";
        }
        $("#table-pedido-body").html(table_body);
      });
    }

    function loadTabla(tabla, op)
    {
      $.ajax({
        "url": "../Proveedor/App.php", 
        "type": "POST",
        "data": {
          op: op
        },     
        success : function(data) {

          $(tabla).DataTable({
            data: data["pedidos"],
            columns: [
              { data: "id_venta"},
              { data: "nombre"},
              { data: "subtotal"},
              { data: "fecha_venta"},
              { data: "estado"},
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
      loadTabla("#table-confirmados", "GetPedidosEnCurso");
      loadTabla("#table-listos-recoleccion", "GetPedidosListosRecoleccion");
      loadTabla("#table-recolectados", "GetPedidosRecolectados");
      loadTabla("#table-en-camino", "GetPedidosEnCamino");
      loadTabla("#table-finalizados", "GetPedidosFinalizados");
      loadTabla("#table-cancelados", "GetPedidosCancelados");

      //cerrar ventana pedido
      $("#close-ventana").click(function(event)
      {
        $("#ventana-pedido-pendiente").hide();
        $("#ventana-pedido-pendiente").css("visibility", "hidden"); 
      }); 

      $('#tabla-pedido-info').DataTable({
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