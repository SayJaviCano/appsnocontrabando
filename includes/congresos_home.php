<?php include("header.php");?>

<div class="container">

	<div class="row justify-content-center">
		<div class="col-md-10">	
			<h1 class="text-center tit1">Congreso Frente al Contrabando Altadis</h1>
		</div>
	</div>	

	<div class="row mb-3">	
		<div class="col-md-10 mx-auto">
			<div class="text-center entradilla">En Altadis estamos comprometidos en la lucha frente al comercio ilícito de tabaco. Con este fin, desde 2015 la compañía organiza el Congreso nacional Frente al Contrabando de Tabaco, que reúne a todos los actores implicados en combatir este problema social. Cada año, el congreso pone el foco en las medidas más relevantes y su contribución en la reducción del contrabando de tabaco en España.</div>
		</div>
	</div>

	<div class="row mb-3">	
		<div class="col-md-6 mx-auto">

			<div class="congresos_categorias">

			<?php
				$sql = "SELECT * FROM congresos_categorias WHERE activo=1 ORDER BY orden DESC, nombre";
				$rs = $mysqli->query($sql); 
				if ($rs!="") {
					$num_total_registros = $rs->num_rows; 
					$contador = 1;
					while ($fila = $rs->fetch_array(MYSQLI_ASSOC)){
						$id = $fila['id'];
						$slug = $fila['slug'];
						$nombre = $fila['nombre'];

						$sql3 = "SELECT * FROM contenidos WHERE activo=1 AND tipo='post' AND id_congresos_categoria=$id";
						$rs3 = $mysqli->query($sql3); 
						if ($rs3->num_rows > 0) {

							?>
							<div class="congresos_categoria <?php if($contador==1) { echo "top"; } ?>">
								<a href="<?php echo $dominio . "category/congresos/$slug/"?>" title="<?php echo $nombre?>"><?php echo $nombre?></a>			
							</div>
							<?php
							$contador++;
						}
					}
				}
				?>

			</div>

		</div>
	</div>

</div>

<?php include("footer.php");?>