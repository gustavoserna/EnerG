<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8" />
  <link rel="apple-touch-icon" sizes="76x76" href="./assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="./assets/img/favicon.png">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <title>
    Vitallica - Dashboard
  </title>
  <meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
  <!--     Fonts and icons     -->
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
  <!-- CSS Files -->
  <link href="./assets/css/material-dashboard.css?v=2.1.2" rel="stylesheet" />
  <!-- CSS Just for demo purpose, don't include it in your project -->
  <link href="./assets/demo/demo.css" rel="stylesheet" />
</head>

<?php
  include("../Backend/Admin.php");
  $Admin = new Admin();
  $GraficaVDiarias = $Admin->GetGraficaVDiarias();
  $GraficaPDiarios = $Admin->GetGraficaPDiarios();
  //SESION
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
    <?php $pag = "dashboard"; include("sidebar.php"); ?>
    <div class="main-panel">
      <!-- Navbar -->
      <nav class="navbar navbar-expand-lg navbar-transparent navbar-absolute fixed-top ">
        <div class="container-fluid">
          <div class="navbar-wrapper">
            <a class="navbar-brand" href="javascript:;">Dashboard</a>
          </div>
          <button class="navbar-toggler" type="button" data-toggle="collapse" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
            <span class="sr-only">Toggle navigation</span>
            <span class="navbar-toggler-icon icon-bar"></span>
            <span class="navbar-toggler-icon icon-bar"></span>
            <span class="navbar-toggler-icon icon-bar"></span>
          </button>
        </div>
      </nav>
      <!-- End Navbar -->
      <div class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6">
              <div class="card card-stats">
                <div class="card-header card-header-danger card-header-icon">
                  <div class="card-icon">
                    <i class="material-icons">store</i>
                  </div>
                  <p class="card-category">Ventas</p>
                  <h3 class="card-title" id="header-ventatotal"></h3>
                </div>
                <div class="card-footer">
                  <div class="stats">
                    <i class="material-icons">date_range</i> Día de hoy
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
              <div class="card card-stats">
                <div class="card-header card-header-info card-header-icon">
                  <div class="card-icon">
                    <i class="material-icons">local_mall</i>
                  </div>
                  <p class="card-category">Total de suscripciones</p>
                  <h3 class="card-title" id="header-pedidos-totales"></h3>
                </div>
                <div class="card-footer">
                  <div class="stats">
                    <i class="material-icons">date_range</i> Día de hoy
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
              <div class="card card-stats">
                <div class="card-header card-header-success card-header-icon">
                  <div class="card-icon">
                    <i class="material-icons">shopping_cart</i>
                  </div>
                  <p class="card-category">Promedio</p>
                  <h3 class="card-title" id="header-promedio-venta"></h3>
                </div>
                <div class="card-footer">
                  <div class="stats">
                    <i class="material-icons">shopping_cart</i> Promedio de venta entre suscripciones
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-4">
              <div class="card card-chart">
                <div class="card-header card-header-success">
                  <div class="ct-chart" id="chartVentaDiaria"></div>
                </div>
                <div class="card-body">
                  <h4 class="card-title">Ventas Diarias Por Semana</h4>
                  <p class="card-category">
                    <?php 
                    if($GraficaVDiarias["otros"]["porc_incremento"] < 0)
                    {
                      $color_inc = "text-danger";
                      $leyenda_inc = "decremento en las venta de hoy.";
                      $arrow_inc = "fa-long-arrow-down";
                    }
                    else
                    {
                      $color_inc = "text-success";
                      $leyenda_inc = "incremento en las venta de hoy.";
                      $arrow_inc = "fa-long-arrow-up";
                    }
                    ?>
                    <span class="<?php echo $color_inc; ?>">
                      <i class="fa <?php echo $arrow_inc; ?>"></i> <?php echo $GraficaVDiarias["otros"]["porc_incremento"]."%"; ?> 
                      <?php echo $leyenda_inc; ?></p>
                    </span>
                </div>
                <div class="card-footer">
                  <div class="stats">
                    <i class="material-icons">access_time</i> updated 4 minutes ago
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="card card-chart">
                <div class="card-header card-header-primary">
                  <div class="ct-chart" id="chartPedidosDiarios"></div>
                </div>
                <div class="card-body">
                  <h4 class="card-title">Suscripciones Diarias Por Semana</h4>
                  <p class="card-category">
                    <?php 
                    if($GraficaPDiarios["otros"]["porc_incremento"] < 0)
                    {
                      $color_inc = "text-danger";
                      $leyenda_inc = "decremento en los pedidos de hoy.";
                      $arrow_inc = "fa-long-arrow-down";
                    }
                    else
                    {
                      $color_inc = "text-success";
                      $leyenda_inc = "incremento en los pedidos de hoy.";
                      $arrow_inc = "fa-long-arrow-up";
                    }
                    ?>
                    <span class="<?php echo $color_inc; ?>">
                      <i class="fa <?php echo $arrow_inc; ?>"></i> <?php echo $GraficaPDiarios["otros"]["porc_incremento"]."%"; ?> 
                      <?php echo $leyenda_inc; ?></p>
                    </span>
                </div>
                <div class="card-footer">
                  <div class="stats">
                    <i class="material-icons">access_time</i> updated 4 minutes ago
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
  <!--	Plugin for Select, full documentation here: http://silviomoreto.github.io/bootstrap-select -->
  <script src="./assets/js/plugins/bootstrap-selectpicker.js"></script>
  <!--  Plugin for the DateTimePicker, full documentation here: https://eonasdan.github.io/bootstrap-datetimepicker/ -->
  <script src="./assets/js/plugins/bootstrap-datetimepicker.min.js"></script>
  <!--  DataTables.net Plugin, full documentation here: https://datatables.net/  -->
  <script src="./assets/js/plugins/jquery.dataTables.min.js"></script>
  <!--	Plugin for Tags, full documentation here: https://github.com/bootstrap-tagsinput/bootstrap-tagsinputs  -->
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
  <!-- Chartist JS -->
  <script src="./assets/js/plugins/chartist.min.js"></script>
  <!--  Notifications Plugin    -->
  <script src="./assets/js/plugins/bootstrap-notify.js"></script>
  <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="./assets/js/material-dashboard.js?v=2.1.2" type="text/javascript"></script>
  <script type="text/javascript">
    function loadHeaders()
    {
      $.ajax(
      {
        "url": "../Backend/App.php", 
        "type": "POST",
         "data": {
           op: "GetHeaders"
         },     
         success : function(data) 
         {
          $("#header-ventatotal").html("$" + data["venta_total"]);
          $("#header-pedidos-totales").html(data["altas_totales"]);
          $("#header-promedio-venta").html("$" + data["promedio_venta"]);
         } 
      }); 
      setTimeout(loadHeaders, 1000);
    }

    var animationVentasDiarias = 0;
    function chartVentasDiarias()
    {
      dataChartVentaDiaria = {
        labels: 
        [
          String.fromCharCode(<?php echo $Admin->GetDiaAscii($GraficaVDiarias["dias"][0]["dia"]); ?>),
          String.fromCharCode(<?php echo $Admin->GetDiaAscii($GraficaVDiarias["dias"][1]["dia"]); ?>),
          String.fromCharCode(<?php echo $Admin->GetDiaAscii($GraficaVDiarias["dias"][2]["dia"]); ?>),
          String.fromCharCode(<?php echo $Admin->GetDiaAscii($GraficaVDiarias["dias"][3]["dia"]); ?>),
          String.fromCharCode(<?php echo $Admin->GetDiaAscii($GraficaVDiarias["dias"][4]["dia"]); ?>),
          String.fromCharCode(<?php echo $Admin->GetDiaAscii($GraficaVDiarias["dias"][5]["dia"]); ?>),
          String.fromCharCode(<?php echo $Admin->GetDiaAscii($GraficaVDiarias["dias"][6]["dia"]); ?>)
        ],
        series: [
          [
            <?php echo $GraficaVDiarias["dias"][0]["venta_total"]; ?>,
            <?php echo $GraficaVDiarias["dias"][1]["venta_total"]; ?>,
            <?php echo $GraficaVDiarias["dias"][2]["venta_total"]; ?>,
            <?php echo $GraficaVDiarias["dias"][3]["venta_total"]; ?>,
            <?php echo $GraficaVDiarias["dias"][4]["venta_total"]; ?>,
            <?php echo $GraficaVDiarias["dias"][5]["venta_total"]; ?>,
            <?php echo $GraficaVDiarias["dias"][6]["venta_total"]; ?>
          ]
        ]
      };
      optionsChartVentaDiaria = {
        lineSmooth: Chartist.Interpolation.cardinal({
          tension: 0
        }),
        low: 0,
        high: <?php echo $GraficaVDiarias["otros"]["valor_maximo"]; ?>, 
        chartPadding: {
          top: 0,
          right: 0,
          bottom: 0,
          left: 0
        },
      }
      var chartVentaDiaria = new Chartist.Line('#chartVentaDiaria', dataChartVentaDiaria, optionsChartVentaDiaria);
      if(animationVentasDiarias == 0)
      {
        md.startAnimationForLineChart(chartVentaDiaria); 
        animationVentasDiarias = 1;
      }

      setTimeout(chartVentasDiarias, 1000);
    }

    function chartPedidosDiarios()
    {
      dataChartPedidosDiarios = {
        labels: 
        [
          String.fromCharCode(<?php echo $Admin->GetDiaAscii($GraficaPDiarios["dias"][0]["dia"]); ?>),
          String.fromCharCode(<?php echo $Admin->GetDiaAscii($GraficaPDiarios["dias"][1]["dia"]); ?>),
          String.fromCharCode(<?php echo $Admin->GetDiaAscii($GraficaPDiarios["dias"][2]["dia"]); ?>),
          String.fromCharCode(<?php echo $Admin->GetDiaAscii($GraficaPDiarios["dias"][3]["dia"]); ?>),
          String.fromCharCode(<?php echo $Admin->GetDiaAscii($GraficaPDiarios["dias"][4]["dia"]); ?>),
          String.fromCharCode(<?php echo $Admin->GetDiaAscii($GraficaPDiarios["dias"][5]["dia"]); ?>),
          String.fromCharCode(<?php echo $Admin->GetDiaAscii($GraficaPDiarios["dias"][6]["dia"]); ?>)
        ],
        series: [
          [
            <?php echo $GraficaPDiarios["dias"][0]["altas_totales"]; ?>,
            <?php echo $GraficaPDiarios["dias"][1]["altas_totales"]; ?>,
            <?php echo $GraficaPDiarios["dias"][2]["altas_totales"]; ?>,
            <?php echo $GraficaPDiarios["dias"][3]["altas_totales"]; ?>,
            <?php echo $GraficaPDiarios["dias"][4]["altas_totales"]; ?>,
            <?php echo $GraficaPDiarios["dias"][5]["altas_totales"]; ?>,
            <?php echo $GraficaPDiarios["dias"][6]["altas_totales"]; ?>
          ]
        ]
      };
      optionsChartPedidosDiarios = {
        lineSmooth: Chartist.Interpolation.cardinal({
          tension: 0
        }),
        low: 0,
        high: <?php echo $GraficaPDiarios["otros"]["valor_maximo"]; ?>, 
        chartPadding: {
          top: 0,
          right: 0,
          bottom: 0,
          left: 0
        },
      }
      var chartPedidosDiarios = new Chartist.Bar('#chartPedidosDiarios', dataChartPedidosDiarios, optionsChartPedidosDiarios);
      md.startAnimationForLineChart(chartPedidosDiarios);  

      setTimeout(chartPedidosDiarios, 1000);
    }
  </script>
  <script>
    $(document).ready(function() {
      //LOAD TABLAS CONSOLA
      loadHeaders();

      //LOAD CHARTS
      chartVentasDiarias();
      chartPedidosDiarios();
    });
  </script>
  <script>
    $(document).ready(function() {
      // Javascript method's body can be found in assets/js/demos.js
      md.initDashboardPageCharts();
    });
  </script>
</body>

</html>





