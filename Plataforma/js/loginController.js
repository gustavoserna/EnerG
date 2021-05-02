$(document).ready(function() 
{   //login
    $("#login-form").on("submit", function(e)
    {
        var usuario = $("#username").val();
        var clave = $("#password").val();
        
        $.ajax({
            "url": "./Controladores/SesionController.php", 
            "type": "POST",
            "data": {
              op: "login",
              usuario: usuario,
              clave: clave
            },     
            success : function(data) {
                if(data == "1"){
                    window.location.href = "perfil.php";
                } else {
                    alert("Usuario o contraseña incorrectos");
                }
            } 
        });
    });
  });
  