<?php include("header.php");?>

	<?php if ($titulo!="") {		?>

    <div class="bg-cabecera">
      <div class="container">
        <div class="col-md-12 content-cab">
          <h1><?php echo $titulo; ?></h1>
        </div>
      </div>
    </div>
		
	<?php } ?>

  <div class="container">

<?php if ($page_name=="buzon-de-denuncias" || $page_name=="conoce-putos-de-venta-legal") {  ?>
	<div class="row">	
		<div class="col text-center">
			<ul class="sub-menu">
				<li><a class="<?php if ($page_name=="buzon-de-denuncias") { echo "active"; } ?>" href="<?php echo $dominio;?>buzon-de-denuncias/" title="Buzón de denuncias">Denuncia</a></li>
				<li>|</li>
				<li><a class="<?php if ($page_name=="conoce-putos-de-venta-legal") { echo "active"; } ?>" href="<?php echo $dominio;?>conoce-putos-de-venta-legal/" title="Conoce los puntos de venta legal">Conoce los puntos de venta legal</a></li>
		</div>			
	</div>
<?php } ?>


	<?php if ($entradilla!="") { ?>
	<div class="row mb-3">	
		<div class="col-md-12 mx-auto">
			<div class="text-center entradilla"><?php echo $entradilla;?></div>
		</div>
	</div>
	<?php } ?>
		
<?php
	$col = "col-md-12";

	if ($page_name=="archivo") { $col = "col-md-12"; }

?>

		
	<div class="row mb-3">	
		<div class="<?php echo $col; ?> mx-auto">
			<div class="texto">
				<?php
				if ($texto!="") {
					$texto = str_replace ("../images/", $dominio."images/",  $texto);
					$bucle = 1;
					do {
						$pos = strpos($texto, "[include]");
						if ($pos === false) { // No include 
							echo $texto;
							$bucle = 0;
						} else {
							$ruta_include =  $_SERVER['DOCUMENT_ROOT'] . "/" . $ruta_real . "includes/"; 
							$pos_fin = strpos($texto, "[/include]");
							$cuantos = $pos_fin - $pos;
							$var_include = substr($texto, $pos, $cuantos) . "[/include]";
							$include = str_replace ("[/include]", "", $var_include);
							$include = str_replace ("[include]", "",  $include);

							$content_1 = substr($texto, 0, $pos);
							$texto = substr($texto, $pos_fin+10);

							echo $content_1;
							include($ruta_include . $include);
						}

					} while ($bucle == 1);
				} 		
				?>
			</div>				
		</div>
	</div>


<?php if ($_SERVER['SERVER_NAME'] == "nocontrabando.altadis.com" && $page_name=="buzon-de-denuncias") {  ?>
	<hr class="clearfix">
	<div class="row mb-5">	
		<div class="col-md-10 mx-auto">	
			<div class="socialfollow text-center">
				<div class="sharetx">Compartir</div>
				<a href="https://www.facebook.com/sharer.php?u=<?php echo urlencode($pagina_nocontrabando)?>&title=<?php echo urlencode($meta_title)?>" title="Facebook" class="facebook share"><i class="fa fa-facebook"></i></a>
				<a href="https://twitter.com/intent/tweet?url=<?php echo urlencode($pagina_nocontrabando)?>&text=<?php echo urlencode($meta_title)?>" title="Twitter" class="twitter share"><i class="fa fa-twitter-x"></i></a>
				<a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo urlencode($pagina_nocontrabando)?>&title=<?php echo urlencode($meta_title)?>" title="Linkedin" class="linkedin share"><i class="fa fa-linkedin"></i></a>
				<a href="mailto:?subject=<?php echo($meta_title);?>&body=<?php echo "Leer noticia completa en el siguiente enlace " . "%0D%0A%0D%0A" . urlencode($pagina_nocontrabando) . "%0D%0A%0D%0A"?>" title="eMail" class="envelope"><i class="fa fa-envelope"></i></a>
				<a href="whatsapp://send?text=<?php echo urlencode($meta_title)?> <?php echo urlencode($pagina_nocontrabando)?>?utm_source=whatsapp" title="Whatsapp" class="whatsapp d-sm-none"><i class="fa fa-whatsapp"></i></a>							
			</div>			
		</div>
	</div>
<?php } ?>

</div>

<?php include("footer.php");?>