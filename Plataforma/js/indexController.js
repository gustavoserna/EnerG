var existe_sesion = false;

function comprarPlan() {
    if(!existe_sesion) {
        window.location.href = "login.php";
    } else {
        var planes = document.getElementById("plan-type");
        var selected_plan = planes.options[planes.selectedIndex].getAttribute("id");

        var tarjetas = document.getElementById("metodos-pago");
        var selected_tarjeta = tarjetas.options[tarjetas.selectedIndex].getAttribute("id");

        if(selected_tarjeta == null) {
            alert("Selecciona un método de pago");
        } else {
            $.ajax({
                "url": "../Controladores/OrdenController.php", 
                "type": "POST",
                "data": {
                    op: "AltaPlan",
                    id_plan: selected_plan,
                    id_tarjeta: selected_tarjeta
                },  
                success : function(data) {
                    alert(data);
                } 
            });
        }
    }
}

function reservarClase(id_instructor_clase, id_horario_clase, id_clase) {
    if(!existe_sesion) {
        window.location.href = "login.php";
    } else {
        $.ajax({
            "url": "../Controladores/ClaseController.php", 
            "type": "POST",
            "data": {
            op: "agendarClase",
            id_instructor_clase: id_instructor_clase,
            id_horario_clase: id_horario_clase,
            id_clase: id_clase
            },     
            success : function(data) {
                alert(data);
            } 
        }); 
    }
}

function loadMetodosPago() {
    $.ajax({
        "url": "../Controladores/UsuarioController.php", 
        "type": "POST",
        "data": {
          op: "getMetodosPago"
        },     
        success : function(data) {
            var tarjetas = JSON.parse(data);
            var metodos_pago_select = document.getElementById("metodos-pago");
            $("#metodos-pago").empty();
  
            tarjetas.tarjetas.forEach(tarjeta => {
                var id_tarjeta = tarjeta["id_tarjeta"];
                var tarjeta = tarjeta["tarjeta"];
  
                //agregar opciones al select de tarjetas del modal de órden
                var option = document.createElement("option");
                option.text = tarjeta;
                option.id = id_tarjeta;
                metodos_pago_select.appendChild(option);
            });
        } 
    });
}

function buscarSesion() {
    if(!existe_sesion) {
        window.location.href = "login.php";
    }
} 

$(document).ready(function() 
{ 
    //verificar si hay una sesion iniciada
    $.ajax({
        "url": "../Controladores/SesionController.php", 
        "type": "POST",
        "data": {
          op: "buscarSesion"
        },     
        success : function(data) {
            if(data == 0) {
                existe_sesion = false;
            } else {
                existe_sesion = true;
            }
        } 
    }); 

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
        "type": "POST",
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
                var id_instructor_clase = clase["id_instructor_clase"];
                var id_horario_clase = clase["id_horario_clase"];
                var id_clase = clase["id_clase"];

                var schedule = 
                "<div class='row schedule-item'>" +
                    "<div class='col-md-2'><time>" + horario_inicio + "</time></div>" +
                    "<div class='col-md-8'>" +
                        "<div class='speaker'>" +
                        "<img src='img/instructores/" + foto + "' alt='Brenden Legros'>" +
                        "</div>" +
                        "<h4>" + clase_titulo + " <span>" + instructor + "</span></h4>" +
                        "<p>" + breve_descripcion + "</p>" +
                    "</div>" +
                    "<div class='col-md-2'>" +
                        "<button onclick=\"reservarClase('" + id_instructor_clase + "', '" + id_horario_clase + "', '" + id_clase + "')\" class='btn'>Reservar</button>"
                    "</div>" 
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
                                    "<button type='button' onclick='buscarSesion()' class='btn' data-toggle='modal' data-target='#buy-ticket-modal' data-ticket-type='" + plan_titulo + "'>Comprar ahora</button>" +
                                "</div>" +
                            "</div>" +
                        "</div>" +
                    "</div>");
            });
        } 
    });

    //get metodos pago
    if(existe_sesion) {
        loadMetodosPago();
    }

    //get galeria
    $.ajax({
        "url": "../Controladores/ClaseController.php", 
        "type": "POST",
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
});
  