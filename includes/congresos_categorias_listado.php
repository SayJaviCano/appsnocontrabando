<?php include("header.php");?>

<div class="container">

	<div class="row justify-content-center">
		<div class="col-md-10">	
			<h1 class="text-center tit1"><?php echo $congresos_categoria_nombre_select;?></h1>
		</div>
	</div>	
	<hr class="clearfix">

	<?php	
	$num_pagina = 1;
	$TAMANO_PAGINA = 10;
	$inicio = 0;
	
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

			if ($imagen!="") {  $imagen = '<img src="' . $dominio . $imagen .'" class="img-fluid" alt="' . $titulo . '">'; }		
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

			if ($contador == 1) {
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
						<h2><a href="<?php echo "$dominio" . $slug . "/"; ?>" title="<?php echo $titulo; ?>" class="tit2"><?php echo $titulo; ?></a></h2>
					</div>	
				</div>
				<hr class="clearfix">
				<div class="row">						
			<?php
				} else {
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
				}		
			$contador++;
		}
	}
	?>
	</div>
	<div id="content_publicaciones"></div>
	<div id="ver_mas" class="text-center"></div>

</div>

<script>
pagina = 1;
total_paginas = <?php echo $total_paginas;?>;
$(document).ready(function() {	
	mas_resultados_listado('<?php echo $num_pagina; ?>', '<?php echo $total_paginas;?>');
	$("#ver_mas").click(function() { carga_listado(); });	
});

function mas_resultados_listado(pagina, total_paginas) {
	pagina = parseInt(pagina) + 1;
	var mas_resultados = "<div class=\"btn btn-primary ver_mas\">Ver más</div> <div class=\"clearfix\">&nbsp;</div>";
	if (pagina > total_paginas)	{ mas_resultados = "";	}
	$('#ver_mas').html(mas_resultados);
}

function carga_listado() {
	pagina = pagina + 1;
	url_ajax = dominio + 'includes/ajax_congresos_categorias_listado.php?c=<?php echo $congresos_categoria_id_select;?>&p='+ pagina;

	$.ajax({
	  url: url_ajax, 
	  success: function(data) {		
		var new_div = $('<div class="row page_' + pagina+ '">' + data + '</div>').hide();
		$('#content_publicaciones').append(new_div);
		new_div.slideDown( "slow", function() {
			$.scrollTo('.page_' + pagina, 500, { axis:'y', offset: -50 } );
		});		
	  }
	});
}
</script>

<?php include("footer.php");?>