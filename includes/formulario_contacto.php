<form id="form1" name="form1" method="post" class="mb-3">

	<input name="verificacion" type="text" id="verificacion" maxlength="250" class="verif"/>
	<input type="hidden" name="acc" id="acc"> 

	<?php
		$para = LimpiaParametros((isset($_GET['para']) ? $_GET['para'] :""));

	?>


<div class="row">
	<div class="col-md-12 mb-3">
		<ul class="para">
			<li>
				<div class="radio_group">
					<input type="radio" class="none" name="para" id="para_general" value="nocontrabando.altadis@gmail.com">
					<label for="para_general"> Información general</label>
				</div>									
			</li>
			<li>
				<div class="radio_group">
					<input type="radio" class="none" name="para" id="para_preguntas" value="ilicito@es.imptob.com" <?php if ($para=="preguntas") { echo "checked"; }?>>
					<label for="para_preguntas"> Sección Pregunta</label>
				</div>									
			</li>	
		</ul>
	</div>	
	<div class="col-md-6">
		<input name="nombre" class="form-control" id="nombre" placeholder="Tu nombre" maxlength="250" required>
	</div>
	<div class="col-md-6">
		<input name="email" class="form-control" id="email" placeholder="Tu E-mail"  maxlength="250" required>
	</div>	
	<div class="col-md-12">
		<textarea name="consulta" rows="6" class="form-control" id="consulta" placeholder="Tu consulta"></textarea>
	</div>	
</div>
			
<div class="row">
	<div class="col-md-10 text-right t-center">
		<input name="acepto_aviso_legal" type="checkbox" id="acepto_aviso_legal" value="1">
		<label for="acepto_aviso_legal" class="aviso_legal">Consiento el tratamiento de mis datos según la <a href="<?php echo $dominio . "politica-privacidad/";?>" target="_blank" title="Política de privacidad">política de privacidad</a></label>
	</div>
	<div class="col-md-2 text-right t-center">
		<input name="verificacion" type="text" id="verificacion" maxlength="250" class="verif"/>
		<a href="#" id="bot_enviar" class="btn btn-primary btn-lg" title="Enviar">Enviar</a>
	</div>		
	<div class="col-md-12">
		<p class="err_form red"></p>
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
$(document).ready(function () { 
	$('#bot_enviar').on('click', function(){ enviar_datos(); return false;} );

});	

function enviar_datos() {
	var ok=true;

    if(!document.querySelector('input[name="para"]:checked') ) {
		ok=false;
		$(".err_form").html("Selecciona a quíen quieres dirigirte");
    } else {
		para = document.querySelector('input[name="para"]:checked').value;
	}


	nombre = no_XSS(document.getElementById("nombre").value);
	document.getElementById("nombre").value = nombre;
	if (  (nombre=="Tu nombre" || nombre=="") && ok!=false){
		ok=false;
		$(".err_form").html("Tu nombre es obligatorio");
	}

	email = no_XSS(document.getElementById("email").value);
	document.getElementById("email").value = email;
	if (  (email=="Tu E-mail" || email=="") && ok!=false){
		ok=false;
		$(".err_form").html("Tu E-mail es obligatorio");
	}

	consulta = no_XSS(document.getElementById("consulta").value);
	document.getElementById("consulta").value = consulta;	

	var acepto = document.form1.acepto_aviso_legal.checked;
	if ((acepto=="" || acepto=="false") && ok!=false){
		ok=false;
		$(".err_form").html("Tienes que leer y aceptar el aviso legal"); 
	}

	verificacion = no_XSS(document.getElementById("verificacion").value);


	if (ok){	
		url = "<?php echo $dominio;?>includes/ajax_contacto.php";
		$.ajax({
			type: "POST",
			url: url, 
			data: {"acc": "contacto", "para": para, "nombre": nombre,  "email": email,  "consulta": consulta, "verificacion": verificacion },
			success: function(data) {
				document.getElementById("form1").reset(); 
				$.fancybox.open({
					src  : '#contactoModal',
					opts : {
						afterShow : function( instance, current ) {
							//setTimeout(function(){ $.fancybox.close(); }, 4000);								
						}
					}
				});				
			}
		});

		
	}
}
</script>