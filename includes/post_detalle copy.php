<?php include("header.php");?>
<?php

/**************************************************************************************************** */
$date = $fecha;
$add_days = 60;
$fecha_mas = date('Y-m-d',strtotime($date) + (24*3600 * $add_days)); 
$fecha_menos = date('Y-m-d',strtotime($date) + (24*3600* - $add_days )); 

$next_id_noticia = 0;
$ant_id_noticia = 0;
$noticia_encontrada = 0;
$ant_enlace_noticia = "";  $next_enlace_noticia="";
$sql2="SELECT * FROM contenidos WHERE (tipo='post' AND activo=1) AND (fecha<='$fecha_mas' AND fecha>='$fecha_menos') ORDER BY fecha DESC"; 
$rs2 = $mysqli->query($sql2);
if ($rs2) {	$num_total_registros2 = $rs2->num_rows;  }

if ($num_total_registros2 > 0) {
	while ($fila2 = $rs2->fetch_array(MYSQLI_ASSOC)){
		if  ($noticia_encontrada == 1) 
		{
			$next_id_noticia = $fila2['id'];;
			break;
		}
		else if( $id_noticia !=  $fila2['id'])
		{
			$ant_id_noticia = $fila2['id'];
		}

		if  ($id_noticia ==  $fila2['id'])  // Noticia Encontrada
		{
			$noticia_encontrada = 1;
		}
	}
	if  ($ant_id_noticia ==  $id_noticia)  { $ant_id_noticia=0; }

}
/***************** ANNTERIOR *********************************************************************************** */
		$sql2="SELECT * FROM contenidos WHERE (tipo='post' AND activo=1 AND id='$ant_id_noticia')"; 
		$rs2 = $mysqli->query($sql2);
		if ($rs2) {	$num_total_registros2 = $rs2->num_rows;  }
		if ($num_total_registros2 > 0) {
			$fila2 = $rs2->fetch_assoc();
			$ant_id_noticia = $fila2['id'];
			$ant_post_name = trim($fila2['post_name']);
			$ant_enlace_noticia = $dominio . "$ant_post_name/";
		}
/***************** SIGUIENTE  *********************************************************************************** */
		$sql2="SELECT * FROM contenidos WHERE (tipo='post' AND activo=1 AND id='$next_id_noticia')"; 
		$rs2 = $mysqli->query($sql2);
		if ($rs2) {	$num_total_registros2 = $rs2->num_rows;  }
		$next_id_noticia = 0;
		if ($num_total_registros2 > 0) {
			$fila2 = $rs2->fetch_assoc();
			$next_id_noticia = $fila2['id'];
			$nex_post_name = trim($fila2['post_name']);
			$next_enlace_noticia = $dominio . "$nex_post_name/";	
		}
/**************************************************************************************************** */		
?>

<div class="container">

<?php
/*
	<div class="row mb-5">	
		<div class="col-4">
			<?php if ($ant_enlace_noticia!="") { ?>
				<a href="<?php echo $ant_enlace_noticia;?>"> <- Anterior </a>
			<?php } ?>	
		</div>
		<div class="col-4 text-center">
			<a href="https://nocontrabando.altadis.com/?p=<?php echo $id_noticia;?>" target="_blank">https://nocontrabando.altadis.com/?p=<?php echo $id_noticia;?></a>
		</div>
		<div class="col-4 text-right">
			<?php if ($next_enlace_noticia!="") { ?>
				<a href="<?php echo $next_enlace_noticia;?>">Siguiente -></a>
			<?php } ?>
		</div>					
	</div>	
*/
?>	

	<div class="row justify-content-center">	
		<div class="col-md-10">	
			<h1 class="text-center tit1"><?php echo $titulo; ?></h1>
		</div>
	</div>	


	<?php if ($entradilla!="") { ?>
	<div class="row">	
		<div class="col-md-9 mx-auto">
			<div class="text-center entradilla"><?php echo $entradilla;?></div>
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
		</div>
	</div>

	<?php if ($imagen!="") { ?>
	<div class="row mt-3 mb-3">	
		<div class="col-md-7 mx-auto">				
			<p class="text-center"><?php echo $imagen;?></p>
			<?php if ($imagen_pie!="") { ?><p class="clearfix text-center"><?php echo $imagen_pie;?></p><?php } ?>
		</div>
	</div>				
	<?php } ?>


	<div class="row mb-5">	
		<div class="col-md-8 mx-auto">	
			<div class="texto mb-5"><?php echo $texto;?></div>
			<?php if ($_SERVER['SERVER_NAME'] == "nocontrabando.altadis.com") {  ?>				
				<div class="socialfollow text-center">
					<div class="sharetx">Compartir</div>
					<a href="https://www.facebook.com/sharer.php?u=<?php echo urlencode($pagina_nocontrabando)?>&title=<?php echo urlencode($meta_title)?>" title="Facebook" class="facebook share"><i class="fa fa-facebook"></i></a>
					<a href="https://twitter.com/intent/tweet?url=<?php echo urlencode($pagina_nocontrabando)?>&text=<?php echo urlencode($meta_title)?>" title="Twitter" class="twitter share"><i class="fa fa-twitter"></i></a>
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
    $sql.= " WHERE contenidos.activo=1 AND contenidos.tipo='post'";

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
                if ($imagen!="") {
                    $imagen = '<img src="' . $dominio . $imagen .'" class="img-fluid">';
                }
                if ($video!="") {
                    $video = str_replace("watch?v=", "", $video);
                    $arr_video	= explode('/', $video);
                    $codigo_video = $arr_video[count($arr_video)-1];

                    $url = "https://i.ytimg.com/vi/$codigo_video/hq720.jpg";
                    if (url_exists($url)) {
                        $imagen = '<img src="https://i.ytimg.com/vi/' . $codigo_video . '/hq720.jpg" class="img-fluid">';
                    } else {
                        $imagen = '<img src="https://i.ytimg.com/vi/' . $codigo_video . '/hqdefault.jpg" class="img-fluid">';
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


		

<?php
/*
	<div class="row mt-5 mb-5">	
		<div class="col-4">
			<?php if ($ant_enlace_noticia!="") { ?>
				<a href="<?php echo $ant_enlace_noticia;?>"> <- Anterior </a>
			<?php } ?>	
		</div>
		<div class="col-4 text-center">
			<a href="https://nocontrabando.altadis.com/?p=<?php echo $id_noticia;?>" target="_blank">https://nocontrabando.altadis.com/?p=<?php echo $id_noticia;?></a>
		</div>
		<div class="col-4 text-right">
			<?php if ($next_enlace_noticia!="") { ?>
				<a href="<?php echo $next_enlace_noticia;?>">Siguiente -></a>
			<?php } ?>
		</div>					
	</div>		
*/	
?>	

</div>

<?php include("footer.php");?>