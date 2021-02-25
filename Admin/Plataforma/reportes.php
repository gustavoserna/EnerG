<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <link rel="apple-touch-icon" sizes="76x76" href="./assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="./assets/img/favicon.png">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <title>
    Abastos - Reportes
  </title>
  <meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
  <!--     Fonts and icons     -->
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
  <!-- CSS Files -->
  <link href="./assets/css/material-dashboard.css?v=2.1.2" rel="stylesheet" />
  <link href="./assets/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

  <link href="./assets/css/material-kit.min.css" rel="stylesheet" />
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
    <?php $pag = "reportes"; include("sidebar.php"); ?>
    <div class="main-panel">
      <!-- Navbar -->
      <nav class="navbar navbar-expand-lg navbar-transparent navbar-absolute fixed-top ">
        <div class="container-fluid">
          <div class="navbar-wrapper">
            <a class="navbar-brand" href="javascript:;">Reportes</a>
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
              <div class="card-header card-header-primary">
                <h4 class="card-category">Filtra los reportes por fecha</h4><br/>
                <div class="row">
                  <div class="col-md-5">
                    <span>Del</span>
                    <input type="text" class="form-control datetimepicker" style="color:white;" id="date-del" />
                  </div>
                  <div class="col-md-5">
                    <span>Al</span>
                    <input type="text" class="form-control datetimepicker" style="color:white;" id="date-al" />
                  </div>
                  <div class="col-md-2">
                    <button type="submit" class="btn btn-primary pull-right" onclick="filtrar()">Filtrar</button>
                  </div>
                </div>
              </div>
              <div class="card-body">
                <div class="tab-content">
                 <table class="table" id="table-finalizados" width="100%">
                  <!-- TABLA CONFIRMADOS -->
                  <thead class="text-primary">
                    <th>Id Pedido</th>
                    <th>Cliente</th>
                    <th>Pagar en caja</th>
                    <th>Subtotal</th>
                    <th>Costo de envío</th>
                    <th>Costo de servicio</th>
                    <th>Descuento</th>
                    <th>Total</th>
                    <th>Método de pago</th>
                    <th>Fecha de pedido</th>
                    <th>Acción</th>
                  </thead>
                  <tbody id="table-finalizados-body">
                  </tbody>
                 </table>
                </div>
                <!-- dispersion de ingresos y egresos -->
                <div style="float:right;">
                  <h4><b>Venta</b></h5>
                  <h5>&emsp;&emsp;&emsp;Venta en efectivo: <span id="venta-efectivo">$0</span></h5>
                  <h5>&emsp;&emsp;&emsp;Venta en tarjeta: <span id="venta-tarjeta">$0</span></h5>
                  <h5>&emsp;&emsp;&emsp;Total: <span id="venta-periodo">$0</span></h5>

                  <h4><b>Dispersión servicio de reparto</b></h4>
                  <h5>&emsp;&emsp;&emsp;Total costos envío: <span id="total-costos-envio">$0</span></h5>
                  <h5>&emsp;&emsp;&emsp;Total pago en caja: <span id="total-pagar-caja">$0</span></h5>
                  <h5>&emsp;&emsp;&emsp;Total entrada efectivo: <span id="total-entrada-efectivo-repartidor">$0</span></h5>
                  <h5>&emsp;&emsp;&emsp;Total: <span id="dispersion-repartidor">$0</span></h5>
                  
                  <h4><b>Desglose</b></h4>
                  <h5>&emsp;&emsp;&emsp;Total ingreso: <span id="total-ingreso">$0</span></h5>
                  <h5>&emsp;&emsp;&emsp;Pago servicio reparto: <span id="pago-reparto">$0</span></h5>
                  <h5>&emsp;&emsp;&emsp;Total descuentos: <span id="descuentos">$0</span></h5>
                  <h5>&emsp;&emsp;&emsp;Total efectivo: <span id="efectivo">$0</span></h5>
                  <h5>&emsp;&emsp;&emsp;Total: <span id="haber">$0</span></h3>
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
              <h6>Cliente</h6><p id="cliente"></p>
              <h6>Estatus del pedido</h6><p id="estatus-pedido"></p>
              <h6>Vehículo de entrega</h6><p id="vehiculo"></p>
              <h6>Repartidor</h6><p id="repartidor"></p>
              <div class="table-responsive">
                <table id="tabla-pedido-info" class="table table-hover" width="100%">
                  <thead>
                    <tr>
                      <th><b>Artículo</b></th>
                      <th><b>Unidad</b></th>
                      <th><b>Cantidad</b></th>
                      <th><b>Total</b></th>
                    </tr>
                  </thead>
                  <tbody id="table-pedido-body"></tbody>
                </table>
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
        $("#vehiculo").html(json["pedido"]["vehiculo"]);
        $("#repartidor").html(json["pedido"]["nombre_repartidor"]);
        var arts = json["pedido"]["articulos"];
        var table_body = "";
        for(var i = 0; i < arts.length; i++)
        {
          table_body += "<tr><td>" + arts[i]["articulo"] + "</td>" + "<td>" + arts[i]["unidad"] + "</td>" + "<td>" + arts[i]["cantidad"] + "</td> <td>" + arts[i]["precio"] + "</td> </tr>";
        }
        $("#table-pedido-body").html(table_body);
      });
    }

    function filtrar()
    {
      var tabla = "#table-finalizados";
      var op = "GetReportesFiltrados";
      var del = $("#date-del").val();
      var al = $("#date-al").val();
      $.ajax({
        "url": "../Proveedor/App.php", 
        "type": "POST",
        "data": {
          op: op,
          del: del,
          al: al
        },     
        success : function(data) 
        {
          $(tabla).DataTable({
            columns: "adjust",
            'columnDefs': [
              {
                "targets": 0, 
                "className": "text-center",
              },
              {
                "targets": 1, 
                "className": "text-center",
              },
              {
                "targets": 2, 
                "className": "text-center",
              },
              {
                "targets": 3, 
                "className": "text-center",
              },
              {
                "targets": 4, 
                "className": "text-center",
              },
              {
                "targets": 5, 
                "className": "text-center",
              },
              {
                "targets": 6, 
                "className": "text-center",
              },
              {
                "targets": 7, 
                "className": "text-center",
              },
              {
                "targets": 8, 
                "className": "text-center",
              },
              {
                "targets": 9, 
                "className": "text-center",
              },
              {
                "targets": 10, 
                "className": "text-center",
              }
            ],
            data: data["pedidos"],
            columns: [
              { data: "id_venta"},
              { data: "nombre"},
              { data: "pagar_caja"},
              { data: "subtotal"},
              { data: "costo_envio"},
              { data: "costo_servicio"},
              { data: "descuento"},
              { data: "total"},
              { data: "metodo_pago"},
              { data: "fecha_venta"},
              { data: "otros"}
            ]
          });

          var venta_total = 0;
          var venta_efectivo = 0;
          var venta_tarjeta = 0;
          var dispersion_repartidor = 0;
          var total_costos_envio = 0;
          var total_pagar_caja = 0;
          var descuentos = 0;
          var haber = 0;
          for(var i = 0; i < data["pedidos"].length; i++)
          {
            var metodo_pago = data["pedidos"][i]["metodo_pago"];
            venta_total = +venta_total + +data["pedidos"][i]["total"];

            total_costos_envio = +total_costos_envio + +data["pedidos"][i]["costo_envio"];
            total_costos_envio = +total_costos_envio + +data["pedidos"][i]["costo_servicio"];
            total_pagar_caja = +total_pagar_caja + +data["pedidos"][i]["pagar_caja"];
            descuentos = +descuentos + +data["pedidos"][i]["descuento"];

            if(metodo_pago == "Tarjeta")
            {
              venta_tarjeta = +venta_tarjeta + +data["pedidos"][i]["total"];
            }
            else
            {
              venta_efectivo = +venta_efectivo + +data["pedidos"][i]["total"];
            }
          }

          dispersion_repartidor = total_costos_envio + total_pagar_caja - venta_efectivo;
          haber = venta_total - venta_efectivo - dispersion_repartidor;
          
          var venta_total_format = new Intl.NumberFormat().format(venta_total);
          var venta_efectivo_format = new Intl.NumberFormat().format(venta_efectivo);
          var venta_tarjeta_format = new Intl.NumberFormat().format(venta_tarjeta);
          var dispersion_repartidor_format = new Intl.NumberFormat().format(dispersion_repartidor);
          var total_costos_envio_format = new Intl.NumberFormat().format(total_costos_envio);
          var total_pagar_caja_format = new Intl.NumberFormat().format(total_pagar_caja);
          var descuentos_format = new Intl.NumberFormat().format(descuentos);
          var haber_format = new Intl.NumberFormat().format(haber);

          $("#venta-periodo").html("$"+venta_total_format);
          $("#venta-efectivo").html("$"+venta_efectivo_format);
          $("#venta-tarjeta").html("$"+venta_tarjeta_format);
          $("#total-costos-envio").html(" + $"+total_costos_envio_format);
          $("#total-pagar-caja").html(" + $"+total_pagar_caja);
          $("#total-entrada-efectivo-repartidor").html(" - $"+venta_efectivo_format);
          $("#dispersion-repartidor").html("$"+dispersion_repartidor_format);
          $("#total-ingreso").html(" + $"+venta_total_format);
          $("#pago-reparto").html(" - $"+dispersion_repartidor_format);
          $("#descuentos").html(" - $"+descuentos_format);
          $("#efectivo").html(" - $"+venta_efectivo_format);
          $("#haber").html("$"+haber_format);
        } 
      });
    }
  </script>
  <script>
    $(document).ready(function() 
    { 
      //load fechas
      $('.datetimepicker').datetimepicker({
        icons: {
          time: "fa fa-clock-o",
          date: "fa fa-calendar",
          up: "fa fa-chevron-up",
          down: "fa fa-chevron-down",
          previous: 'fa fa-chevron-left',
          next: 'fa fa-chevron-right',
          today: 'fa fa-screenshot',
          clear: 'fa fa-trash',
          close: 'fa fa-remove'
        }
      });

      //cerrar ventana pedido
      $("#close-ventana").click(function(event)
      {
        $("#ventana-pedido-pendiente").hide();
        $("#ventana-pedido-pendiente").css("visibility", "hidden"); 
      }); 

    });
  </script>
</body>

</html>