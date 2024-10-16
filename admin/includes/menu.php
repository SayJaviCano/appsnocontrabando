<aside class="left-sidebar">
	<!-- Sidebar scroll-->
	<div class="scroll-sidebar">


		<nav class="sidebar-nav">	

			<ul id="sidebarnav" class="in">

				<?php if ($_SESSION['sess_admin_nivel'] < 2) { ?>
					<li class="sidebar-item <?php if ($n1==1) { echo "selected"; } ?>"><a href="inicio.php" class="sidebar-link waves-effect waves-dark <?php if ($n1==1) { echo "active"; } ?>" aria-expanded="false"><i class="mdi mdi-adjust"></i><span class="hide-menu">Actividad</span></a></li>
				<?php } ?>

				<?php if ($_SESSION['sess_admin_nivel']==0) { ?>
					<li class="sidebar-item <?php if ($n1==2) { echo "selected"; } ?>">
						<a class="sidebar-link has-arrow waves-effect waves-dark <?php if ($n1==2) { echo "active"; } ?>" href="javascript:void(0)" aria-expanded="false">
							<i class="mdi mdi-adjust"></i>
							<span class="hide-menu">Admin </span>
						</a>
						<ul aria-expanded="false" class="collapse  first-level <?php if ($n1==2) { echo "in"; } ?>">
							<li class="sidebar-item">
								<a href="administradores_listado.php" class="sidebar-link <?php if ($n1==2 && $n2==1) { echo "active"; } ?>">
									<i class="mdi mdi-adjust"></i>
									<span class="hide-menu"> Administradores </span>
								</a>
							</li>										
						</ul>
					</li>	
				<?php } ?>

				<?php if ($_SESSION['sess_admin_nivel'] < 2) { ?>

					<li class="sidebar-item <?php if ($n1==3) { echo "selected"; } ?>">
						<a class="sidebar-link has-arrow waves-effect waves-dark <?php if ($n1==3) { echo "active"; } ?>" href="javascript:void(0)" aria-expanded="false">
							<i class="mdi mdi-adjust"></i>
							<span class="hide-menu">Home</span>
						</a>
						<ul aria-expanded="false" class="collapse first-level <?php if ($n1==3) { echo "in"; } ?>">
						<li class="sidebar-item <?php if ($n1==3 && $n2=="1") { echo "active"; } ?>">
								<a href="ticker_listado.php" class="sidebar-link"><i class="mdi mdi-adjust"></i><span class="hide-menu"> Ticker</span></a></li>						
							<li class="sidebar-item <?php if ($n1==3 && $n2=="2") { echo "active"; } ?>">
								<a href="banner_listado.php" class="sidebar-link"><i class="mdi mdi-adjust"></i><span class="hide-menu"> Banner</span></a>
							</li>
											
						</ul>
					</li>

					<li class="sidebar-item <?php if ($n1==8) { echo "selected"; } ?>">
						<a class="sidebar-link has-arrow waves-effect waves-dark <?php if ($n1==8) { echo "active"; } ?>" href="javascript:void(0)" aria-expanded="false">
							<i class="mdi mdi-adjust"></i>
							<span class="hide-menu">Noticias</span>
						</a>
						<ul aria-expanded="false" class="collapse first-level <?php if ($n1==8) { echo "in"; } ?>">
							<li class="sidebar-item <?php if ($n1==8 && $n2==1) { echo "active"; } ?>">
								<a href="post_listado.php" class="sidebar-link">
									<i class="mdi mdi-adjust"></i>
									<span class="hide-menu"> Listado</span>
								</a>
							</li>						
							<li class="sidebar-item <?php if ($n1==8 && $n2==2) { echo "active"; } ?>">
								<a href="categorias_listado.php" class="sidebar-link">
									<i class="mdi mdi-adjust"></i>
									<span class="hide-menu"> Categorías</span>
								</a>
							</li>	
							<li class="sidebar-item <?php if ($n1==8 && $n2==3) { echo "active"; } ?>">
								<a href="congresos_categorias_listado.php" class="sidebar-link">
									<i class="mdi mdi-adjust"></i>
									<span class="hide-menu"> Congresos Categorías</span>
								</a>
							</li>							
						</ul>
					</li>		

					<li class="sidebar-item <?php if ($n1==10) { echo "selected"; } ?>"><a href="nosotros_listado.php" class="sidebar-link waves-effect waves-dark <?php if ($n1==10) { echo "active"; } ?>" aria-expanded="false"><i class="mdi mdi-adjust"></i><span class="hide-menu">Nosotros</span></a></li>

					<li class="sidebar-item <?php if ($n1==5) { echo "selected"; } ?>">
						<a class="sidebar-link has-arrow waves-effect waves-dark <?php if ($n1==5) { echo "active"; } ?>" href="javascript:void(0)" aria-expanded="false">
							<i class="mdi mdi-adjust"></i>
							<span class="hide-menu">Preguntas </span>
						</a>
						<ul aria-expanded="false" class="collapse first-level <?php if ($n1==5) { echo "in"; } ?>">

						<?php
						$sql = "SELECT * FROM faqs_categorias ORDER BY orden, nombre";
						$rs = $mysqli->query($sql); 
						while ($fila = $rs->fetch_array(MYSQLI_ASSOC)){
							$id_faq_categoria = $fila['id'];
							$nombre = $fila['nombre'];
							$cuantos_c = 22;
							if (strlen($nombre) > $cuantos_c) { $nombre = substr($nombre, 0, $cuantos_c) . " ..."; }
							?>
							<li class="sidebar-item <?php if ($n1==5 && $id_categoria==$id_faq_categoria) { echo "active"; } ?>">
								<a href="faqs_listado.php?cat=<?php echo $id_faq_categoria;?>" class="sidebar-link"><i class="mdi mdi-adjust"></i><span class="hide-menu"><?php echo $nombre;?></span></a>
							</li>						
						<?php } ?>
							
							<li class="sidebar-item <?php if ($n1==5 && $n2==2) { echo "active"; } ?>">
								<br><a href="faqs_categorias_listado.php" class="sidebar-link"><i class="mdi mdi-adjust"></i><span class="hide-menu"> <b>Preguntas Categorías</b></span></a>
							</li>						
						</ul>
					</li>	


					<li class="sidebar-item <?php if ($n1==6) { echo "selected"; } ?>">
						<a class="sidebar-link has-arrow waves-effect waves-dark <?php if ($n1==6) { echo "active"; } ?>" href="javascript:void(0)" aria-expanded="false">
							<i class="mdi mdi-adjust"></i>
							<span class="hide-menu">Páginas </span>
						</a>
						<ul aria-expanded="false" class="collapse first-level <?php if ($n1==6) { echo "in"; } ?>">		
							<li class="sidebar-item <?php if ($n1==6 && $slug=='nosotros') { echo "active"; } ?>">
								<a href="contenido_editar.php?slug=nosotros" class="sidebar-link">
									<i class="mdi mdi-adjust"></i>
									<span class="hide-menu"> Nosotros </span>
								</a>
							</li>									
							<li class="sidebar-item <?php if ($n1==6 && $slug=='contacto') { echo "active"; } ?>">
								<a href="contenido_editar.php?slug=buzon-de-denuncias" class="sidebar-link">
									<i class="mdi mdi-adjust"></i>
									<span class="hide-menu"> Buzón de denuncias </span>
								</a>
							</li>
							<li class="sidebar-item <?php if ($n1==6 && $slug=='contacto') { echo "active"; } ?>">
								<a href="contenido_editar.php?slug=conoce-putos-de-venta-legal" class="sidebar-link">
									<i class="mdi mdi-adjust"></i>
									<span class="hide-menu"> Puntos de venta legal </span>
								</a>
							</li>	


							<li class="sidebar-item <?php if ($n1==6 && $slug=='contacto') { echo "active"; } ?>">
								<a href="contenido_editar.php?slug=preguntanos" class="sidebar-link">
									<i class="mdi mdi-adjust"></i>
									<span class="hide-menu"> Pregúntanos </span>
								</a>
							</li>

							<li class="sidebar-item <?php if ($n1==6 && $slug=='archivo') { echo "active"; } ?>">
								<a href="contenido_editar.php?slug=archivo" class="sidebar-link">
									<i class="mdi mdi-adjust"></i>
									<span class="hide-menu"> Archivo </span>
								</a>
							</li>

							<li class="sidebar-item <?php if ($n1==6 && $slug=='contacto') { echo "active"; } ?>">
								<a href="contenido_editar.php?slug=contacto" class="sidebar-link">
									<i class="mdi mdi-adjust"></i>
									<span class="hide-menu"> Contáctanos </span>
								</a>
							</li>
							<li class="sidebar-item <?php if ($n1==6 && $slug=='aviso-legal') { echo "active"; } ?>">
								<a href="contenido_editar.php?slug=aviso-legal" class="sidebar-link">
									<i class="mdi mdi-adjust"></i>
									<span class="hide-menu"> Aviso Legal </span>
								</a>
							</li>	
							<li class="sidebar-item <?php if ($n1==6 && $slug=='politica-privacidad') { echo "active"; } ?>">
								<a href="contenido_editar.php?slug=politica-privacidad" class="sidebar-link">
									<i class="mdi mdi-adjust"></i>
									<span class="hide-menu"> Política de Privacidad </span>
								</a>
							</li>	
							<li class="sidebar-item <?php if ($n1==6 && $slug=='politica-cookies') { echo "active"; } ?>">
								<a href="contenido_editar.php?slug=politica-cookies" class="sidebar-link">
									<i class="mdi mdi-adjust"></i>
									<span class="hide-menu"> Politica de cookies </span>
								</a>
							</li>											
						</ul>
					</li>	
				<?php } ?>
				
				<li class="sidebar-item <?php if ($n1==7) { echo "selected"; } ?>">
					<a class="sidebar-link <?php if ($n1==7) { echo "active"; } ?>" href="denuncias_listado.php" aria-expanded="false"><i class="mdi mdi-adjust"></i><span class="hide-menu">Listado de Denuncias </span></a>
				</li>							
					

				<?php if ($_SESSION['sess_admin_nivel'] < 2) { ?>
					<li class="sidebar-item <?php if ($n1==9) { echo "selected"; } ?>">
						<a class="sidebar-link has-arrow waves-effect waves-dark <?php if ($n1==9) { echo "active"; } ?>" href="javascript:void(0)" aria-expanded="false">
							<i class="mdi mdi-adjust"></i>
							<span class="hide-menu">APPS</span>
						</a>
						<ul aria-expanded="false" class="collapse  first-level <?php if ($n1==9) { echo "in"; } ?>">

							<li class="sidebar-item">
								<a href="apps_notificaciones_listado.php" class="sidebar-link <?php if ($n1==9 && $n2==1) { echo "active"; } ?>">
									<i class="mdi mdi-adjust"></i>
									<span class="hide-menu"> Notificaciones Push</span>
								</a>
							</li>	
							<li class="sidebar-item">
								<a href="apps_dispositivos_listado.php" class="sidebar-link <?php if ($n1==9 && $n2==2) { echo "active"; } ?>">
									<i class="mdi mdi-adjust"></i>
									<span class="hide-menu"> Dispositivos </span>
								</a>
							</li>		
							<li class="sidebar-item">
								<a href="apps_log.php" class="sidebar-link <?php if ($n1==9 && $n2==3) { echo "active"; } ?>">
									<i class="mdi mdi-adjust"></i>
									<span class="hide-menu"> Log </span>
								</a>
							</li>								
						</ul>
					</li>	
				<?php } ?>					
      
			</ul>
		</nav>

		<!-- End Sidebar navigation -->
	</div>
	<!-- End Sidebar scroll-->
</aside>