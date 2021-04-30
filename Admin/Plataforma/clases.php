<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <link rel="apple-touch-icon" sizes="76x76" href="./assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="./assets/img/favicon.png">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <title>
    Vitallica - Clases
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
    <?php $pag = "clases"; include("sidebar.php"); ?>
    <div class="main-panel">
      <!-- Navbar -->
      <nav class="navbar navbar-expand-lg navbar-transparent navbar-absolute fixed-top ">
        <div class="container-fluid">
          <div class="navbar-wrapper">
            <a class="navbar-brand" href="javascript:;">Clases</a>
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
                    <span class="nav-tabs-title">Clases :</span>
                    <ul class="nav nav-tabs" data-tabs="tabs">
                      <li class="nav-item">
                        <a class="nav-link active" href="#activas" data-toggle="tab">
                          <i class="material-icons">format_align_justify</i> Lista
                          <div class="ripple-container"></div>
                        </a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" href="#alta" data-toggle="tab">
                          <i class="material-icons">add</i> Alta
                          <div class="ripple-container"></div>
                        </a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" href="#horarios" data-toggle="tab">
                          <i class="material-icons">add</i> Horarios
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
                    <table class="table" id="table-clases" width="100%">
                      <!-- TABLA LISTA CLASES -->
                      <thead class="text-primary">
                        <th>Id clase</th>
                        <th>Clase</th>
                        <th>Breve descripción</th>
                        <th>Acción</th>
                      </thead>
                      <tbody id="table-clases-body">
                      </tbody>
                    </table>
                  </div>
                  <div class="tab-pane" id="alta">
                    <!-- ALTA CLASE -->
                    <form id="alta-clase" method="post">
                      <div class="row">
                        <div class="col-md-12">
                          <div class="form-group">
                            <label class="bmd-label-floating">Clase</label>
                            <input type="text" class="form-control" id="clase" name="clase">
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-12">
                          <div class="form-group">
                            <label class="bmd-label-floating">Descripción breve</label>
                            <textarea id="desc-breve" name="desc-breve" class="form-control"></textarea>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-12">
                          <div class="form-group">
                            <label class="bmd-label-floating">Descripción completa</label>
                            <textarea id="descripcion" name="descripcion" class="form-control"></textarea>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-12">
                          <div class="form-group">
                            <label class="bmd-label-floating">Mínimo</label>
                            <input type="text" class="form-control" id="minimo" name="minimo">
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-12">
                          <div class="form-group">
                            <label class="bmd-label-floating">Máximo</label>
                            <input type="text" class="form-control" id="maximo" name="maximo">
                          </div>
                        </div>
                      </div><br>
                      <button type="submit" class="btn btn-primary pull-right">Guardar</button>
                      <div class="clearfix"></div>
                    </form>
                  </div>
                  <div class="tab-pane" id="horarios">
                    <button onclick="showVentanaAgregarHorario()" class="btn btn-primary pull-left">+ Agregar un horario</button><br><br><br>
                    <table class="table" id="table-horarios" width="100%">
                      <!-- TABLA HORARIOS CLASES -->
                      <thead class="text-primary">
                        <th>Clase</th>
                        <th>Instructor</th>
                        <th>Día</th>
                        <th>Horario</th>
                        <th>Fecha</th>
                        <th>Acción</th>
                      </thead>
                      <tbody id="table-horarios-clases-body">
                      </tbody>
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
  <div id="ventana-clase" class="fixed-top" style="visibility: hidden;">
    <div class="row justify-content-center">
      <div class="col-md-5">
        <div class="card card-chart">
          <div class="card-header card-header-info">
            <i id="close-ventana" class="material-icons" style="cursor:pointer;">close</i>
            <h4 class="card-title" id="clase-header"></h4>
            <p class="card-category">Checa toda la información de la clase</p>
          </div>
          <div class="card-body">
            <h4 class="card-title">Información de la clase</h4><hr>
            <p class="card-category">
              <div class="row">
                <div class="col-md-5">
                  <h6>Clase</h6><p id="clase-v"></p>
                  <h6>Descripción</h6><p id="descripcion-v"></p>
                  <h6>Breve descripción</h6><p id="breve-descripcion-v"></p>
                  <h6>Mínimo</h6><p id="minimo-v"></p>
                  <h6>Máximo</h6><p id="maximo-v"></p>
                </div>
                <div class="col-md-7">
                  <div class="table-responsive">
                    <table id="tabla-clases-info" class="table table-hover" width="100%">
                      <thead>
                        <tr>
                          <th><b>Clase</b></th>
                          <th><b>Alumno</b></th>
                          <th><b>Horario clase</b></th>
                        </tr>
                      </thead>
                      <tbody id="table-clases-body"></tbody>
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

  <div id="ventana-horario" class="fixed-top" style="visibility: hidden;">
    <div class="row justify-content-center">
      <div class="col-md-7">
        <div class="card card-chart">
          <div class="card-header card-header-info">
            <i id="close-ventana-horario" class="material-icons" style="cursor:pointer;">close</i>
            <h4 class="card-title" id="clase-horario-header"></h4>
            <p class="card-category">Checa toda la información del horario de clase</p>
          </div>
          <div class="card-body">
            <h4 class="card-title">Información de la clase</h4><hr>
            <p class="card-category">
              <div class="row">
                <div class="col-md-12">
                  <div class="table-responsive">
                    <table id="tabla-clase-horario" class="table table-hover" width="100%">
                      <thead>
                        <tr>
                          <th><b>Clase</b></th>
                          <th><b>Instructor</b></th>
                          <th><b>Dia</b></th>
                          <th><b>Horario</b></th>
                          <th><b>Fecha</b></th>
                          <th><b>Quitar</b></th>
                        </tr>
                      </thead>
                      <tbody id="table-clase-horario-body"></tbody>
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

  <div id="ventana-agregar-horario" class="fixed-top" style="visibility: hidden;">
    <div class="row justify-content-center">
      <div class="col-md-7">
        <div class="card card-chart">
          <div class="card-header card-header-info">
            <i id="close-ventana-agregar-horario" class="material-icons" style="cursor:pointer;">close</i>
            <h4 class="card-title" id="agregar-horario-header"></h4>
            <p class="card-category">Agrega un horario a una clase</p>
          </div>
          <div class="card-body">
            <h4 class="card-title">Agrega un horario</h4><hr>
            <p class="card-category">
              <div class="row">
                <div class="col-md-12">
                  <form>
                    <div class="form-group">
                      <label class="bmd-label-floating">Clase</label>
                      <select id="clases" class="form-control"></select>
                    </div>

                    <div class="form-group">
                      <label class="bmd-label-floating">Instructor</label>
                      <select id="instructores" class="form-control"></select>
                    </div>

                    <div class="form-group">
                      <label class="bmd-label-floating">Fecha y hora</label>
                      <input type="text" class="form-control datetimepicker" id="fecha-clase-i" />
                    </div>

                    <button type="submit" class="btn btn-primary pull-right">Guardar</button>
                  </form>
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
    function showVentanaClase(id_clase)
    {
      $("#ventana-clase").show();
        $("#ventana-clase").css("visibility", "visible");
    }

    function showVentanaHorario(id_clase)
    {
      var post_url = "../Backend/App.php";
      var form_data = "op=GetHorariosClase&id_clase=" + id_clase;
      $.post(post_url, form_data, function(json)
      {
        $("#ventana-horario").show();
        $("#ventana-horario").css("visibility", "visible");

        var horarios = json["horarios"];
        var table_body = "";
        for(var i = 0; i < horarios.length; i++)
        {
          table_body += "<tr><td>" + horarios[i]["clase"] + "</td>" + "<td>" + horarios[i]["instructor"] + "</td>" + "<td>" + horarios[i]["dia"] + "</td>" + "<td>" + horarios[i]["horario"] + "</td>" + "<td>" + horarios[i]["fecha"] + "</td>" + "<td>" + horarios[i]["otros"] + "</td>" +"</tr>";
        }
        $("#table-clase-horario-body").html(table_body);
      });
    }

    function showVentanaAgregarHorario()
    {
      $("#ventana-agregar-horario").show();
      $("#ventana-agregar-horario").css("visibility", "visible");
    }

    function quitarHorario(id_horario_clase)
    {
      var post_url = "../Backend/App.php";
      var form_data = "op=QuitarHorarioClase&id_horario_clase=" + id_horario_clase;
      $.post(post_url, form_data, function(json)
      {
      });
      alert("Horario eliminado");
    }

    function loadTablaListaClases()
    {
      $.ajax({
        "url": "../Backend/App.php", 
        "type": "POST",
        "data": {
          op: "GetListaClases"
        },     
        success : function(data) {
          $("#table-clases").DataTable({
            columns: "adjust",
              'columnDefs': [
              {
                "targets": 0, 
                "width": "10%"
              },
              {
                "targets": 1, 
                "width": "20%"
              },
              {
                "targets": 2, 
                "width": "60%"
              },
              {
                "targets": 3, 
                "width": "10%"
              }
            ],  
            data: data["clases"],
            columns: [
              { data: "id_clase"},
              { data: "clase" },
              { data: "breve_descripcion"},
              { data: "otros"}
            ]
          });
        } 
      });
    }

    function loadTablaHorariosClases()
    {
      $.ajax({
        "url": "../Backend/App.php", 
        "type": "POST",
        "data": {
          op: "GetHorariosClases"
        },     
        success : function(data) {
          $("#table-horarios").DataTable({
            data: data["horarios"],
            columns: [
              { data: "clase"},
              { data: "instructor" },
              { data: "dia" },
              { data: "horario" },
              { data: "fecha" },
              { data: "otros" }
            ]
          });
        } 
      });
    }

    $("#close-ventana-horario").click(function(event)
      {
        $("#ventana-horario").hide();
        $("#ventana-horario").css("visibility", "hidden"); 
    });
    
    $("#close-ventana-agregar-horario").click(function(event)
      {
        $("#ventana-agregar-horario").hide();
        $("#ventana-agregar-horario").css("visibility", "hidden"); 
    });
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

      //LOAD TABLAS
      loadTablaListaClases();
      loadTablaHorariosClases();

      //cerrar ventana clase
      $("#close-ventana").click(function(event)
      {
        $("#ventana-instructor").hide();
        $("#ventana-instructor").css("visibility", "hidden"); 
      }); 

      $('#tabla-instructor-info').DataTable({
        "scrollY":"300px",
        "scrollCollapse":true,
        "paging":false,
        "searching":false,
        "ordering":false,
        "info":false
      });

      //alta clase
      $("#alta-clase").on("submit", function(e)
      {
        e.preventDefault();
        var f = $(this);
        var formData = new FormData(document.getElementById("alta-clase"));
        formData.append("op", "AltaClase");
        $.ajax({
          url: "../Backend/App.php",
          type: "post",
          dataType: "html",
          data: formData,
          cache: false,
          contentType: false,
          processData: false
        })
        .done(function(res){
          alert("Clase dada de alta.");
          $("#clase").val('');
          $("#desc-breve").val('');
          $("#descripcion").val('');
          $("#minimo").val('');
          $("#maximo").val('');
        });
      });

      //cargar clases
      $.ajax(
      {
        "url": "../Backend/App.php", 
        "type": "POST",
        "data": {
          op: "GetListaClases"
        }, 
        success : function(data) 
        {
          var arr = data["clases"];
          var select = document.getElementById("clases");

          for(var i = 0; i < arr.length; i++)
          {
            var option = document.createElement("option");
            option.text = arr[i]["clase"];
            option.id = arr[i]["id_clase"];
            select.add(option); 
          }
        } 
      });

      //cargar instructores
      $.ajax(
      {
        "url": "../Backend/App.php", 
        "type": "POST",
        "data": {
          op: "GetInstructores"
        }, 
        success : function(data) 
        {
          var arr = data["instructores"];
          var select = document.getElementById("instructores");

          for(var i = 0; i < arr.length; i++)
          {
            var option = document.createElement("option");
            option.text = arr[i]["nombre"];
            option.id = arr[i]["id_instructor"];
            select.add(option); 
          }
        } 
      });
    });
  </script>
</body>

</html>