<?php include("header.php");?>

<div class="container">		
<?php
	$sql = "
	SELECT c.id, c.post_name, c.fecha, c.titulo, c.entradilla, 
		c.texto, c.imagen, c.imagen_pie, c.video, c.tags, 
        c.destacado_img, 
        MIN(j.id_categoria)

		FROM contenidos c

		LEFT JOIN contenidos_categorias j ON j.id_contenido = c.id 
		LEFT JOIN categorias cat ON cat.id = j.id_categoria

		WHERE c.activo=1 
		AND c.tipo='post' 
		AND cat.activo = 1

		GROUP BY c.id

		ORDER BY destacado, c.fecha DESC, c.id DESC 

		LIMIT 5";

	$rs = $mysqli->query($sql); 
	$num_total_registros = $rs->num_rows; 

	if ($rs!="") {	
		$contador = 1;				
		while ($fila = $rs->fetch_array(MYSQLI_ASSOC)){
			$id = $fila['id'];		
			$fecha = cambiaf_a_normal($fila['fecha']);			
			$titulo = $fila['titulo'];	
			$entradilla = $fila['entradilla'];	
			$imagen = $fila['imagen'];	
			$imagen_pie = $fila['imagen_pie'];	
			$video = $fila['video'];
			if ($imagen!="") {  $imagen = '<img src="' . $dominio . $imagen .'" class="img-fluid"  alt="' . $titulo . '">'; }		
			elseif ($video!="")	
			{  
				$pos = strpos($video, "youtube");
				if ($pos !== false) { 
					$video = str_replace ("watch?v=", "", $video);
					$arr_video	= explode('/', $video ); 
					$codigo_video = $arr_video[count($arr_video)-1];
					$url = "https://i.ytimg.com/vi/$codigo_video/hq720.jpg";
					if (url_exists($url))
					{
						$imagen = '<img src="https://i.ytimg.com/vi/' . $codigo_video . '/hq720.jpg" class="img-fluid" alt="' . $titulo . '">';
					} else {
						$imagen = '<img src="https://i.ytimg.com/vi/' . $codigo_video . '/hqdefault.jpg" class="img-fluid" alt="' . $titulo . '">';
					}
				} else{
					$pos = strpos($video, "vimeo");
					if ($pos !== false) {
						$arr_video	= explode('/', $video ); 
						$codigo_video = $arr_video[count($arr_video)-1];
						$url = getVimeoThumb($codigo_video);	
						$imagen = '<img src="' . $url . '" class="img-fluid" alt="' . $titulo . '">';
					}					
				}
			}

			$slug = $fila["post_name"];
			$destacado_img = $fila['destacado_img'];
			
			// ENCABEZADO - NOTICIA PRINCIPAL (HOME)
			if ($contador == 1) { ?>
			
				<div class="row justify-content-center">
					<div class="col-md-10">	
						<h1 class="text-center"><a href="<?php echo "$dominio" . $slug . "/"; ?>" title="<?php echo $titulo; ?>" class="tit1"><?php echo $titulo; ?></a></h1>

						<?php if ($destacado_img=="1") { ?>
							<p class="text-center"><a href="<?php echo "$dominio" . $slug . "/"; ?>" title="<?php echo $titulo; ?>"><?php echo $imagen; ?></a></p>
						<?php } ?>
					</div>
				</div>

			<?php if ($_SERVER['SERVER_NAME'] == "nocontrabando.altadis.com" || $_SERVER['SERVER_NAME'] == "asus") { ?>
				</div>
				<div class="bg-gris2 mt-5 mb-5 text-center p-4">
					<a href="https://nocontrabando.altadis.com/?utm_source=webNoContrabando&utm_medium=banner&utm_campaign=perdemosTodos&pop=appNoContrabando"><img src="<?php echo $dominio;?>images/banner_perdemos_todos.gif" class="img-fluid" alt="Descárgate la APP NO CONTRABANDO"></a>
				</div>			
				<div class="container">	
			<?php } else { ?>
				<hr class="clearfix">
			<?php } ?>
				<?php	
			} elseif ($contador == 2) {
				?>
				<div class="row justify-content-center align-items-center">
					<div class="col-md-6">							
						<a href="<?php echo "$dominio" . $slug . "/"; ?>" title="<?php echo $titulo; ?>">
							<div class="caja n2">
								<div class="box">
									<?php echo $imagen; ?>
									<?php if ($video!="") { ?><div class="tapa_video"></div><?php } ?>
								</div>
							</div>							
						</a>
					</div>
					<div class="col-md-6">	
						<?php
							$sql2 = "SELECT categorias.id, categorias.nombre, categorias.slug FROM categorias";		
							$sql2.= " INNER JOIN contenidos_categorias ON contenidos_categorias.id_categoria = categorias.id";		
							$sql2.= " WHERE contenidos_categorias.id_contenido='$id'";
							$sql2.= " ORDER BY categorias.orden";
							$rs2 = $mysqli->query($sql2); 
							if ($rs2->num_rows > 0) {
								echo '<p class="cat">';
								$contador=1;
								while ($fila2 = $rs2->fetch_array(MYSQLI_ASSOC)){
									$categoria_name = $fila2["nombre"];
									$categoria_slug = $fila2["slug"];
									?><a href="<?php echo $dominio . "category/$categoria_slug/";?>"><?php echo $categoria_name;?></a><?php

									if ($contador < $rs2->num_rows) {  echo " | "; }
									$contador ++;
								}
								echo "</p>";
							}
						?>
						<h2><a href="<?php echo "$dominio" . $slug . "/"; ?>" title="<?php echo $titulo; ?>" class="tit2"><?php echo $titulo; ?></a></h2>
					</div>	
				</div>
				<hr>
				<div class="row">						
				<?php
			} else {
				?>
				<div class="col-md-4 noticias_n3">	
					<a href="<?php echo "$dominio" . $slug . "/"; ?>" title="<?php echo $titulo; ?>">
						<div class="caja n3">
							<div class="box">
								<?php echo $imagen; ?>
								<?php if ($video!="") { ?><div class="tapa_video"></div><?php } ?>
							</div>
						</div>						
					</a>
					<?php
						$sql2 = "SELECT categorias.id, categorias.nombre, categorias.slug FROM categorias";		
						$sql2.= " INNER JOIN contenidos_categorias ON contenidos_categorias.id_categoria = categorias.id";		
						$sql2.= " WHERE contenidos_categorias.id_contenido='$id'";
						$sql2.= " ORDER BY categorias.orden";
						$rs2 = $mysqli->query($sql2); 
						if ($rs2->num_rows > 0) {
							echo '<p class="cat">';
							$contador=1;
							while ($fila2 = $rs2->fetch_array(MYSQLI_ASSOC)){
								$categoria_name = $fila2["nombre"];
								$categoria_slug = $fila2["slug"];
								?><a href="<?php echo $dominio . "category/$categoria_slug/";?>"><?php echo $categoria_name;?></a><?php

								if ($contador < $rs2->num_rows) {  echo " | "; }
								$contador ++;
							}
							echo "</p>";
						}
					?>					
					<h3><a href="<?php echo "$dominio" . $slug . "/"; ?>" title="<?php echo $titulo; ?>" class="titular_3"><?php echo $titulo; ?></a></h3>				
				</div>
				<?php
			}		
			$contador++;				
		}
	}
	?>
			</div>
