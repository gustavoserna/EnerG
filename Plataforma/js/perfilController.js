$(document).ready(function() 
{   
    //get perfil
    $.ajax({
        "url": "../Controladores/UsuarioController.php", 
        "type": "GET",
        "data": {
          op: "getPerfil",
          id_usuario: "1"
        },     
        success : function(data) {
            var perfil = JSON.parse(data);

            $("#nombre").val(perfil[0]["nombre"]);
            $("#apellido").val(perfil[0]["apellido"]);
            $("#mail").val(perfil[0]["email"]);
            $("#telefono").val(perfil[0]["telefono"]);
            $("#username").val(perfil[0]["usuario"]);
        } 
    });
    
    //actualizar perfil
    $("#perfil-form").on("submit", function(e)
    {
        var nombre = $("#nombre").val();
        var apellido = $("#apellido").val();
        var mail = $("#mail").val();
        var telefono = $("#telefono").val();
        var usuario = $("#username").val();
        var clave = $("#password").val();
        var clave2 = $("#password2").val();
        
        if(nombre == "" || apellido == "" || mail == "" || telefono == ""){
            alert("No puede haber campos vacíos");
        }

        if(clave != clave2){
            alert("Las contraseñas no coinciden");
        }

        $.ajax({
            "url": "../Controladores/UsuarioController.php", 
            "type": "GET",
            "data": {
              op: "updatePerfil",
              nombre: nombre,
              apellido: apellido,
              mail: mail,
              telefono: telefono
            },     
            success : function(data) {
                alert("Perfil actualizado");
            } 
        });
    });

    //load clases 
    loadClases();
});

function loadClases(){

    //clases programadas
    $.ajax({
        "url": "../Controladores/UsuarioController.php", 
        "type": "GET",
        "data": {
          op: "getClasesStatusUsuario",
          id_status: 1
        },     
        success : function(data) {
            var clases = JSON.parse(data);

            clases.clases.forEach(clase => {
                var clase_titulo = clase["clase"];
                var horario_inicio = clase["horario_inicio"];
                var fecha = clase["fecha"];
                var instructor = clase["nombre"] + " " + clase["apellido"];
                var foto = clase["foto"];
                var breve_descripcion = clase["breve_descripcion"];
                var horario_fin = clase["horario_fin"];

                $("#programadas").append(
                "<div class='row schedule-item'>" +
                    "<div class='col-md-2'><time>" + horario_inicio + " - " + horario_fin + "<br>" + fecha + "</time></div>" +
                    "<div class='col-md-8'>" +
                        "<div class='speaker'>" +
                        "<img src='img/instructores/" + foto + "'>" +
                        "</div>" +
                        "<h4>" + clase_titulo + " <span>" + instructor + "</span></h4>" +
                        "<p>" + breve_descripcion + "</p>" +
                    "</div>" +
                    "<div class='col-md-2'>" +
                    "<div class='text-center'>" +
                        "<button type='button' class='btn' data-toggle='modal' data-target='#cancelar-modal'>Cancelar</button>" +
                    "</div>" +
                "</div>");
            });
        } 
    });

    //clases completadas
    $.ajax({
        "url": "../Controladores/UsuarioController.php", 
        "type": "GET",
        "data": {
          op: "getClasesStatusUsuario",
          id_status: 2
        },     
        success : function(data) {
            var clases = JSON.parse(data);

            clases.clases.forEach(clase => {
                var clase_titulo = clase["clase"];
                var horario_inicio = clase["horario_inicio"];
                var fecha = clase["fecha"];
                var instructor = clase["nombre"] + " " + clase["apellido"];
                var foto = clase["foto"];
                var breve_descripcion = clase["breve_descripcion"];
                var horario_fin = clase["horario_fin"];

                $("#completadas").append(
                "<div class='row schedule-item'>" +
                    "<div class='col-md-2'><time>" + horario_inicio + " - " + horario_fin + "<br>" + fecha + "</time></div>" +
                    "<div class='col-md-10'>" +
                        "<div class='speaker'>" +
                        "<img src='img/instructores/" + foto + "'>" +
                        "</div>" +
                        "<h4>" + clase_titulo + " <span>" + instructor + "</span></h4>" +
                        "<p>" + breve_descripcion + "</p>" +
                    "</div>" +
                "</div>");
            });
        } 
    });

    //clases canceladas
    $.ajax({
        "url": "../Controladores/UsuarioController.php", 
        "type": "GET",
        "data": {
          op: "getClasesStatusUsuario",
          id_status: 3
        },     
        success : function(data) {
            var clases = JSON.parse(data);

            clases.clases.forEach(clase => {
                var clase_titulo = clase["clase"];
                var horario_inicio = clase["horario_inicio"];
                var fecha = clase["fecha"];
                var instructor = clase["nombre"] + " " + clase["apellido"];
                var foto = clase["foto"];
                var breve_descripcion = clase["breve_descripcion"];
                var horario_fin = clase["horario_fin"];

                $("#canceladas").append(
                "<div class='row schedule-item'>" +
                    "<div class='col-md-2'><time>" + horario_inicio + " - " + horario_fin + "<br>" + fecha + "</time></div>" +
                    "<div class='col-md-10'>" +
                        "<div class='speaker'>" +
                        "<img src='img/instructores/" + foto + "'>" +
                        "</div>" +
                        "<h4>" + clase_titulo + " <span>" + instructor + "</span></h4>" +
                        "<p>" + breve_descripcion + "</p>" +
                    "</div>" +
                "</div>");
            });
        } 
    });
}