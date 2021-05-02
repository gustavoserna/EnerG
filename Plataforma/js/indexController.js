var existe_sesion = 0;

function comprarPlan() {
    if(!existe_sesion) {
        window.location.href = "login.php";
    } else {
        var planes = document.getElementById("plan-type");
        var selected_plan = planes.options[planes.selectedIndex].getAttribute("id");
        var plan_text = planes.options[planes.selectedIndex].text;

        var tarjetas = document.getElementById("metodos-pago");
        var selected_tarjeta = tarjetas.options[tarjetas.selectedIndex].getAttribute("id");
        var tarjeta_text = tarjetas.options[tarjetas.selectedIndex].text;

        if(selected_tarjeta == null) {
            alert("Selecciona un método de pago");
        } else {
            $.ajax({
                "url": "./Controladores/OrdenController.php", 
                "type": "POST",
                "data": {
                    op: "AltaPlan",
                    id_plan: selected_plan,
                    id_tarjeta: selected_tarjeta
                },  
                success : function(data) {
                    data = data.trim();
                    if(data == "plan_activo") {
                        swal("Lo sentimos", "Actualmente cuentas con un plan de clases activo.", "error");
                    } else if(data == "agotado") {
                        swal("Lo sentimos", "Ya usaste esta promoción.", "error");
                    }else {
                        getClasesDisponibles();
                        swal("¡Gracias!", "Tu plan de " + plan_text + " ha sido agregado a tu cuenta. Método de pago utilizado: " + tarjeta_text, "success");
                    }
                    $("#close-modal-orden").click();
                } 
            });
        }
    }
}

function reservarClase(id_instructor_clase, id_horario_clase, id_clase, clase, hora, instructor) {
    if(!existe_sesion) {
        window.location.href = "login.php";
    } else {
        $("#clase-confirmar").val(clase);
        $("#instructor-confirmar").val(instructor);
        $("#hora-confirmar").val(hora);
        $("#confirmar-reservacion-btn").on("click", function () {
            confirmarClase(id_instructor_clase, id_horario_clase, id_clase);
        });
    }
}

function confirmarClase(id_instructor_clase, id_horario_clase, id_clase) {
    $.ajax({
        "url": "./Controladores/ClaseController.php", 
        "type": "POST",
        "data": {
        op: "agendarClase",
        id_instructor_clase: id_instructor_clase,
        id_horario_clase: id_horario_clase,
        id_clase: id_clase
        },     
        success : function(data) {
            if(data == "Ya no tienes clases disponibles.") { 
                swal("Lo sentimos", "Ya no tienes clases disponibles. Compra un paquete de clases para poder reservar.", "error");
            } else if(data == "sin_cupo") {
                swal("Lo sentimos", "Ya no hay cupo para esta clase.", "error");
            } else {
                getClasesDisponibles();
                swal("¡Listo!", "Tu clase ha sido reservada.", "success");
                window.location.href = "perfil.php#schedule";
            }
        } 
    });
}

