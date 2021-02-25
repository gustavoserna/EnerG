<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <link rel="apple-touch-icon" sizes="76x76" href="./assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="./assets/img/favicon.png">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <title>
    Abastos - Alta de productos
  </title>
  <meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
  <!--     Fonts and icons     -->
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
  <!-- CSS Files -->
  <link href="./assets/css/material-dashboard.css?v=2.1.2" rel="stylesheet" />
  <link href="./assets/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/docsearch.js/2/docsearch.min.css" />
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
    <?php $pag = "alta"; include("sidebar.php"); ?>
    <div class="main-panel">
      <!-- Navbar -->
      <nav class="navbar navbar-expand-lg navbar-transparent navbar-absolute fixed-top ">
        <div class="container-fluid">
          <div class="navbar-wrapper">
            <a class="navbar-brand" href="javascript:;">Alta de productos</a>
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
          <div class="row">
            <div class="col-md-12">
              <div class="card">
                <div class="card-header card-header-primary">
                  <h4 class="card-title">Dar de alta un producto</h4>
                  <p class="card-category">Completa la información solicitada</p>
                </div>
                <div class="card-body">
                  <form id="alta-producto" action="../Proveedor/App.php" method="post" enctype="multipart/form-data">
                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group">
                          <label class="bmd-label-floating">Producto</label>
                          <input type="text" class="form-control" id="producto" name="producto">
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group">
                          <label class="bmd-label-floating">Categoría</label>
                          <select id="categorias" class="form-control"></select>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group">
                          <label class="bmd-label-floating">Subcategoría</label>
                          <select id="subcategorias" class="form-control"></select>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group">
                          <label class="bmd-label-floating">Unidad</label>
                          <select id="unidades" class="form-control"></select>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group">
                          <label class="bmd-label-floating">Precio</label>
                          <input id="precio" type="text" class="form-control" name="precio">
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group">
                          <label class="bmd-label-floating">Imágen (arrasta la imágen que quieres subir)</label>
                          

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
    var id_categoria_ = 1;
    var id_subcategoria_ = 1;
    var id_unidad_ = 1;

    function loadSubCategorias(id_categoria)
    {
      $.ajax(
      {
        "url": "../Proveedor/App.php", 
        "type": "POST",
        "data": {
          op: "GetSubCategorias",
          id_categoria: id_categoria
        }, 
        success : function(data) 
        {
          var arr = data["subcategorias"];
          var select = document.getElementById("subcategorias");
          id_subcategoria_ = arr[0]["id_subcategoria"];

          for(var i = 0; i < arr.length; i++)
          {
            var option = document.createElement("option");
            option.text = arr[i]["subcategoria"];
            option.id = arr[i]["id_subcategoria"];
            select.add(option); 
          }
        } 
      });
    }
  </script>
  <script>
    $(document).ready(function() 
    { 
      //cargar categorias
      $.ajax(
      {
        "url": "../Proveedor/App.php", 
        "type": "POST",
        "data": {
          op: "GetCategorias"
        }, 
        success : function(data) 
        {
          var arr = data["categorias"];
          var select = document.getElementById("categorias");

          for(var i = 0; i < arr.length; i++)
          {
            var option = document.createElement("option");
            option.text = arr[i]["categoria"];
            option.id = arr[i]["id_categoria"];
            select.add(option); 
          }
        } 
      });

      //cargar unidades
      $.ajax(
      {
        "url": "../Proveedor/App.php", 
        "type": "POST",
        "data": {
          op: "GetUnidades"
        }, 
        success : function(data) 
        {
          var arr = data["unidades"];
          var select = document.getElementById("unidades");

          for(var i = 0; i < arr.length; i++)
          {
            var option = document.createElement("option");
            option.text = arr[i]["unidad"];
            option.id = arr[i]["id_unidad"];
            select.add(option); 
          }
        } 
      });

      //categoria index changed
      loadSubCategorias(1);
      document.getElementById('categorias').addEventListener('change', function(e) 
      {
        var id_categoria = e.target.options[e.target.selectedIndex].getAttribute('id');
        $("#subcategorias").empty();
        loadSubCategorias(id_categoria);
        id_categoria_ = id_categoria;
      });

      //subcategoria index changed
      document.getElementById('subcategorias').addEventListener('change', function(e) 
      {
        var id_subcategoria = e.target.options[e.target.selectedIndex].getAttribute('id');
        id_subcategoria_ = id_subcategoria;
      });

      //unidad index changed
      document.getElementById('unidades').addEventListener('change', function(e) 
      {
        var id_unidad = e.target.options[e.target.selectedIndex].getAttribute('id');
        id_unidad_ = id_unidad;
      });

      //alta producto
      $("#alta-producto").on("submit", function(e)
      {
        e.preventDefault();
        var f = $(this);
        var formData = new FormData(document.getElementById("alta-producto"));
        formData.append("op", "AltaProducto");
        formData.append("categoria", id_categoria_);
        formData.append("subcategoria", id_subcategoria_);
        formData.append("unidad", id_unidad_);
        //formData.append(f.attr("name"), $(this)[0].files[0]);
        $.ajax({
          url: "../Proveedor/App.php",
          type: "post",
          dataType: "html",
          data: formData,
          cache: false,
          contentType: false,
          processData: false
        })
        .done(function(res){
          alert("Producto agregado.");
          $("#producto").val('');
          $("#precio").val('');
          $("#file").val('');
        });
      });

      // FileInput
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





