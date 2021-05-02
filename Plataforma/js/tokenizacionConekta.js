Conekta.setPublicKey('key_ezDFJbsRkAMTbxYkyyLSpzQ');

var conektaSuccessResponseHandler = function(token) {
  $.ajax({
    "url": "./Controladores/OrdenController.php", 
    "type": "POST",
    "data": {
      op: "RegistrarTarjeta",
      token: token.id
    },     
    success : function(data) {
      //alert("Método de pago guardado");
      swal("Gracias", ", Tu método de pago ha sido guardado. Ya puedes comprar el paquete que prefieras.", "success");
      $("#name").val('');
      $("#cardnumber").val('');
      $("#expirationdate").val('');
      $("#securitycode").val('');
    } 
  });
    /*var $form = $("#card-form");
    //Inserta el token_id en la forma para que se envíe al servidor
    $form.append($('<input type="hidden" name="conektaTokenId" id="conektaTokenId">').val(token.id));
    $form.get(0).submit(); //Hace submit*/
};

var conektaErrorResponseHandler = function(response) {
    /*var $form = $("#card-form");
    $form.find(".card-errors").text(response.message_to_purchaser);
    $form.find("button").prop("disabled", false);*/
};

function sendForm() {
        var $form = $(this);
        // Previene hacer submit más de una vez
        $form.find("button").prop("disabled", true);

        var nombre = $("#name").val();
        var tarjeta = $("#cardnumber").val();
        var cvc = $("#securitycode").val();
        var exp = $("#expirationdate").val();
        exp = exp.split("/");
        var month = exp[0];
        var year = exp[1];

        var tokenParams = {
            "card": {
              "number": tarjeta.trim(),
              "name": nombre,
              "exp_year": year,
              "exp_month": month,
              "cvc": cvc
            }
          };

        Conekta.Token.create(tokenParams, conektaSuccessResponseHandler, conektaErrorResponseHandler);
        return false;
}
  