function loadMetodosPago() {
    $.ajax({
        "url": "./Controladores/UsuarioController.php", 
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

function getClasesDisponibles() {
    try {
        $.ajax({
            "url": "./Controladores/UsuarioController.php", 
            "type": "POST",
            "data": {
              op: "getCreditosUsuario"
            },     
            success : function(data) {
                $("#clases-disponibles").html(data + " ");
            } 
        });
    } catch (error) {
        console.error("sin sesion");
    }
}

$(document).ready(function() 
{ 
    //verificar si hay una sesion iniciada
    $.ajax({
        "url": "./Controladores/SesionController.php", 
        "type": "POST",
        "data": {
          op: "buscarSesion"
        },     
        success : function(data) {
            if(data == 0) {
                existe_sesion = 0;
            } else {
                existe_sesion = 1;
            }
        } 
    }); 

    getClasesDisponibles();

    //get instructores
    $.ajax({
        "url": "./Controladores/InstructorController.php", 
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
                    "<div class='col-lg-4 col-md-6' style='margin-bottom:20px;'>" +
                        "<div class='speaker'>" +
                            "<img src='img/instructores/" + foto + "' alt='Speaker 1' class='img-fluid'>" +
                            "<div class='details'>" +
                                "<br><h3><a href='speaker-details.html'>" + nombre + " " + apellido + "</a></h3>" +
                                "<label>" + descripcion + "</label>" +
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
        "url": "./Controladores/ClaseController.php", 
        "type": "POST",
        "data": {
          op: "getClasesInstHor"
        },     
        success : function(data) {
            var disciplinas = ["Ride", "Bootcamp", "Beatbox", "Yoga"];
            var clases = JSON.parse(data);

            disciplinas.forEach( disciplina => {
                var dias = [];

                for(var i = 0; i < 7; i++) {
                    var date = new Date();
                    var next = new Date(date.getTime() + (i * 24 * 60 * 60 * 1000));
                    var diaSemana = next.getUTCDay();
                    var day = next.getDate();
                    

                    switch (diaSemana) {
                        case 1:
                            diaSemana = "Lunes";
                            break;
                        case 2:
                            diaSemana = "Martes";
                            break;
                        case 3:
                            diaSemana = "Miercoles";
                            break;
                        case 4:
                            diaSemana = "Jueves";
                            break;
                        case 5:
                            diaSemana = "Viernes";
                            break;
                        case 6:
                            diaSemana = "Sabado";
                            break;
                        case 0:
                            diaSemana = "Domingo";
                            break;
                    }
                    dias.push(diaSemana + ' ' + day);
                }

                dias.forEach(element => {
                    element = element.split(' ');
                    var dia_letra = element[0];
                    var dia_numero = element[1];
    
                    var dia_caja = 
                    '<div id="' + dia_letra + '_' + disciplina + '" style="width:14.28%; display: inline-block; vertical-align: top; text-align:center;">' +
                        '<label">' + dia_letra + ' ' + dia_numero + '</label><br>' + 
                    '<div><br>'
    
                    $("#" + disciplina).append(dia_caja);
                });

                clases.clases.forEach(clase => {
                    var clase_titulo = clase["clase"];
                    var horario_inicio = clase["horario_inicio"];
                    var dia = clase["dia"];
                    var instructor = clase["nombre"] + " " + clase["apellido"];
                    var foto = clase["foto"];
                    var disciplina_bd = clase["disciplina"];
                    var id_instructor_clase = clase["id_instructor_clase"];
                    var id_horario_clase = clase["id_horario_clase"];
                    var id_clase = clase["id_clase"];
    
                    var horario = 
                    '<div onclick=\'reservarClase("' + id_instructor_clase + '", "' + id_horario_clase + '", "' + id_clase + '","' + clase_titulo + '","' + horario_inicio + '","' + instructor + '")\' class="horario" data-toggle="modal" data-target="#confirmar-reservacion-modal">' +
                        '<label><b>' + instructor + '</b></label><br>' + 
                        '<label>' + clase_titulo + '</label><br>' + 
                        '<label>' + horario_inicio + '</label><br>' + 
                    '</div>';
    
                    if(disciplina == disciplina_bd) {
                        switch (dia) {
                            case "Lunes":
                                $("#Lunes_"+disciplina_bd).append(horario);
                                break;
        
                            case "Martes":
                                $("#Martes_"+disciplina_bd).append(horario);
                                break;
        
                            case "Miercoles":
                                $("#Miercoles_"+disciplina_bd).append(horario);
                                break;
        
                            case "Jueves":
                                $("#Jueves_"+disciplina_bd).append(horario);
                                break;
        
                            case "Viernes":
                                $("#Viernes_"+disciplina_bd).append(horario);
                                break;
        
                            case "Sabado":
                                $("#Sabado_"+disciplina_bd).append(horario);
                                break;
        
                            case "Domingo":
                                $("#Domingo_"+disciplina_bd).append(horario);
                                break;
                        
                            default:
                                break;
                        }
                    }
                });
            });
            
            /*var dias = [];
            for(var i = 0; i < 6; i++) {
                var date = new Date();
                var next = new Date(date.getTime() + (i * 24 * 60 * 60 * 1000));
                var diaSemana = next.getUTCDay();
                var day = next.getDate();
                switch (diaSemana) {
                    case 1:
                        diaSemana = "Domingo";
                        break;
                    case 2:
                        diaSemana = "Lunes";
                        break;
                    case 3:
                        diaSemana = "Martes";
                        break;
                    case 4:
                        diaSemana = "Miercoles";
                        break;
                    case 5:
                        diaSemana = "Jueves";
                        break;
                    case 6:
                        diaSemana = "Viernes";
                        break;
                    case 0:
                        diaSemana = "Sabado";
                        break;
                }
                dias.push(diaSemana + ' ' + day);
            }
            
            dias.forEach(element => {
                element = element.split(' ');
                var dia_letra = element[0];
                var dia_numero = element[1];

                var dia_caja = 
                '<div id="' + dia_letra + '" style="display: inline-block; vertical-align: top; text-align:center;">' +
                    '<label>' + dia_letra + ' ' + dia_numero + '</label>'
                '<div>'

                $("#Ride").append(dia_caja);
                $("#Bootcamp").append(dia_caja);
                $("#Beatbox").append(dia_caja);
                $("#Yoga").append(dia_caja);
            });

            clases.clases.forEach(clase => {
                var clase_titulo = clase["clase"];
                var horario_inicio = clase["horario_inicio"];
                var dia = clase["dia"];
                var instructor = clase["nombre"] + " " + clase["apellido"];
                var foto = clase["foto"];
                var disciplina = clase["disciplina"];
                var id_instructor_clase = clase["id_instructor_clase"];
                var id_horario_clase = clase["id_horario_clase"];
                var id_clase = clase["id_clase"];

                var horario = 
                '<div style="border: 1px solid #ccc; background:white; padding: 20px;">' +
                    '<img width="100px" style="border-radius:20px; border:1px solid #ccc;" src="img/instructores/' + foto + '" /><br>' + 
                    '<label>' + instructor + '</label><br>' + 
                    '<label>' + clase_titulo + '</label><br>' + 
                    '<label>' + horario_inicio + '</label><br>' + 
                '</div>';

                switch (dia) {
                    case "Lunes":
                        $("#Lunes").append(horario);
                        break;

                    case "Martes":
                        $("#Martes").append(horario);
                        break;

                    case "Miercoles":
                        $("#Miercoles").append(horario);
                        break;

                    case "Jueves":
                        $("#Jueves").append(horario);
                        break;

                    case "Viernes":
                        $("#Viernes").append(horario);
                        break;

                    case "Sabado":
                        $("#Sabado").append(horario);
                        break;

                    case "Domingo":
                        $("#Domingo").append(horario);
                        break;
                
                    default:
                        break;
                }
            });*/

            /*clases.clases.forEach(clase => {
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
            });*/
        } 
    });

    //get planes
    $.ajax({
        "url": "./Controladores/PlanController.php", 
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
    }loadMetodosPago();

    //get galeria
    $.ajax({
        "url": "./Controladores/ClaseController.php", 
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
  