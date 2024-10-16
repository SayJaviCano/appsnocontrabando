<?php

/**
 * contact form - sends via AJAX (fields must match)
 * ! IGNORE "para" field - could be used to spam.
 * 
 **/
?>

<form id="form1" name="form1" method="post" class="mb-3">

  <input name="verificacion" type="text" id="verificacion" maxlength="250" class="verif" />
  <input type="hidden" name="acc" id="acc">
  <div class="row">
    <div class="col-md-12 text-right">
      <p class="tx_naranja tx-14">Campos obligatorios *</p>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
      <label for="mensaje" class="mr-sm-2">Mensaje *</label>
      <textarea name="mensaje" id="mensaje" class="form-control" required></textarea>
    </div>
  </div>

  <div class="row mt-3">
    <div class="col-md-6 mb-2">
      <label for="nombre" class="mr-sm-2">Nombre *</label>
      <input type="text" id="nombre" name="nombre" class="form-control" maxlength="255" required />
    </div>
    <div class="col-md-6">
      <label for="country" class="mr-sm-2">Pais *</label>
      <input type="text" id="country" name="country" class="form-control" maxlength="255" required />
    </div>
  </div>

  <div class="row mt-3">
    <div class="col-md-6 mb-2   ">
      <label for="email" class="mr-sm-2">Correo *</label>
      <input type="email" id="email" name="email" class="form-control" maxlength="255" required />
    </div>
    <div class="col-md-6">
      <label for="telefono" class="mr-sm-2">Teléfono</label>
      <input type="tel" id="telefono" name="telefono" class="form-control" maxlength="255" />
    </div>
  </div>

  <div class="row">

    <div class="col-md-12">
      <input name="acepto_aviso_legal" type="checkbox" id="acepto_aviso_legal" value="1">
      <label for="acepto_aviso_legal" class="aviso_legal">Consiento el tratamiento de mis datos según la <a href="<?php echo $dominio . "politica-privacidad/"; ?>" target="_blank" title="Política de privacidad">política de privacidad</a></label>
    </div>

    <div class="col-md-12">
      <div class="clearfix">&nbsp;</div>
      <a href="#" id="bot_enviar" class="btn btn-primary btn-lg" title="Enviar">Enviar</a>
    </div>

    <div class="col-md-12">
      <div class="<div class=" col-md-12 mt-3"><br>
        <div class="err_form text-danger">&nbsp;</div>
      </div>
    </div>
  </div>
</form>


<div id="contactoModal" class="animated-modal text-center p-5" style="display:none">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <p class="titular_listado">Contacto</b></p>
        <p class="tx_respuesta">Formulario enviado correctamente.<br><br>Gracias por contactar con nosotros.</p>
      </div>
    </div>
  </div>
</div>

<script>
  $(document).ready(function() {
    $('#bot_enviar').on('click', function() {
      enviar_datos();
      return false;
    });

  });

  function enviar_datos() {

    var ok = true;

    nombre = no_XSS(document.getElementById("nombre").value);
    document.getElementById("nombre").value = nombre;
    if ((nombre == "Tu nombre" || nombre == "") && ok != false) {
      ok = false;
      $(".err_form").html("Tu nombre es obligatorio");
    }

    email = no_XSS(document.getElementById("email").value);
    document.getElementById("email").value = email;
    if ((email == "Tu E-mail" || email == "") && ok != false) {
      ok = false;
      $(".err_form").html("Tu E-mail es obligatorio");
    }

    consulta = no_XSS(document.getElementById("mensaje").value);
    document.getElementById("mensaje").value = consulta;

    telefono = no_XSS(document.getElementById("telefono").value);
    document.getElementById("telefono").value = consulta;

    country = no_XSS(document.getElementById("country").value);
    document.getElementById("country").value = consulta;

    var acepto = document.form1.acepto_aviso_legal.checked;
    if ((acepto == "" || acepto == "false") && ok != false) {
      ok = false;
      $(".err_form").html("Tienes que leer y aceptar el aviso legal");
    }

    verificacion = no_XSS(document.getElementById("verificacion").value);

    if (ok) {
      url = "<?php echo $dominio; ?>includes/ajax_contacto.php";
      $.ajax({
        type: "POST",
        url: url,
        data: {
          "acc": "contacto",
          "para": '',
          "nombre": nombre,
          "email": email,
          "consulta": consulta,
          "verificacion": verificacion,
          "country": country,
          "telefono": telefono
        },
        success: function(data) {
         
          document.getElementById("form1").reset();
          document.getElementById("form1").style.display = "none";
          document.getElementById("contactoModal").style.display = "block";
          
        }
      });


    }
  }
</script>