</div>


<iframe id="iframe_chart" name="iframe_chart" src="<?php echo $dominio?>iframe_chart.php" frameborder="0" scrolling="no"></iframe>

<div class="container clearfix">			
	<?php
	$sql = "SELECT contenidos.id, contenidos.post_name, contenidos.fecha, contenidos.titulo, contenidos.entradilla, contenidos.texto, contenidos.imagen, contenidos.imagen_pie, contenidos.video, contenidos.tags, contenidos_categorias.id_categoria FROM contenidos";		
	$sql.= " INNER JOIN contenidos_categorias ON contenidos_categorias.id_contenido = contenidos.id";		
	$sql.= " WHERE contenidos.activo=1 AND contenidos.tipo='post' AND video<>''";
	$sql.= " GROUP BY contenidos.id, contenidos_categorias.id_categoria";		
	$sql.= " ORDER BY destacado_video DESC, contenidos.fecha DESC, contenidos.id DESC";
	$sql.= " LIMIT 1";
	$rs = $mysqli->query($sql); 
	$num_total_registros = $rs->num_rows; 

	if ($rs!="") {	
			
		while ($fila = $rs->fetch_array(MYSQLI_ASSOC)){
			$id = $fila['id'];		
			$fecha = cambiaf_a_normal($fila['fecha']);			
			$titulo = $fila['titulo'];	
			$entradilla = $fila['entradilla'];	
			$video = $fila['video'];	
			if ($video!="")	
			{  
				$pos = strpos($video, "youtube");
				if ($pos !== false) { 
					$video = str_replace ("watch?v=", "", $video);
					$arr_video	= explode('/', $video ); 
					$codigo_video = $arr_video[count($arr_video)-1];
					$url = "https://i.ytimg.com/vi/$codigo_video/hq720.jpg";
					if (url_exists($url))
					{
						$imagen = '<img src="https://i.ytimg.com/vi/' . $codigo_video . '/hq720.jpg" class="img-fluid" alt="' . $titulo . '">';
					} else {
						$imagen = '<img src="https://i.ytimg.com/vi/' . $codigo_video . '/hqdefault.jpg" class="img-fluid" alt="' . $titulo . '">';
					}
				} else{
					$pos = strpos($video, "vimeo");
					if ($pos !== false) {
						$arr_video	= explode('/', $video ); 
						$codigo_video = $arr_video[count($arr_video)-1];
						$url = getVimeoThumb($codigo_video);	
						$imagen = '<img src="' . $url . '" class="img-fluid" alt="' . $titulo . '">';
					}					
				}
			}
			$slug = $fila["post_name"];
			?>
			<div class="row mt-2">
				<div class="col-md-8 mb-5">							
					<a href="<?php echo "$dominio" . $slug . "/"; ?>" title="<?php echo $titulo; ?>">
						<div class="caja n4">
							<div class="box">
								<?php echo $imagen; ?>
								<div class="tapa_video"></div>
							</div>
						</div>							
					</a>
					<h3><a href="<?php echo "$dominio" . $slug . "/"; ?>" title="<?php echo $titulo; ?>" class="tit2"><?php echo $titulo; ?></a></h3>
				</div>
				<div class="col-md-4 text-center mb-5">
					<?php
						$sql2 = "SELECT * FROM banner_home WHERE activo=1 ORDER BY RAND() LIMIT 1";
						$rs2 = $mysqli->query($sql2); 
						if ($rs2->num_rows > 0) {
							$fila2 = $rs2->fetch_assoc();
							$archivo = $fila2["archivo"];
							$texto = $fila2["texto"];
							$enlace = $fila2["enlace"];
							
							$target="_self";
							$pos = strpos($enlace, "http");
							if ($pos === false) {
								$enlace = $dominio . $enlace;
							} else {
								$target="_blank";
							}

							?><a href="<?php echo $enlace;?>" title="<?php echo $texto;?>" target="<?php echo $target;?>"><img src="<?php echo $dominio."images/banner/$archivo";?>" class="img-fluid" alt="<?php echo $texto;?>"></a><?php
						}
					?>					
				</div>	
			</div>
		<?php
				
		}
	}
	?>
</div>

<?php include("footer.php");?>