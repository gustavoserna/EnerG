$(document).ready(function() 
{ 
    //get instructores
    $.ajax({
        "url": "../Controladores/InstructorController.php", 
        "type": "GET",
        "data": {
          op: "getInstructores"
        },     
        success : function(data) {
            var instructores = JSON.parse(data);

            instructores.instructores.forEach(instructor => {
                var nombre = instructor["nombre"];
                var apellido = instructor["apellido"];
                var descripcion = instructor["descripcion"];
                var foto = instructor["foto"];

                $("#instructores").append(
                    "<div class='col-lg-4 col-md-6'>" +
                        "<div class='speaker'>" +
                            "<img src='img/instructores/" + foto + "' alt='Speaker 1' class='img-fluid'>" +
                            "<div class='details'>" +
                                "<h3><a href='speaker-details.html'>" + nombre + " " + apellido + "</a></h3>" +
                                "<p>" + descripcion + "</p>" +
                                /*"<div class='social'>" +
                                    "<a href=''><i class='fa fa-twitter'></i></a>" +
                                    "<a href=''><i class='fa fa-facebook'></i></a>" +
                                    "<a href=''><i class='fa fa-google-plus'></i></a>" +
                                    "<a href=''><i class='fa fa-linkedin'></i></a>" +
                                "</div>" +*/
                            "</div>" +
                        "</div>" +
                    "</div>"
                );
            });
        } 
    }); 

    //get horarios
    $.ajax({
        "url": "../Controladores/ClaseController.php", 
        "type": "GET",
        "data": {
          op: "getClasesInstHor"
        },     
        success : function(data) {
            var clases = JSON.parse(data);

            clases.clases.forEach(clase => {
                var clase_titulo = clase["clase"];
                var horario_inicio = clase["horario_inicio"];
                var dia = clase["dia"];
                var instructor = clase["nombre"] + " " + clase["apellido"];
                var foto = clase["foto"];
                var breve_descripcion = clase["breve_descripcion"];

                var schedule = 
                "<div class='row schedule-item'>" +
                    "<div class='col-md-2'><time>" + horario_inicio + "</time></div>" +
                    "<div class='col-md-10'>" +
                        "<div class='speaker'>" +
                        "<img src='img/instructores/" + foto + "' alt='Brenden Legros'>" +
                        "</div>" +
                        "<h4>" + clase_titulo + " <span>" + instructor + "</span></h4>" +
                        "<p>" + breve_descripcion + "</p>" +
                    "</div>" +
                "</div>";

                switch (dia) {
                    case "Lunes":
                        $("#lunes").append(schedule);
                        break;

                    case "Martes":
                        $("#martes").append(schedule);
                        break;

                    case "Miercoles":
                        $("#miercoles").append(schedule);
                        break;

                    case "Jueves":
                        $("#jueves").append(schedule);
                        break;

                    case "Viernes":
                        $("#viernes").append(schedule);
                        break;

                    case "Sabado":
                        $("#sabado").append(schedule);
                        break;

                    case "Domingo":
                        $("#domingo").append(schedule);
                        break;
                
                    default:
                        break;
                }
            });
        } 
    });

    //get planes
    $.ajax({
        "url": "../Controladores/PlanController.php", 
        "type": "GET",
        "data": {
          op: "getPlanes"
        },     
        success : function(data) {
            var planes = JSON.parse(data);
            var plan_type_select = document.getElementById("plan-type");

            planes.planes.forEach(plan => {
                var id_plan = plan["id_plan"];
                var plan_titulo = plan["plan"];
                var descripcion  = plan["descripcion"];
                var total_clases = plan["total_clases"];
                var precio = plan["precio"];
                var vencimiento = plan["vencimiento"];

                //agregar opciones al select de planes del modal de órden
                var option = document.createElement("option");
                option.text = plan_titulo;
                option.id = id_plan;
                plan_type_select.appendChild(option);

                $("#planes").append(
                    "<div class='col-lg-4'>" +
                        "<div class='card mb-5 mb-lg-0'>" +
                            "<div class='card-body'>" +
                                "<h5 class='card-title text-muted text-uppercase text-center'>" + plan_titulo + "</h5>" +
                                "<h6 class='card-price text-center'>$" + precio + "</h6>" +
                                "<hr>" +
                                "<ul class='fa-ul'>" +
                                "<li><span class='fa-li'><i class='fa fa-check'></i></span>" + total_clases + " clases</li>" +
                                "<li><span class='fa-li'><i class='fa fa-check'></i></span>Válido " + vencimiento + " días</li>" +
                                "</ul>" +
                                "<hr>" +
                                "<div class='text-center'>" +
                                    "<button type='button' class='btn' data-toggle='modal' data-target='#buy-ticket-modal' data-ticket-type='" + plan_titulo + "'>Comprar ahora</button>" +
                                "</div>" +
                            "</div>" +
                        "</div>" +
                    "</div>");
            });
        } 
    });

    //get galeria
    $.ajax({
        "url": "../Controladores/ClaseController.php", 
        "type": "GET",
        "data": {
          op: "getGaleria"
        },     
        success : function(data) {
            var galeria = JSON.parse(data);

            galeria.galeria.forEach(img => {
                var foto = img["foto"];
                var descripcion = img["descripcion"];

                /*$("#galeria").html(
                "<a href='img/gallery/2.jpg' class='venobox' data-gall='gallery-carousel'>" +
                    "<img src='img/gallery/2.jpg' alt=''>" +
                "</a>");*/
            });
        } 
    });

    $("#payment-form").on("submit", function(e)
    {
        var e = document.getElementById("plan-type");
        var selected_plan = e.options[e.selectedIndex].getAttribute("id");
        var id_usuario = "1";
        
        $.ajax({
            "url": "../Controladores/PlanController.php", 
            "type": "GET",
            "data": {
              op: "altaPlan",
              id_plan: selected_plan,
              id_usuario: id_usuario
            },     
            success : function(data) {
                
            } 
        });
    });
  });
  