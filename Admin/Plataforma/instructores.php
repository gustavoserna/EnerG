<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <link rel="apple-touch-icon" sizes="76x76" href="./assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="./assets/img/favicon.png">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <title>
    Vitallica - Instructores
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
    <?php $pag = "instructores"; include("sidebar.php"); ?>
    <div class="main-panel">
      <!-- Navbar -->
      <nav class="navbar navbar-expand-lg navbar-transparent navbar-absolute fixed-top ">
        <div class="container-fluid">
          <div class="navbar-wrapper">
            <a class="navbar-brand" href="javascript:;">Instructores</a>
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
                    <span class="nav-tabs-title">Instructores :</span>
                    <ul class="nav nav-tabs" data-tabs="tabs">
                      <li class="nav-item">
                        <a class="nav-link active" href="#activas" data-toggle="tab">
                          <i class="material-icons">format_align_justify</i> Lista
                          <div class="ripple-container"></div>
                        </a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" href="#finalizadas" data-toggle="tab">
                          <i class="material-icons">add</i> Alta
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
                      <!-- TABLA LISTA INSTRUCTORES -->
                      <thead class="text-primary">
                        <th>Id instructor</th>
                        <th>Foto</th>
                        <th>Nombre</th>
                        <th>Teléfono</th>
                        <th>Acción</th>
                      </thead>
                      <tbody id="table-activas-body">
                      </tbody>
                    </table>
                  </div>
                  <div class="tab-pane" id="finalizadas">
                    <!-- ALTA INSTRUCTOR -->
                    <form id="alta-instructor" action="../Backend/App.php" method="post" enctype="multipart/form-data">
                      <div class="row">
                        <div class="col-md-12">
                          <div class="form-group">
                            <label class="bmd-label-floating">Nombre</label>
                            <input type="text" class="form-control" id="nombre-i" name="nombre">
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-12">
                          <div class="form-group">
                            <label class="bmd-label-floating">Apellido</label>
                            <input type="text" class="form-control" id="apellido-i" name="apellido">
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-12">
                          <div class="form-group">
                            <label class="bmd-label-floating">Usario</label>
                            <input type="text" class="form-control" id="usuario-i" name="usuario">
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-12">
                          <div class="form-group">
                            <label class="bmd-label-floating">Clave</label>
                            <input type="text" class="form-control" id="clave-i" name="clave">
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-12">
                          <div class="form-group">
                            <label class="bmd-label-floating">Teléfono</label>
                            <input type="text" class="form-control" id="telefono-i" name="telefono">
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-12">
                          <div class="form-group">
                            <label class="bmd-label-floating">Correo</label>
                            <input type="text" class="form-control" id="correo-i" name="correo">
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-12">
                          <div class="form-group">
                            <label class="bmd-label-floating">Descripcion</label>
                            <textarea class="form-control" id="descripcion-i" name="descripcion"></textarea>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-12">
                          <div class="form-group">
                            <label class="bmd-label-floating">Foto (arrasta la imágen que quieres subir)</label>
                          
                            <div class="form-group form-file-upload form-file-simple">
                              <input type="text" class="form-control inputFileVisible" placeholder="Simple chooser...">
                              <input type="file" name="file" class="inputFileHidden">
                            </div>
                          </div>
                        </div>
                      </div><br>
                      <button type="submit" class="btn btn-primary pull-right">Guardar</button>
                      <div class="clearfix"></div>
                    </form>
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
  <div id="ventana-instructor" class="fixed-top" style="visibility: hidden;">
    <div class="row justify-content-center">
      <div class="col-md-9">
        <div class="card card-chart">
          <div class="card-header card-header-info">
            <i id="close-ventana" class="material-icons" style="cursor:pointer;">close</i>
            <h4 class="card-title" id="instructor-header"></h4>
            <p class="card-category">Checa toda la información del instructor</p>
          </div>
          <div class="card-body">
            <h4 class="card-title">Información del instructor</h4><hr>
            <p class="card-category">
              <div class="row">
                <div class="col-md-3">
                  <form id="form-actualizar-instructor">
                    <h6>Instructor</h6><input type="text" class="form-control" id="instructor-ventana-i" name="nombre"><br>
                    <h6>Usuario</h6><input type="text" class="form-control" id="usuario-ventana-i" disabled name="usuario"><br>
                    <h6>Clave</h6><input type="text" class="form-control" id="clave-ventana-i" name="clave"><br>
                    <h6>Teléfono</h6><input type="text" class="form-control" id="telefono-ventana-i" name="telefono"><br>
                    <h6>Correo</h6><input type="text" class="form-control" id="correo-ventana-i" name="correo"><br>
                    <h6>Descripción breve</h6><textarea class="form-control" id="descbreve-ventana-i" name="desc"></textarea><br>
                    <button type="submit" class="btn btn-primary pull-left">Actualizar</button>
                  </form>
                </div>
                <div class="col-md-9">
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
    function showVentanaInstructor(id_instructor)
    {
      var post_url = "../Backend/App.php";
      var form_data = "op=GetInstructor&id_instructor=" + id_instructor;
      $.post(post_url, form_data, function(json)
      {
        $("#ventana-instrcutor").show();
        $("#ventana-instructor").css("visibility", "visible");
        $("#instructor-ventana-i").val(json["instructor"]["instructor"]);
        $("#usuario-ventana-i").val(json["instructor"]["usuario"]);
        $("#clave-ventana-i").val(json["instructor"]["clave"]);
        $("#telefono-ventana-i").val(json["instructor"]["telefono"]);
        $("#correo-ventana-i").val(json["instructor"]["correo"]);
        $("#descbreve-ventana-i").val(json["instructor"]["descripcion"]);

        var clases = json["instructor"]["clases"];
        var table_body = "";
        for(var i = 0; i < clases.length; i++)
        {
          table_body += "<tr><td>" + clases[i]["clase"] + "</td>" + "<td>" + clases[i]["alumno"] + "</td>" + "<td>" + clases[i]["horario_clase"] + "</td>" +"</tr>";
        }
        $("#table-clases-body").html(table_body);
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
            columns: "adjust",
              'columnDefs': [
              {
                "targets": 0, 
                "width": "20%"
              },
              {
                "targets": 1, 
                "width": "20%"
              },
              {
                "targets": 2, 
                "width": "30%"
              },
              {
                "targets": 3, 
                "width": "20%"
              },
              {
                "targets": 4, 
                "width": "10%"
              }
            ],  
            data: data["instructores"],
            columns: [
              { data: "id_instructor"},
              { data: "foto" },
              { data: "nombre"},
              { data: "telefono"},
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
      loadTabla("#table-activas", "GetInstructores");
      loadTabla("#table-finalizadas", "GetSuscripcionesFinalizadas");

      //cerrar ventana instructor
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

      //alta instructor
      $("#alta-instructor").on("submit", function(e)
      {
        e.preventDefault();
        var f = $(this);
        var formData = new FormData(document.getElementById("alta-instructor"));
        formData.append("op", "AltaInstructor");
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
        alert(res);
          alert("Instructor dado de alta.");
          $("#nombre-i").val('');
          $("#apellido-i").val('');
          $("#usuario-i").val('');
          $("#clave-i").val('');
          $("#telefono-i").val('');
          $("#correo-i").val('');
          $("#descripcion-i").val('');
          $("#file").val('');
        });
      });

      //actualizar instructor
      $("#form-actualizar-instructor").on("submit", function(e)
      {
        e.preventDefault();
        var f = $(this);
        var formData = new FormData(document.getElementById("form-actualizar-instructor"));
        formData.append("usuario", $("#usuario-ventana-i").val());
        formData.append("op", "ActualizarInstructor");
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
          alert("Instructor actualizado.");
        });
      });

      // FileInput
      $('.form-file-simple .inputFileVisible').click(function() {
        $(this).siblings('.inputFileHidden').trigger('click');
      });

      $('.form-file-simple .inputFileHidden').change(function() {
        var filename = $(this).val().replace(/C:\\fakepath\\/i, '');
        $(this).siblings('.inputFileVisible').val(filename);
      });

      $('.form-file-multiple .inputFileVisible, .form-file-multiple .input-group-btn').click(function() {
        $(this).parent().parent().find('.inputFileHidden').trigger('click');
        $(this).parent().parent().addClass('is-focused');
      });

      $('.form-file-multiple .inputFileHidden').change(function() {
        var names = '';
        for (var i = 0; i < $(this).get(0).files.length; ++i) {
          if (i < $(this).get(0).files.length - 1) {
            names += $(this).get(0).files.item(i).name + ',';
          } else {
            names += $(this).get(0).files.item(i).name;
          }
        }
        $(this).siblings('.input-group').find('.inputFileVisible').val(names);
      });

      $('.form-file-multiple .btn').on('focus', function() {
        $(this).parent().siblings().trigger('focus');
      });

      $('.form-file-multiple .btn').on('focusout', function() {
        $(this).parent().siblings().trigger('focusout');
      });

    });
  </script>
</body>

</html>