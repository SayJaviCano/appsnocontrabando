<div class="accordion" id="nosotros_accordion">
	<div class="card mb-5">	
	<?php
			$sql = "SELECT * FROM nosotros WHERE activo=1 ORDER BY orden";
			$rs = $mysqli->query($sql); 
			$contador=1;
			while ($fila = $rs->fetch_array(MYSQLI_ASSOC)){
				$id_faq = $fila['id'];				
				$pregunta = $fila['pregunta'];	
				$respuesta = $fila['respuesta'];
				$collapsed = "collapsed";
				$aria_expanded="false";
				$show = "";
				
				if ($contador==1)
				{
					$collapsed = "";
					$aria_expanded="true";
					$show = "show";					
				}
				

				?>
				<!-- **************** -->
				<div class="card-header <?php echo $collapsed;?>" data-toggle="collapse" href="#c1_<?php echo $id_faq;?>" aria-expanded="<?php echo $aria_expanded;?>"><span class="card-title card-title-color"><?php echo $pregunta;?></span></div>
				<div class="card-body card-body-color collapse <?php echo $show;?>" data-parent="#nosotros_accordion" id="c1_<?php echo $id_faq;?>">
					<?php echo $respuesta;?>
				</div>
				<?php	
				$contador++;		
			}
	?>
	</div>
</div>
<script>
$(document).ready(function() {	
	$.scrollTo('.accordion', 500, { axis:'y', offset: -50 } );
});
</script>