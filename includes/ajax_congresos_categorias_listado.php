<?php
header("Expires: Sat, 01 Jan 2000 00:00:00 GMT");
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
header("Cache-Control: post-check=0, pre-check=0",false);
session_cache_limiter("must-revalidate");

session_start();

$path_relative="../";
require_once('conexion.php');

	$congresos_categoria_id_select = LimpiaParametros(isset($_GET['c']) ? $_GET['c'] : ""); 
	$num_pagina = LimpiaParametros(isset($_GET['p']) ? $_GET['p'] : 1); 

	$TAMANO_PAGINA = 9;
	$inicio = ($num_pagina - 1) * $TAMANO_PAGINA;  
	$inicio = $inicio+1;

	
  $sql = "SELECT * FROM contenidos WHERE activo=1 AND tipo='post' AND id_congresos_categoria=$congresos_categoria_id_select";	
  $sql.= " ORDER BY fecha DESC, id DESC";


	$rs = $mysqli->query($sql); 
	$num_total_registros = $rs->num_rows;  
	$total_paginas = ceil($num_total_registros / $TAMANO_PAGINA); 

	$sql .= " LIMIT " . $inicio . "," . $TAMANO_PAGINA;
	$rs = $mysqli->query($sql); 

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
      $slug = $fila['post_name'];

      $has_media = false; // always (showing cards)

      if ($imagen != "") {

        $imagen = '<img src="' . $dominio . $imagen .'" class="img-fluid">'; 
      } elseif ($video!="") {  

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

      $sub_slug = '';

      $sql2 = "SELECT categorias.id, categorias.nombre, categorias.slug 
                FROM categorias 
                INNER JOIN contenidos_categorias ON contenidos_categorias.id_categoria = categorias.id
                WHERE contenidos_categorias.id_contenido='$id'
                ORDER BY categorias.orden";

      $rs2 = $mysqli->query($sql2);

      if ($rs2->num_rows > 0) {

        $cont_slug = 1;
        
        while ($fila2 = $rs2->fetch_array(MYSQLI_ASSOC)
        ) {
          $categoria_name = $fila2["nombre"];
          $categoria_slug = $fila2["slug"];
          $sub_slug .= '<a href="' . $dominio . 'categoria/$categoria_slug/">' . $categoria_name . '</a>';

          if ($cont_slug < $rs2->num_rows
          ) {
            $sub_slug .=  " | ";
          }

          $cont_slug++;
        }

        $sub_slug .=  " | " . $fecha;
      } else {
        $sub_slug = "";
      }


      if ($has_media) {
        $class = "noticias-card";
      } else {
        $class = "noticias-card-textonly";
      }


			?>
			<div class="col-md-4 mb-4">
        <div class="noticias-card-compact">
          <p class="cat">
            <a href="<?php echo $dominio . 'category/' . $categoria_slug . '/'; ?>">
              <?php echo $sub_slug; ?>
            </a>
          </p>
          <h3>
            <a href="<?php echo "$dominio" . $slug . "/"; ?>"
              title="<?php echo $titulo; ?>" class="titular_3">
              <?php echo $titulo; ?></a>
          </h3>
          <p>
            <a href="<?php echo "$dominio" . $slug . "/"; ?>" title="<?php echo $titulo; ?>" class="arrow-link">Seguir Leyendo</a>
          </p>
        </div>
      </div>

			<?php
	
			$contador++;
		}
	}
?>
<script>
$(document).ready(function() {
	mas_resultados_listado('<?php echo $num_pagina; ?>', '<?php echo $total_paginas;?>');
});
</script>