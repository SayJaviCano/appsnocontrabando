<footer>
	<div class="container">
		<div class="row footer-1">

			<div class="col-12 col-md-4 col-footer-1">
				<p><a href="https://www.imperialbrands.com" alt="Imperial Brands"><img src="<?php echo $dominio; ?>images/imperial-brands-logo.png" alt="Imperial Brands" class="img-fluid footer-logo"></a></p>
				<p>
					<strong>Head-Office</strong><br>
					Altadis S.A.<br>
					Calle Comandante Azcárraga, 5<br>
					28016 Madrid - España<br>
					Tel: +34 (0) 91 360 90 00
				</p>
			</div>

			<div class="col-12 col-md-4 col-footer-1">
				<h3>Links Útiles</h3>
				<ul>

					<li><a href="<?php echo $dominio ?>buzon-de-denuncias">Denuncia</a></li>
					<li><a href="https://www.altadis.com/">ALTADIS - Imperial Brands</a></li>
					<ul>
			</div>

			<div class="col-12 col-md-4">
				<h3>Síguenos</h3>
				<ul class="social">
					<li class="nav-item">
						<a href="https://www.linkedin.com/showcase/no-contrabando-altadis/" title="Síguenos en Linkedin" target="_blank" class="linkedin" rel="noreferrer noopener">
							<i class="fa-brands fa-linkedin"></i>
						</a>
					</li>

					<li class="nav-item">
						<a href="https://www.facebook.com/pages/No-Contrabando/382387838608294" title="Síguenos en Facebook" target="_blank" class="facebook" rel="noreferrer noopener">
							<i class="fa-brands fa-facebook"></i>
						</a>
					</li>

					<li class="nav-item">
						<a href="https://twitter.com/No_Contrabando" title="Síguenos en Twitter" target="_blank" class="twitter" rel="noreferrer noopener">
							<i class="fa-brands fa-x-twitter"></i>
						</a>
					</li>

					<li class="nav-item">
						<a href="https://www.youtube.com/channel/UC0Ug8tmC0r2yWKCGp_5kqxQ/feed" title="Síguenos en YouTube" target="_blank" class="youTube" rel="noreferrer noopener">
							<i class="fa-brands fa-youtube"></i>
						</a>
					</li>
				</ul>





			</div>
		</div>
	</div>

	<div class="bg_brown tx_white">
		<div class="container">
			<div class="row">
				<div class="col text-left">

					<ul class="footer">

						<li>© Altadis</li>
						<li><a href="<?php echo $dominio ?>aviso-legal/" title="Aviso Legal">Aviso Legal</a></li>

						<li><a href="<?php echo $dominio ?>politica-privacidad/" title="Política de Privacidad">Política de Privacidad</a></li>

						<li><a href="<?php echo $dominio ?>politica-cookies/" title="Política de cookies">Política de cookies</a></li>

						<li><a href="#" class="pop_config_cookies" title="Configurar cookies">Configurar cookies</a></li>

						<li><a href="<?php echo $dominio . "contacto/"; ?>" title="Configurar cookies">Contacto</a></li>

					</ul>

				</div>
			</div>
		</div>
	</div>
</footer>

<div id="cookie-policy" class="cp-notice cp-hidden">
	<div class="cp-bg">
		<div class="container">
			<div class="row">
				<div class="col-md-8">
					<p>Utilizamos cookies propias y de terceros para mejorar nuestros servicios mediante el análisis de sus hábitos de navegación. Puede aceptar las cookies haciendo clic en el botón «Aceptar todas» o configurarlas o rechazar su uso haciendo clic en «Configurar cookies». <a href="<?php echo $dominio ?>/politica-cookies/" title="Política de cookies" class="enlace">Política de cookies</a>.</p>
				</div>
				<div class="col-md-3 offset-md-1">
					<p><a class="pop_config_cookies btn btn-secondary w100" href="#">Configurar cookies</a></p>
					<p><a class="cp-close btn btn-primary w100" href="#">Aceptar todas</a></p>
				</div>
			</div>
		</div>
	</div>
</div>


<!-- Cookies Block -->
<div id="cookie-config" class="cp-hidden">
	<div class="config-bg vertical-align">
		<div class="container">
			<div class="config-box">

				<p class="tit">Configuración de cookies</p>
				<p>Desde este panel podrá configurar las cookies que el sitio web puede instalar en su navegador, excepto las cookies técnicas o funcionales que son necesarias para la navegación y la utilización de las diferentes opciones o servicios que se ofrecen.</p>
				<p>Las cookies seleccionadas indican que el usuario autoriza la instalación en su navegador y el tratamiento de datos bajo las condiciones reflejadas en la <a href="<?php echo $dominio ?>politica-cookies/" title="Política de cookies">Política de cookies</a>.</p>

				<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
					<div class="panel panel-default">
						<div class="panel-heading" role="tab" id="headingOne">
							<div class="panel-title"><a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="false" aria-controls="collapseOne" class="collapsed"><i class="more-less bi-chevron-compact-down"></i>Técnicas y funcionales</a></div>
							<div class="s_activo">Siempre activo</div>
						</div>
						<div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne" aria-expanded="false" style="height: 0px;">
							<div class="panel-body">Las cookies Técnicas y funcionales ayudan a hacer una página web utilizable activando funciones básicas como la navegación en la página y el acceso a áreas seguras de la página web. La página web no puede funcionar adecuadamente sin estas cookies.</div>
						</div>
					</div>

					<div class="panel panel-default">
						<div class="panel-heading" role="tab" id="headingTwo">
							<div class="panel-title"><a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo"><i class="more-less bi-chevron-compact-down"></i>Estadísticas</a></div>
							<?php
							$cookie_statistics = "";
							if (isset($_COOKIE['cookiepolicy-statistics'])) {
								$cookie_statistics = $_COOKIE['cookiepolicy-statistics'];
							}
							$checked = "";
							if ($cookie_statistics == "true") {
								$checked = "checked";
							}
							?>
							<div class="form-group">
								<div class="checkbox checbox-switch switch-danger">
									<label>
										<input type="checkbox" name="cookie_statistics" id="cookie_statistics" <?php echo $checked; ?> />
										<span></span>
									</label>
								</div>
							</div>
						</div>
						<div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo" aria-expanded="false">
							<div class="panel-body">Las cookies estadísticas ayudan a los propietarios de páginas web a comprender cómo interactúan los visitantes con las páginas web reuniendo y proporcionando información de forma anónima.</div>
						</div>
					</div>
				</div>

				<div class="row mt-5">
					<div class="col-md-6">
						<a class="cp-guardar btn btn-secondary" href="#">Guardar configuración actual</a>
					</div>
					<div class="col-md-6 text-right">
						<a class="cp-acept btn btn-primary" href="#">Aceptar todo</a>
					</div>
				</div>

			</div>
		</div>
	</div>
</div>