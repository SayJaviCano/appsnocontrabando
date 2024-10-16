<footer>
	<div class="bg-gris">
		<div class="container">
			<div class="row justify-content-center align-items-center">
				<?php if ($_SERVER['SERVER_NAME'] == "nocontrabando.altadis.com" || $_SERVER['SERVER_NAME'] == "asus") { ?>
					<div class="col-md-4 footer1">
						<a href="<?php echo $dominio;?>" title="No Contrabando"><img src="<?php echo $dominio;?>images/no_contrabando_rojo.png" class="img-fluid" alt="No Contrabando"></a>
					</div>		
					<div class="col-md-4 text-center footer1">
						<p class="tx_red mt-0 mb-1">Descargar APP Gratis</p>
						<?php
							$url_pop = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . "?utm_source=webNoContrabando&utm_medium=pie&utm_campaign=perdemosTodos&pop=appNoContrabando";
						?>
						<a href="<?php echo $url_pop; ?>" title="Disponible en Google Play"><img src="<?php echo $dominio?>images/google-play.png" alt="Disponible en Google Play"></a>
						<a href="<?php echo $url_pop; ?>" title="Disponible en App Store"><img src="<?php echo $dominio?>images/app-store.png" alt="Disponible en App Store"></a>
						<?php
						/*
						<a href="https://play.google.com/store/apps/details?id=com.saysawa.nocontrabando" title="Disponible en Google Play" target="_blank" rel="noreferrer noopener"><img src="<?php echo $dominio?>images/google-play.png" alt="Disponible en Google Play"></a>
						<a href="https://apps.apple.com/gb/app/no-contrabando/id1568160667" title="Disponible en App Store" target="_blank" rel="noreferrer noopener"><img src="<?php echo $dominio?>images/app-store.png" alt="Disponible en App Store"></a>
						*/
						?>
					</div>
					<div class="col-md-4 col_text_right footer1">	
						<a href="https://www.altadis.com/" title="Altadis" target="_blank" rel="noreferrer noopener"><img src="<?php echo $dominio;?>images/altadis_rojo.png" class="img-fluid" alt="Altadis"></a>
					</div>					
				<?php } else { ?>
					<div class="col-6 col-md-6 footer1">
						<a href="<?php echo $dominio;?>" title="No Contrabando"><img src="<?php echo $dominio;?>images/no_contrabando_rojo.png" class="img-fluid" alt="No Contrabando"></a>
					</div>
					<div class="col-6 col-md-6 col_text_right footer1">	
						<a href="https://www.altadis.com/" title="Altadis" target="_blank" rel="noreferrer noopener"><img src="<?php echo $dominio;?>images/altadis_rojo.png" class="img-fluid" alt="Altadis"></a>
					</div>							
				<?php } ?>				
			</div>
		</div>
	</div>
	<div class="container">
		<div class="row">
			<div class="col clearfix">
				<ul class="float-left m1">				
					<li><a href="<?php echo $dominio?>aviso-legal/" title="Aviso Legal">Aviso Legal</a></li>
					<li>|</li>
					<li><a href="<?php echo $dominio?>politica-privacidad/" title="Política de Privacidad">Política de Privacidad</a></li>		
					<li>|</li>					
					<li><a href="<?php echo $dominio?>politica-cookies/" title="Política de cookies">Política de cookies</a></li>
					<li>|</li>
					<li><a href="#" class="pop_config_cookies" title="Configurar cookies">Configurar cookies</a></li>
					<li>|</li>
					<li><a href="<?php echo $dominio . "contacto/";?>" title="Configurar cookies">Contacto</a></li>					
				</ul>
				<ul class="float-right">		
					<li>© Altadis S.A.</li>
				</ul>		
			</div>
		</div>
	</div>
</footer>

<?php if ($_SERVER['SERVER_NAME'] == "nocontrabando.altadis.com") { ?>
	<div id="cookie-policy" class="cp-notice cp-hidden">
		<div class="cp-bg">
			<div class="container">
				<div class="row">
					<div class="col-md-8">			
						<p>Utilizamos cookies propias y de terceros para mejorar nuestros servicios mediante el análisis de sus hábitos de navegación. Puede aceptar las cookies haciendo clic en el botón «Aceptar todas» o configurarlas o rechazar su uso haciendo clic en «Configurar cookies». Ver <a href="<?php echo $dominio?>politica-cookies/" title="Política de cookies" class="enlace">Política de cookies</a></p>
					</div>
					<div class="col-md-3 offset-md-1">		
						<p><a class="pop_config_cookies btn btn-secondary w100" href="#">Configurar cookies</a></p>
						<p><a class="cp-close  btn btn-primary w100" href="#">Aceptar todas</a></p>
					</div>				
				</div>
			</div>
		</div>
	</div>
<?php } ?>

<div id="cookie-config" class="cp-hidden">
	<div class="config-bg vertical-align">
		<div class="container">
			<div class="config-box">

				<p class="tit">Configuración de cookies</p>
				<p>Desde este panel podrá configurar las cookies que el sitio web puede instalar en su navegador, excepto las cookies técnicas o funcionales que son necesarias para la navegación y la utilización de las diferentes opciones o servicios que se ofrecen.</p>
				<p>Las cookies seleccionadas indican que el usuario autoriza la instalación en su navegador y el tratamiento de datos bajo las condiciones reflejadas en la <a href="<?php echo $dominio?>politica-cookies/" title="Política de cookies">Política de cookies</a>.</p>

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
							if(isset($_COOKIE['cookiepolicy-statistics'])) {
								$cookie_statistics = $_COOKIE['cookiepolicy-statistics'];
							}
							$checked = "";
							if ($cookie_statistics=="true") { $checked = "checked"; }
							?>
							<div class="form-group">
								<div class="checkbox checbox-switch switch-danger">
									<label>
										<input type="checkbox" name="cookie_statistics" id="cookie_statistics" <?php echo $checked; ?>/>
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
