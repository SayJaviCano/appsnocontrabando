<?php include("header.php");?>

<div class="container">

	<div class="row">	
		<div class="col text-center"><h1 class="titular_listado">Todas las publicaciones de la etiqueta <?php echo $tag_name_select;?></h1></div>			
	</div>

	<div class="row">	
		<div class="col-md-12 mb-3">
			<div class="row">
				<?php		
				
				$sql = "SELECT * FROM contenidos WHERE activo=1 AND tipo='post'";				
				$sql.= " AND tags like '%$tag_name_select%'";		
				$sql.= " ORDER BY fecha DESC, id DESC";
				$rs = $mysqli->query($sql); 
				$num_total_registros = $rs->num_rows; 

				if ($rs!="") {
					
					while ($fila = $rs->fetch_array(MYSQLI_ASSOC)){
						$id = $fila['id'];		
						$fecha = cambiaf_a_normal($fila['fecha']);			
						$titulo = $fila['titulo'];	
						$entradilla = $fila['entradilla'];	
						$imagen = $fila['imagen'];	
						$imagen_pie = $fila['imagen_pie'];	
						$video = $fila['video'];
						if ($imagen!="") {  $imagen = '<img src="' . $dominio . $imagen .'" class="img-fluid" alt="' . $titulo . '">'; }		
						if ($video!="") {  $imagen = '<iframe src="' . $video. '" allowfullscreen="allowfullscreen" frameborder="0"></iframe>'; }		
						$slug = $fila["post_name"];
						$tags = $fila["tags"];
						?>
						<div class="col-md-4 mb-3">	
							<a href="<?php echo "$dominio" . $slug . "/";?>">									
									<div class="caja">
										<div class="box">
											<?php echo $imagen;?>
											<?php if ($imagen_pie!="") {  echo "<br>$imagen_pie<br>"; } ?>
										</div>
									</div>
								<p class="titular_3"><?php echo $titulo;?></p>
							</a>
						</div>
					<?php			
					
					}
				}
				?>
			</div>		
		</div>
	</div>

</div>

<?php include("footer.php");?>