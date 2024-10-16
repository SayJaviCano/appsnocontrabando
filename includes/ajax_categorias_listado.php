<?php
header("Expires: Sat, 01 Jan 2000 00:00:00 GMT");
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
header("Cache-Control: post-check=0, pre-check=0",false);
session_cache_limiter("must-revalidate");

session_start();

$path_relative="../";
require_once('conexion.php');

	$categoria_id_select = LimpiaParametros(isset($_GET['c']) ? $_GET['c'] : ""); 
	$num_pagina = LimpiaParametros(isset($_GET['p']) ? $_GET['p'] : 1); 

	$TAMANO_PAGINA = 9;
	$inicio = ($num_pagina - 1) * $TAMANO_PAGINA;  
	$inicio = $inicio+1;

	
	$sql = "SELECT contenidos.id, contenidos.post_name, contenidos.fecha, contenidos.titulo, contenidos.entradilla, contenidos.texto, contenidos.imagen, contenidos.imagen_pie, contenidos.video, contenidos.tags, contenidos_categorias.id_categoria FROM contenidos";		
	$sql.= " INNER JOIN contenidos_categorias ON contenidos_categorias.id_contenido = contenidos.id";		
	$sql.= " WHERE contenidos.activo=1 AND contenidos.tipo='post'";
	
	$sql.= " AND contenidos_categorias.id_categoria=$categoria_id_select";

	$sql.= " GROUP BY contenidos.id";		
	$sql.= " ORDER BY contenidos.fecha DESC, contenidos.id DESC";


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

			if ($imagen!="") {  $imagen = '<img src="' . $dominio . $imagen .'" class="img-fluid">'; }		
			elseif ($video!="") {  

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
			<div class="col-md-4 mb-5">	
				<a href="<?php echo "$dominio" . $slug . "/"; ?>" title="<?php echo $titulo; ?>">
					<div class="caja n3">
						<div class="box"><?php echo $imagen; ?></div>
					</div>						
				</a>
				<h3><a href="<?php echo "$dominio" . $slug . "/"; ?>" title="<?php echo $titulo; ?>" class="titular_3"><?php echo $titulo; ?></a></h3>	
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