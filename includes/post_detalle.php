<?php include("header.php");?>
<div class="container">
	<div class="row justify-content-center">	
		<div class="col-md-10">	
			<h1 class="text-center tit1"><?php echo $titulo; ?></h1>
		</div>
	</div>	

	<?php if ($entradilla!="") { ?>
	<div class="row mb-3">	
		<div class="col-md-9 mx-auto">
			<div class="entradilla"><?php echo $entradilla;?></div>
		</div>
	</div>
	<?php } ?>
		
	<div class="row">	
		<div class="col-md-11 mx-auto">
			<p class="text-center cat">
				<?php
				$sql2 = "SELECT categorias.id, categorias.nombre, categorias.slug FROM categorias";		
				$sql2.= " INNER JOIN contenidos_categorias ON contenidos_categorias.id_categoria = categorias.id";		
				$sql2.= " WHERE contenidos_categorias.id_contenido='$id_noticia'";
				$sql2.= " ORDER BY categorias.orden";
				$rs2 = $mysqli->query($sql2); 
				if ($rs2->num_rows > 0) {
					while ($fila2 = $rs2->fetch_array(MYSQLI_ASSOC)){
						$categoria_name = $fila2["nombre"];
						$categoria_slug = $fila2["slug"];
						?><a href="<?php echo $dominio . "category/$categoria_slug/";?>"><?php echo $categoria_name;?></a> | <?php
					}
				}
				?>
				<?php echo cambiaf_a_normal($fecha);?>
			</p>	
			<p class="text-center autor">NO Contrabando - Altadis</p>
		</div>
	</div>

	<?php if ($imagen!="") { ?>
	<div class="row mt-3 mb-3">	
		<div class="col-sm-7 col-md-7 mx-auto text-center">							
			<?php echo $imagen;?>
			<?php if ($imagen_pie!="") { ?><p class="clearfix text-center"><?php echo $imagen_pie;?></p><?php } ?>
		</div>

		<?php
			if ($livechat_vimeo==1) {
				?>
				<div class="col-sm-4 col-md-4">	
					<div class="w-100 h-100 d-inline-block">
						<iframe class="livechat_container" frameborder="0" scrolling="no" src="https://vimeo.com/live-chat/<?php echo $codigo_video; ?>/"></iframe>
					</div>
				</div>
				<?php
			}
		?>		
	</div>				
	<?php } ?>


	<div class="row mb-5">	
		<div class="col-md-8 mx-auto">	
			<div class="texto mb-5"><?php echo $texto;?></div>
			<?php if ($_SERVER['SERVER_NAME'] == "nocontrabando.altadis.com") {  ?>				
				<div class="socialfollow text-center">
					<div class="sharetx">Compartir</div>
					<a href="https://www.facebook.com/sharer.php?u=<?php echo urlencode($pagina_nocontrabando)?>&title=<?php echo urlencode($meta_title)?>" title="Facebook" class="facebook share"><i class="fa fa-facebook"></i></a>
					<a href="https://twitter.com/intent/tweet?url=<?php echo urlencode($pagina_nocontrabando)?>&text=<?php echo urlencode($meta_title)?>" title="Twitter" class="twitter share"><i class="fa fa-twitter-x"></i></a>
					<a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo urlencode($pagina_nocontrabando)?>&title=<?php echo urlencode($meta_title)?>" title="Linkedin" class="linkedin share"><i class="fa fa-linkedin"></i></a>
					<a href="mailto:?subject=<?php echo($meta_title);?>&body=<?php echo "Leer noticia completa en el siguiente enlace " . "%0D%0A%0D%0A" . urlencode($pagina_nocontrabando) . "%0D%0A%0D%0A"?>" title="eMail" class="envelope"><i class="fa fa-envelope"></i></a>
					<a href="whatsapp://send?text=<?php echo urlencode($meta_title)?> <?php echo urlencode($pagina_nocontrabando)?>?utm_source=whatsapp" title="Whatsapp" class="whatsapp d-sm-none"><i class="fa fa-whatsapp"></i></a>				
				</div>			
			<?php } ?>			
		</div>
	</div>	


<?php

if ($tags!="") {

    $sql = "SELECT contenidos.id, contenidos.post_name, contenidos.fecha, contenidos.titulo, contenidos.entradilla, contenidos.texto, contenidos.imagen, contenidos.imagen_pie, contenidos.video, contenidos.tags, contenidos_categorias.id_categoria FROM contenidos";
    $sql.= " INNER JOIN contenidos_categorias ON contenidos_categorias.id_contenido = contenidos.id";
    $sql.= " WHERE contenidos.activo=1 AND contenidos.tipo='post' AND contenidos.id<>$id_noticia";
	$sql.= " AND (";
    $array_tags = explode(",", $tags);
    $contador=1;
    foreach ($array_tags as $tag) {
        $tag_name = trim($tag);
        $tag_slug = limpia_post_name($tag_name);
        $tag_name = ucfirst($tag_name); 
		
		$sql.= " contenidos.titulo LIKE '%$tag_name%' OR contenidos.entradilla LIKE '%$tag_name%' ";

		if ($contador < count($array_tags)) {
			$sql.= " OR ";
		}

        $contador ++;
    }
	$sql.= " )";

    $sql.= " GROUP BY contenidos.id";
    $sql.= " ORDER BY RAND()";
    $sql.= " LIMIT 3";


    $rs = $mysqli->query($sql);
    if ($rs!="") {
        $num_total_registros = $rs->num_rows;
        if ($num_total_registros>0) {
            echo '<hr class="clearfix">';
            echo '<div class="row">';
            echo '	<div class="col-md-12 text-center"><h2 class="titular_listado">Noticias relacionadas</h2></div>';
            echo '</div>';

            echo '<div class="row mb-5">';

            while ($fila = $rs->fetch_array(MYSQLI_ASSOC)) {
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
                $tags = $fila["tags"]; ?>
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
                    while ($fila2 = $rs2->fetch_array(MYSQLI_ASSOC)) {
                        $categoria_name = $fila2["nombre"];
                        $categoria_slug = $fila2["slug"]; ?><a href="<?php echo $dominio . "category/$categoria_slug/"; ?>"><?php echo $categoria_name; ?></a><?php

                                if ($contador < $rs2->num_rows) {
                                    echo " | ";
                                }
                        $contador ++;
                    }
                    echo "</p>";
                } ?>					
					<h3><a href="<?php echo "$dominio" . $slug . "/"; ?>" title="<?php echo $titulo; ?>" class="titular_3"><?php echo $titulo; ?></a></h3>								
					
				</div>
				<?php
            }
            echo "</div>";
        }
    }
}	
?>

</div>

<?php include("footer.php");?>