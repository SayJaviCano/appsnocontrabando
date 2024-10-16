<?php	
	$sql = "SELECT * FROM faqs_categorias WHERE activo=1 ORDER BY orden, nombre";
	$rs = $mysqli->query($sql); 
	$contador=1;
	echo '<div class="preguntas_categorias">';

	while ($fila = $rs->fetch_array(MYSQLI_ASSOC)){
		$id_categoria = $fila['id'];
		$nombre = $fila['nombre'];		
		$slug = $fila['slug'];		
		?><div class="pregunta <?php if ($contador==1) { echo "top"; } ?> <?php if ($faqs_categorias_id_select==$id_categoria) { echo "collapsed"; } ?> ">
			<a href="<?php echo $dominio . "preguntanos/" . $slug . "/";?>" title="<?php echo "$nombre";?>" class="<?php if ($page_name==$slug) { echo "active"; } ?>"><?php echo "$nombre";?></a>

			<?php if ($faqs_categorias_id_select==$id_categoria) { ?>
					<div class="accordion" id="preguntas_accordion">
						<div class="card">	
						<?php
							$sql2 = "SELECT * FROM faqs WHERE activo=1 AND id_categoria=$faqs_categorias_id_select ORDER BY orden";
							$rs2 = $mysqli->query($sql2); 
							$contador2=1;
							while ($fila2 = $rs2->fetch_array(MYSQLI_ASSOC)){
								$id_faq = $fila2['id'];				
								$pregunta = $fila2['pregunta'];	
								$respuesta = $fila2['respuesta'];

								$collapsed = "collapsed";
								$aria_expanded="false";
								$show = "";

								$faq = LimpiaParametros((isset($_GET['faq']) ? $_GET['faq'] :""));									
								if ($faq==$id_faq)
								{
									$collapsed = "";
									$aria_expanded="true";
									$show = "show";					
								}


								?>
								<!-- **************** -->
								<div class="card-header <?php if ($contador2==1) { echo "top"; } ?> <?php echo $collapsed;?>" data-toggle="collapse" href="#f_<?php echo $id_faq;?>" aria-expanded="<?php echo $aria_expanded;?>"><span class="card-title card-title-color"><?php echo $pregunta;?></span></div>
								<div class="card-body card-body-color collapse <?php echo $show;?>" data-parent="#preguntas_accordion" id="f_<?php echo $id_faq;?>">
									<?php echo $respuesta;?>
								</div>
								<?php	
								$contador2++;
							}
						?>
						</div>
					</div>
					<script>
					$(document).ready(function() {	
						$.scrollTo('.accordion', 500, { axis:'y', offset: -50 } );
					});
					</script>
			<?php 
				$contador++;		
			} ?>

		</div><?php				
	}
	echo '</div>';
?>

<script>
$(document).ready(function() {

	$( "body" ).on( "click", ".pregunta.collapsed a", function(e) {
		e.preventDefault();
		$('.pregunta.collapsed').removeClass('collapsed');
		$('.accordion').hide();
		return false;
	});
	

});
</script>