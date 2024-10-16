<div class="row">
	<div class="col-md-12">
		<h2>Comparte lo que sabes</h2>
		<p class="bold red">Datos del punto de venta ilegal</p>
	</div>
</div>

<form id="form1" name="form1" method="post" class="mb-3">
	<input name="verificacion" type="text" id="verificacion" maxlength="250" class="verif" />
	<input type="hidden" name="acc" id="acc">

	<div class="row">
		<div class="col-md-6">
			<select name="comunidad_autonoma" id="comunidad_autonoma" class="form-control">
				<option value="0">Comunidad autónoma</option>
				<?php
				$sql = "SELECT * FROM comunidades ORDER BY orden";
				$rs = $mysqli->query($sql);
				while ($fila = $rs->fetch_array(MYSQLI_ASSOC)) {
					$comunidad_id = $fila['id'];
					$comunidad_nombre = $fila['nombre'];
				?><option value="<?php echo $comunidad_id; ?>"><?php echo $comunidad_nombre; ?></option><?php
																									}
																										?>
			</select>
		</div>
		<div class="col-md-6">
			<select name="provincia" id="provincia" class="form-control">
				<option value="0">Provincia</option>
			</select>
		</div>
	</div>

	<div class="row">
		<div class="col-md-6">
			<input name="localidad" class="form-control" id="localidad" placeholder="Localidad" value="" maxlength="250">
		</div>
		<div class="col-md-6">
			<select name="tipo_punto_venta" id="tipo_punto_venta" class="form-control">
				<option value="0">Tipo de punto de venta</option>
				<?php
				$sql = "SELECT * FROM tipos_punto_de_venta ORDER BY orden";
				$rs = $mysqli->query($sql);
				while ($fila = $rs->fetch_array(MYSQLI_ASSOC)) {
					$tipo_punto_venta_id = $fila['id'];
					$tipo_punto_ventanombre = $fila['nombre'];
				?><option value="<?php echo $tipo_punto_venta_id; ?>"><?php echo $tipo_punto_ventanombre; ?></option><?php
																													}
																														?>
			</select>
		</div>
	</div>

	<div class="row">
		<div class="col-md-12">
			<input name="nombre" class="form-control" id="nombre" placeholder="Nombre del punto de venta (si lo tiene...)" maxlength="250" required>
		</div>

		<div class="col-md-12">
			<input name="telefono_contacto" class="form-control" id="telefono_contacto" placeholder="En caso de figurar, teléfono de contacto para acceder a comprar tabaco ilícito" maxlength="250" required>
		</div>

		<div class="col-md-12">
			<input name="direccion" class="form-control" id="direccion" placeholder="Dirección" maxlength="250" required>
		</div>

		<div class="col-md-12">
			<textarea name="comentarios" rows="8" class="form-control" id="comentarios" placeholder="Comentarios"></textarea>
		</div>
	</div>

	<div class="row">
		<div class="col-md-6">
			<input name="tu_telefono" class="form-control" id="tu_telefono" placeholder="Tú teléfono *" value="" maxlength="250">
		</div>
		<div class="col-md-6">
			<span class="texto">*No obligatorio</span>
		</div>
	</div>

	<div class="row">
		<div class="col-md-12">
			<input name="acepto_aviso_legal" type="checkbox" id="acepto_aviso_legal" value="1">
			<label for="acepto_aviso_legal" class="aviso_legal">Consiento el tratamiento de mis datos según la <a href="<?php echo $dominio . "politica-privacidad/"; ?>" target="_blank" title="Política de privacidad">política de privacidad</a></label>
		</div>
	</div>
	<br>
	<div class="row">
		<div>
			<input name="verificacion" type="text" id="verificacion" maxlength="250" class="verif" />
			<a href="#" id="bot_enviar" class="btn btn-primary btn-lg" title="Enviar">Enviar</a>
		</div>
	</div>

	<div class="row">
		<div class="col-md-12">
			<p class="err_form red"></p>
		</div>
	</div>


</form>

<div id="denunciaModal" class="animated-modal text-center p-5" style="display:none">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<p class="titular_listado">Datos del punto de venta ilegal</b></p>
				<p class="tx_respuesta">Gracias por la información</p>
			</div>
		</div>
	</div>
</div>

<script>
	$(document).ready(function() {
		$("#comunidad_autonoma").change(function() {
			$("#comunidad_autonoma option:selected").each(function() {
				elegido = $(this).val();
				$.post("../includes/ajax_combo_provincia.php", {
					ca: elegido
				}, function(data) {
					$("#provincia").html(data);
				});
			});
		});
		$('#bot_enviar').on('click', function() {
			enviar_datos();
			return false;
		});

	});

	function enviar_datos() {
		var ok = true;

		ca = document.getElementById("comunidad_autonoma").value;
		if ((ca == "0") && ok != false) {
			ok = false;
			$(".err_form").html("La comunidad autónoma es obligatoria");
		}

		prov = document.getElementById("provincia").value;
		if ((prov == "0") && ok != false) {
			ok = false;
			$(".err_form").html("La provincia es obligatoria");
		}

		localidad = no_XSS(document.getElementById("localidad").value);
		document.getElementById("localidad").value = localidad;
		if ((localidad == "Localidad" || localidad == "") && ok != false) {
			ok = false;
			$(".err_form").html("La localidad es obligatoria");
		}

		tipo_punto_venta = document.getElementById("tipo_punto_venta").value;
		if ((tipo_punto_venta == "0") && ok != false) {
			ok = false;
			$(".err_form").html("El tipo de punto de venta es obligatorio");
		}

		nombre = no_XSS(document.getElementById("nombre").value);
		document.getElementById("nombre").value = nombre;
		if ((nombre == "Nombre" || nombre == "") && ok != false) {
			ok = false;
			$(".err_form").html("El nombre es obligatorio");
		}

		direccion = no_XSS(document.getElementById("direccion").value);
		document.getElementById("direccion").value = direccion;
		if ((direccion == "Dirección" || direccion == "") && ok != false) {
			ok = false;
			$(".err_form").html("La dirección es obligatorio");
		}

		telefono_contacto = no_XSS(document.getElementById("telefono_contacto").value);
		document.getElementById("telefono_contacto").value = telefono_contacto;

		comentarios = no_XSS(document.getElementById("comentarios").value);
		document.getElementById("comentarios").value = comentarios;

		tu_telefono = no_XSS(document.getElementById("tu_telefono").value);
		document.getElementById("tu_telefono").value = tu_telefono;

		var acepto = document.form1.acepto_aviso_legal.checked;
		if ((acepto == "" || acepto == "false") && ok != false) {
			ok = false;
			$(".err_form").html("Tienes que leer y aceptar el aviso legal");
		}

		verificacion = no_XSS(document.getElementById("verificacion").value);

		if (ok) {
			url = "<?php echo $dominio; ?>includes/ajax_denuncia.php";
			$.ajax({
				type: "POST",
				url: url,
				data: {
					"acc": "denuncia",
					"comunidad_autonoma": ca,
					"provincia": prov,
					"localidad": localidad,
					"tipo_punto_venta": tipo_punto_venta,
					"nombre": nombre,
					"telefono_contacto": telefono_contacto,
					"direccion": direccion,
					"comentarios": comentarios,
					"tu_telefono": tu_telefono,
					"verificacion": verificacion
				},
				success: function(data) {
					
          document.getElementById("form1").reset();
          document.getElementById("form1").style.display = "none";
          document.getElementById("denunciaModal").style.display = "block";
          
					
				}
			});


		}
	}
</script>