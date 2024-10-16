<?php 

/**
 * News entry - linked from home and categoria pages
 * uses meta.php
 * called from index.php
 */


include("header.php");?>

<div class="container">
<!-- social share and bredcrumbs -->
<div class="breadcrumb-holder row justify-content-center">

  <div class="col-md-12 breadcrumbs">	
    <span><a href="<?php echo $dominio;?>">Home</a></span>
    <span class="separador"><i class="fa-solid fa-angle-right themed"></i></span>

    <?php if (@$congreso_post) { ?>

      <span><a href="<?php echo $dominio;?>category/forum/">Forum</a></span>

    <?php } else { ?>

      <span>Sections</span>
      <span class="separador"><i class="fa-solid fa-angle-right themed"></i></span>
      <span><a href="<?php echo $dominio . "/category/" . $categorias_reference[ $id_categoria]["slug"] ;?>">

      <?php echo $categorias_reference[ $id_categoria]["nombre"] ?></a></span>

    <?php } ?>
    
    <span class="separador"><i class="fa-solid fa-angle-right themed"></i></span>
    <span><?php echo $titulo; ?></span>
  </div>


  <div class="col-md-10">

    <div class="social-share">

      <div>Compartir </div>
  
      <div>
      
        <a href="https://www.facebook.com/sharer.php?u=<?php echo urlencode($pagina_nocontrabando)?>&title=<?php echo urlencode($meta_title)?>" title="Facebook" class=" share"><i class="themed fa-brands fa-facebook"></i></a>
        
        <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode($pagina_nocontrabando)?>&text=<?php echo urlencode($meta_title)?>" title="Twitter" class=" share"><i class="themed fa-brands fa-x-twitter"></i></a>
        
        <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo urlencode($pagina_nocontrabando)?>&title=<?php echo urlencode($meta_title)?>" title="Linkedin" class=" share"><i class="themed fa-brands fa-linkedin"></i></a>
        
        <a href="mailto:?subject=<?php echo($meta_title);?>&body=<?php echo "Leia o conteúdo completo no link a seguir " . "%0D%0A%0D%0A" . urlencode($pagina_nocontrabando) . "%0D%0A%0D%0A"?>" title="eMail" class="envelope"><i class="themed fa fa-envelope"></i></a>
        
        <a href="whatsapp://send?text=<?php echo urlencode($meta_title)?> <?php echo urlencode($pagina_nocontrabando)?>&utm_source=whatsapp" title="Whatsapp" class=" share"><i class="themed fa-brands fa-whatsapp"></i></a>				
      </div>		
    </div>
    
  </div>

  <div class="col-md-2 text-right">
    <a href="#" onclick="history.back()" title="Volver a la página principal" class="arrow-link-back">Volver</a>
  </div>

</div>	
<!-- end social share and bredcrumbs -->


<div class="top-underlined"></div>


  <div class="row post-detalle">	

    <div class="col-12 mb-3">
      <header>
        <p class="fecha"><?php echo cambiaf_a_normal($fecha);?></p>
        <h1 class="text-left"><?php echo $titulo; ?></h1>
        <?php echo $entradilla; ?>
      </header>
    </div>

  </div>
		
	<div class="row mb-3">	

    <div class="col-md-8 mt-3 pr-3">
      
      <?php echo $texto; ?>

      <?php if ($imagen_pie!="") { ?>
        <!--<p class="clearfix text-left"><?php echo $imagen_pie;?></p>-->
      <?php } ?>

    </div>

		<div class="col-md-4 mt-3">							
			<?php if ($imagen!="") {
         echo $imagen;
      }
      ?> 

    <?php if ($livechat_vimeo==1) { ?>
      <div class="col-12">	
        <div class="w-100 h-100 d-inline-block">
          <iframe class="livechat_container" frameborder="0" scrolling="no" src="https://vimeo.com/live-chat/<?php echo $codigo_video; ?>/"></iframe>
        </div>
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
            echo '<hr class="clearfix">
                  <div class="row">
                    <div class="col-md-12>
                      <h3 class="titular_listado">Contenúdo Relacionado</h3>
                    </div>
                  </div>
                  <div class="row mb-5">';

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
        $tags = $fila["tags"]; 
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