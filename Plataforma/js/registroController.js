$(document).ready(function() 
{   //login
    $("#registro-form").on("submit", function(e)
    {
        var nombre = $("#nombre").val();
        var apellido = $("#apellido").val();
        var mail = $("#mail").val();
        var telefono = $("#telefono").val();
        var usuario = $("#username").val();
        var clave = $("#password").val();
        var clave2 = $("#password2").val();
        
        if(nombre == "" || apellido == "" || mail == "" || telefono == "" || usuario == "" || clave == "" || clave2 == ""){
            alert("Por favor completa todos los campos");
        }

        if(clave != clave2){
            alert("Las contraseñas no coinciden");
        }

        $.ajax({
            "url": "../Controladores/SesionController.php", 
            "type": "POST",
            "data": {
              op: "registro",
              nombre: nombre,
              apellido: apellido,
              mail: mail,
              telefono: telefono,
              usuario: usuario,
              clave: clave
            },     
            success : function(data) {
                if(data == "existe"){
                    alert("Usuario, teléfono o correo electrónico ya registrados");
                } else {
                    alert("Usuario creado");
                }
            } 
        });
    });
  });
  