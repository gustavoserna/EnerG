<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <link rel="apple-touch-icon" sizes="76x76" href="./assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="./assets/img/favicon.png">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <title>
    Vitallica - Lista de clases
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
    <?php $pag = "clases"; include("sidebar.php"); ?>
    <div class="main-panel">
      <!-- Navbar -->
      <nav class="navbar navbar-expand-lg navbar-transparent navbar-absolute fixed-top ">
        <div class="container-fluid">
          <div class="navbar-wrapper">
            <a class="navbar-brand" href="javascript:;">Lista de clasess</a>
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
                    <span class="nav-tabs-title">Categorías :</span>
                    <ul id="categorias" class="nav nav-tabs" data-tabs="tabs">
                    </ul>
                  </div>
                </div>
              </div>
              <div class="card-body">
                <div id="tabs" class="tab-content">
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
  <div id="ventana-producto" class="fixed-top" style="visibility: hidden;">
    <div class="row justify-content-center">
      <div class="col-md-5">
        <div class="card card-chart">
          <div class="card-header card-header-primary">
            <h4 class="card-title" id="producto-header"></h4>
            <i id="close-ventana" class="material-icons" style="cursor:pointer;">close</i>
            <p class="card-category">Checa la información del artículo</p>
          </div>
          <div class="card-body">
            <h4 class="card-title">Información del artículo</h4><hr>
            <p class="card-category">
              <table style="border-spacing:30px; border-collapse: separate;">
                <tr>
                  <td style="vertical-align:top; padding: 10px 0;">
                    <p id="imagen-articulo"></p>
                    <form id="update-imagen" action="../Proveedor/App.php" method="post" enctype="multipart/form-data">
                      <div class="row">
                        <div class="col-md-12">
                          <div class="form-group">
                            <label class="bmd-label-floating">Imágen</label>
                            <div class="form-group form-file-upload form-file-simple">
                              <input type="text" class="form-control inputFileVisible" placeholder="Simple chooser...">
                              <input type="file" name="file" class="inputFileHidden">
                            </div>
                          </div>
                        </div>
                      </div><br>
                      <button type="submit" class="btn btn-primary pull-right">Actualizar imagen</button>
                    </form>
                  </td>
                  <td style="padding: 10px 0;"> 
                    <h6>Id Artículo</h6><p id="id-articulo"></p>
                    <span>Artículo</span><input id="articulo" class="form-control" />
                    <span>Descripción</span><input id="descripcion" class="form-control" />
                    <span>Categoría</span><select id="categorias-articulo" class="form-control"></select>
                    <span>Subcategoría</span><select id="subcategorias-articulo" class="form-control"></select>
                    <span>Unidad</span><select id="unidades-articulo" class="form-control"></select>
                    <span>Tu Precio</span><input id=precio-articulo class="form-control" />
                  </td>
                </tr>
              </table>
              <button onclick="updateProducto()" type="submit" class="btn btn-primary pull-right">Actualizar</button>
            </p><br>
            <div class="card-footer pull-right">
              <div class="stats text-primary">
                El precio promedio se obtiene del promedio de precio de venta de todas las bodegas participantes mas un 20%.
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
    var id_row_tabla = null;
    function loadTabla(tabla, op, idcat)
    {
      var dt = null;
      $.ajax({
        "url": "../Proveedor/App.php", 
        "type": "POST",
        "data": {
          op: op,
          idcat: idcat
        },     
        success : function(data) {

           dt = $(tabla).DataTable({
            columns: "adjust",
            'columnDefs': [
              {
                "targets": 0, 
                "className": "text-center",
                "width": "5%"
              },
              {
                "targets": 1, 
                "className": "text-center",
                "width": "15%"
              },
              {
                "targets": 2, 
                "className": "text-left",
                "width": "50%"
              },
              {
                "targets": 3, 
                "className": "text-center",
                "width": "10%"
              },
              {
                "targets": 4, 
                "className": "text-center",
                "width": "10%"
              },
              {
                "targets": 5, 
                "className": "text-center",
                "width": "10%"
              }
            ],
            data: data["productos"],
            columns: [
              { data: "id_articulo"},
              { data: "imagen_principal"},
              { data: "articulo"},
              { data: "precio"},
              { data: "precio_promedio"},
              { data: "checked"}
            ]
          });
        } 
      });
  
      $(tabla + ' tbody').on( 'click', 'tr', function () {
        id_row_tabla = dt.row( this ).index();
      });
    }

    function checkProducto(id_articulo)
    {
      $.ajax({
        "url": "../Proveedor/App.php", 
        "type": "POST",
        "data": {
          op: "CheckProducto",
          id_articulo: id_articulo
        },     
        success : function(data) {
          alert(data);
        } 
      });
    }

    function loadCategorias()
    {
      var post_url = "../Proveedor/App.php";
      var form_data = "op=GetCategorias";
      $.post(post_url, form_data, function(json)
      {
        var categorias = "";
        var activo = "";
        var arts = json["categorias"];
        for(var i = 0; i < arts.length; i++)
        {
          if(i == 0) activo = "active"; else activo = "";
          categorias += '<li class="nav-item" onclick="setCategoriaTabla(\'C'+arts[i]["id_categoria"]+'\')" style="margin:5px;"><a class="nav-link '+ activo +'" href="#C'+ arts[i]["id_categoria"] +'" data-toggle="tab">'+ arts[i]["categoria"] +'<div class="ripple-container"></div></a></li>';
          crearTabla(arts[i]["id_categoria"], "C" + arts[i]["id_categoria"]);
        }
        $("#categorias").html(categorias);
      });
    }

    var id_cat = "C1";
    function setCategoriaTabla(id_cat_)
    {
      id_cat = id_cat_;
    }

    var activo_tab = "active";
    function crearTabla(idcat, cat)
    {
      var tabs = "";
      tabs = '<div class="tab-pane '+ activo_tab +'" id="'+ cat +'"><table class="table" id="table-'+ cat +'" width="100%"><thead class="text-primary"><th>Id</th><th>Imágen</th><th>Artículo</th><th>Tu Precio</th><th>Precio Promedio</th><th>Activar/Editar</th></thead><tbody></tbody></table></div>';
      $("#tabs").append(tabs);
      activo_tab = "";
      loadTabla("#table-" + cat, "GetProductos", idcat);
    }

    var id_categoria_articulo;
    var id_subcategoria_articulo;
    var id_unidad_articulo;
    var id_unidad;
    function loadUnidadesArticulo(id_unidad)
    {
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
          var select = document.getElementById("unidades-articulo");

          for(var i = 0; i < arr.length; i++)
          {
            var option = document.createElement("option");
            option.text = arr[i]["unidad"];
            option.id = arr[i]["id_unidad"];
            if(arr[i]["id_unidad"] == id_unidad) option.selected = true;
            select.add(option); 
          }
        } 
      });
    }

    function loadCategoriasArticulo(id_categoria)
    {
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
          var select = document.getElementById("categorias-articulo");

          for(var i = 0; i < arr.length; i++)
          {
            var option = document.createElement("option");
            option.text = arr[i]["categoria"];
            option.id = arr[i]["id_categoria"];
            if(arr[i]["id_categoria"] == id_categoria) option.selected = true;
            select.add(option); 
          }
        } 
      });
    }

    function loadSubCategoriasArticulo(id_categoria, id_subcategoria = "")
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
          var select = document.getElementById("subcategorias-articulo");
          id_subcategoria_ = arr[0]["id_subcategoria"];

          for(var i = 0; i < arr.length; i++)
          {
            var option = document.createElement("option");
            option.text = arr[i]["subcategoria"];
            option.id = arr[i]["id_subcategoria"];
            if(arr[i]["id_subcategoria"] == id_subcategoria) option.selected = true;
            select.add(option); 
          }
        } 
      });
    }

    function showVentanaProducto(id_articulo)
    {
      var post_url = "../Proveedor/App.php";
      var form_data = "op=GetArticulo&id_articulo=" + id_articulo;
      $.post(post_url, form_data, function(json)
      {
        $("#ventana-producto").show();
        $("#ventana-producto").css("visibility", "visible");
        $("#id-articulo").html(json["id_articulo"]);
        $("#articulo").val(json["articulo"]);
        $("#descripcion").html(json["descripcion"]);
        $("#precio-articulo").val(json["tu_precio"]);
        $("#imagen-articulo").html("<img width='100px' src='https://app.vapa-ya.com/Imagenes/"+ json["imagen_principal"] +"' />");

        id_categoria_articulo = json["id_categoria"];
        id_subcategoria_articulo = json["id_subcategoria"];
        id_unidad = json["id_unidad_"];
        id_unidad_articulo = json["id_unidad_articulo"];

        $("#categorias-articulo").empty();
        $("#subcategorias-articulo").empty();
        $("#unidades-articulo").empty();
        loadCategoriasArticulo(id_categoria_articulo);
        loadSubCategoriasArticulo(id_categoria_articulo, id_subcategoria_articulo);
        loadUnidadesArticulo(id_unidad);
      });
    }

    function updateProducto()
    {
      var id_articulo = $("#id-articulo").html();
      var precio = $("#precio-articulo").val();
      var descripcion = $("#descripcion").val();
      var articulo = $("#articulo").val();

      $.ajax({
        "url": "../Proveedor/App.php", 
        "type": "POST",
        "data": {
          op: "UpdateArticulo",
          id_articulo: id_articulo,
          precio: precio,
          descripcion : descripcion,
          articulo : articulo,
          categoria : id_categoria_articulo,
          subcategoria : id_subcategoria_articulo,
          unidad : id_unidad,
          id_unidad_articulo : id_unidad_articulo
        },     
        success : function(data) 
        {         

        } 
      });

      $("#ventana-producto").hide();
      $("#ventana-producto").css("visibility", "hidden"); 
      //update precio
      var table = $("#table-" + id_cat).DataTable();
      var temp = table.row(id_row_tabla).data();
      temp["precio"] = precio;
      temp["articulo"] = articulo;
      table.row(id_row_tabla).data(temp).invalidate();

      alert("Producto actualizado. Actualiza la página si quieres ver los cambios completos."); 
    }

    $("#update-imagen").on("submit", function(e)
      {
        var id_articulo = $("#id-articulo").html();
        e.preventDefault();
        var f = $(this);
        var formData = new FormData(document.getElementById("update-imagen"));
        formData.append("op", "UpdateImagenProducto");
        formData.append("id_articulo", id_articulo);
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
          alert("Imágen actualizada.");
          $("#file").val('');
        });
      });
  </script>
  <script>
    $(document).ready(function() 
    { 
      //LOAD TABLAS
      loadCategorias();

      //CERRAR VENTANA
      $("#close-ventana").click(function(event)
      {
        $("#ventana-producto").hide();
        $("#ventana-producto").css("visibility", "hidden"); 
      }); 

      //unidad index changed
      document.getElementById('unidades-articulo').addEventListener('change', function(e) 
      {
        var id_unidad_ = e.target.options[e.target.selectedIndex].getAttribute('id');
        id_unidad = id_unidad_;
      });

      //categoria index changed
      document.getElementById('categorias-articulo').addEventListener('change', function(e) 
      {
        var id_categoria = e.target.options[e.target.selectedIndex].getAttribute('id');
        $("#subcategorias-articulo").empty();
        loadSubCategoriasArticulo(id_categoria);
        id_categoria_articulo = id_categoria;
      });

      //subcategoria index changed
      document.getElementById('subcategorias-articulo').addEventListener('change', function(e) 
      {
        var id_subcategoria = e.target.options[e.target.selectedIndex].getAttribute('id');
        id_subcategoria_articulo = id_subcategoria;
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