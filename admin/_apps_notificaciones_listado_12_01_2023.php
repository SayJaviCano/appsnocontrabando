<?php
session_start();
$roles = array('0','1','2');
include('includes/segur.php');

$path_relative = "../";
include('../includes/conexion.php');

$n1=9;
$n2=1;

$id = (isset($_GET['id']) ? $_GET['id'] : ""); if ($id==""){  $id = (isset($_POST['id']) ? $_POST['id'] : ""); }
$valor = (isset($_GET['val']) ? $_GET['val'] : ""); if ($valor==""){  $valor = (isset($_POST['val']) ? $_POST['val'] : ""); }
$accion = (isset($_GET['accion']) ? $_GET['accion'] : ""); if ($accion==""){  $accion = (isset($_POST['accion']) ? $_POST['accion'] : ""); }

if ($accion=="borrar") {
	foreach ($_POST["borrar"] as $key => $value) { 
		$id_borrar = str_replace("#", ",", $value);

		$sql = "DELETE FROM app_ca_envios WHERE id_notificacion=$id_borrar";
		$rs = $mysqli->query($sql);

		$sql = "DELETE FROM app_ca_campaign WHERE id=$id_borrar";
		$rs = $mysqli->query($sql);		
	} 
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title></title>

 	<link href="dist/css/style.css" rel="stylesheet">
	<link href="css/custom.css" rel="stylesheet">

	<script src="assets/libs/jquery/dist/jquery.min.js"></script>
    <script src="assets/libs/popper.js/dist/umd/popper.min.js"></script>
    <script src="assets/libs/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js"></script>

    <script src="dist/js/app.min.js"></script>
    <script src="dist/js/app.init.js"></script>
    <script src="dist/js/app-style-switcher.js"></script>

    <script src="dist/js/waves.js"></script>
    <script src="dist/js/sidebarmenu.js"></script>
    <script src="dist/js/custom.min.js"></script>

	<script type="text/javascript" src="../js/comun.js"></script>

	<script type="text/JavaScript">
	<!--		

	function f_editar(id) { 
			location.href = "apps_notificaciones_editar.php?id=" + id;
		}

	function filtro() { 
			document.form1.submit();
	}			
	function f_borrar() {
			var conf = confirm("¿Seguro que desea borrar este/os elemento/s?");
			if(conf==true){
				document.form1.action = "apps_notificaciones_listado.php?accion=borrar";
				document.form1.submit()
			}
	}	
	function ver_botones_envio() {
		$("#botones_enviar").show();
	}	

	
	function f_enviar1(id) {
		document.getElementById("id").value = id;
		document.getElementById("accion").value = 'enviar1';
		document.form1.action="apps_notificaciones_listado.php";
		document.form1.submit();
	}
	function f_enviar2(id) {
		var conf = confirm("¿Seguro que desea enviar esta notificación?.");
		if(conf==true){
			document.getElementById("id").value = id;
			document.getElementById("accion").value = 'enviar2';
			document.form1.action="apps_notificaciones_listado.php";
			document.form1.submit();
		}
	}
	function realizar_envio(id) {
		url = "apps_notificaciones_enviar.php?id_notificacion="+id;
		document.getElementById('ifram_enviar').src = url;
	}			
	-->
	</script>
</head>

<div class="preloader">
	<div class="lds-ripple">
		<div class="lds-pos"></div>
		<div class="lds-pos"></div>
	</div>
</div>

<form name="form1" id="form1" method="post">
	<input name="accion" type="hidden" id="accion">
	<input name="aux_borrar" type="hidden" id="aux_borrar">
	<input name="id" type="hidden" id="id" value="">

	<div id="main-wrapper">
		<header class="topbar">
			<nav class="navbar top-navbar navbar-expand-md navbar-dark">
				<div class="navbar-header">
					<a class="nav-toggler waves-effect waves-light d-block d-md-none" href="javascript:void(0)">
						<i class="ti-menu ti-close"></i>
					</a>
					<div class="navbar-brand">
						<a href="inicio.php" class="logo">
							<span class="logo-text"><img src="images/logo.jpg" class="light-logo w100"/></span>
						</a>
						<a class="sidebartoggler d-none d-md-block" href="javascript:void(0)" data-sidebartype="mini-sidebar"><i class="mdi mdi-toggle-switch mdi-toggle-switch-off font-20"></i></a>
					</div>
					<a class="topbartoggler d-block d-md-none waves-effect waves-light" href="javascript:void(0)" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><i class="ti-more"></i></a>
				</div>
				<div class="navbar-collapse collapse" id="navbarSupportedContent">
					<ul class="navbar-nav float-left mr-auto">
						<li class="nav-item search-box">&nbsp;</li>
					</ul>
					<ul class="navbar-nav float-right">
						<li class="mr-3"> <button type="button" class="btn btn-primary" onclick="f_editar('')">Nuevo</button></li>
					</ul>
				</div>
			</nav>
		</header>

		<?php include('includes/menu.php');	?>
        <div class="page-wrapper">

            <div class="page-breadcrumb">
                <div class="row">
                    <div class="col">
                        <h4 class="page-title">Notificaciones Push</h4>
                    </div>
                </div>
            </div>

            <div class="container-fluid">
				<div class="row">
                    <div class="col card card-body">
					<!-- CONTENT -->

<?php
if ($accion=="enviar1") {

		if ($id!=0) {
			$sql = "SELECT * FROM app_ca_campaign WHERE enviado=0 AND id=$id";
			$rs = $mysqli->query($sql); 
			if ($rs->num_rows > 0) {
				$fila = $rs->fetch_assoc();				
				$fecha_alta = cambiaf_a_normal($fila['fecha_alta']);	
	
				$nombre = $fila['nombre'];	
				$titulo = $fila['titulo'];	
				$texto = $fila['texto'];	
				$enlace = $fila['enlace'];	

				$grupo_envio = $fila['grupo_envio'];	// 0=Todos, 1=IOS, 2=Android, 3=Web
				$tx_enviado_a = "IOS + Android + Web";
				if ($grupo_envio == 1) 		{ $tx_enviado_a = "IOS"; 	}
				elseif ($grupo_envio == 2)	{ $tx_enviado_a = "Android";}
				elseif ($grupo_envio == 3)	{ $tx_enviado_a = "Web";}

				$total_envios = 0;
				$sql3 = "SELECT * FROM app_dispositivos";
				if ($grupo_envio==1) { $sql3 = "SELECT * FROM app_dispositivos WHERE tipo=1"; }
				if ($grupo_envio==2) { $sql3 = "SELECT * FROM app_dispositivos WHERE tipo=2"; }
				if ($grupo_envio==3) { $sql3 = "SELECT * FROM app_dispositivos WHERE tipo=3"; }

				$rs3 = $mysqli->query($sql3);
                if ($rs3) {
                    $total_envios = $rs3->num_rows;
                }
					
					
			}
		}
		?>
		<table class="table" align="center" style="width:700px;">
		<tr>
			<td class="grey lighten-2 text-center" style="padding-top:12px;" colspan="2"><h3>Envío de notificación</h3></td>
		</tr>
		<tr>
			<td class="grey lighten-2" style="padding:8px;" width="150">Nombre</td>
			<td style="padding:8px;"><?php echo $nombre;?></td>
		</tr>
		<tr>
			<td class="grey lighten-2" style="padding:8px;">Mensaje</td>
			<td style="padding:8px;">
				<b><?php echo $titulo;?></b>
				<?php if ($texto!="") { echo "<br><br>$texto"; } ?>
			</td>
		</tr>
		<tr>
			<td class="grey lighten-2" style="padding:8px;">Dispositivos</td>
			<td style="padding:8px;"><?php echo $tx_enviado_a;?></td>
		</tr>


		<tr>
			<td colspan="2" align="center" id="botones_enviar" style="padding:15px;">
				<input type="button" name="Button3" value="Enviar (<?php echo number_format($total_envios, 0, ',', '.');?>)" class="btn btn-primary" onClick="f_enviar2('<?php echo $id;?>')">
			</td>
		</tr>
		</table>
		<hr><br><br>
<?php } ?>	



<?php if ($accion=="enviar2") {

	if ($id!=0) {

		$sql = "SELECT * FROM app_ca_campaign WHERE enviado=0 AND id=$id";
		$rs = $mysqli->query($sql); 
		if ($rs->num_rows > 0) {
			$fila = $rs->fetch_assoc();
			$fecha_alta = cambiaf_a_normal($fila['fecha_alta']);

			$nombre = $fila['nombre'];
			$titulo = $fila['titulo'];
			$texto = $fila['texto'];
			$enlace = $fila['enlace'];

			$grupo_envio = $fila['grupo_envio'];	// 0=Todos, 1=IOS, 2=Android
			$tx_enviado_a = "IOS + Android + Web";
			if ($grupo_envio == 1)		{ $tx_enviado_a = "IOS"; } 
			elseif ($grupo_envio == 2)	{ $tx_enviado_a = "Android"; }
			elseif ($grupo_envio == 3)	{ $tx_enviado_a = "Web"; }
		}
	}
?>
	<table class="table" align="center" style="width:700px;">
		<tr>
			<td class="grey lighten-2 text-center" style="padding-top:12px;" colspan="2"><h3>Enviando notificación</h3></td>
		</tr>
		<tr>
			<td class="grey lighten-2" style="padding:8px;" width="150">Nombre</td>
			<td style="padding:8px;"><?php echo $nombre;?></td>
		</tr>
		<tr>
			<td class="grey lighten-2" style="padding:8px;">Mensaje</td>
			<td style="padding:8px;">
				<b><?php echo $titulo;?></b>
				<?php if ($texto!="") { echo "<br><br>$texto"; } ?>
			</td>
		</tr>
		<tr>
			<td class="grey lighten-2" style="padding:8px;">Dispositivos</td>
			<td style="padding:8px;"><?php echo $tx_enviado_a;?></td>
		</tr>
		<tr>
			<td colspan="2" style="padding: 10px 0 0 0;">
				<iframe id="ifram_enviar" width="100%" height="400" src="apps_notificaciones_preparar_envio.php?id_notificacion=<?php echo $id;?>" frameborder="0" allowfullscreen></iframe>
			</td>
		</tr>
	</table>
<hr><br><br>
<?php } ?>



		<!-- Table  -->
		<table class="table table-striped">

			<thead class="grey lighten-2">
				<tr scope="row">		
					<th width="50" align="center"></th>		
				  	<th>Notificación</th>
					<th width="150" align="center">Enviados</th>
					<th width="150" align="center">Recibidos (Beta)</th>
					<th width="150" align="center">Clic  (Beta)</th>
					<th width="100">&nbsp;</th>
					<th width="145">&nbsp;</th>
				</tr>
			</thead>

			<tbody>
	<?php

		$sql = "SELECT * FROM app_ca_campaign ORDER by id DESC";
		$rs = $mysqli->query($sql); 
		if ($rs->num_rows > 0) {
			$num_total_registros = $rs->num_rows; 
			while ($fila = $rs->fetch_array(MYSQLI_ASSOC)){
				$id_notificacion = $fila['id'];		
				$fecha_alta = cambiaf_a_normal($fila['fecha_alta']);	
				$fecha_envio = $fila['fecha_envio'];	

				$nombre = $fila['nombre'];	
				$titulo = $fila['titulo'];	
				$texto = $fila['texto'];	
				$enlace = $fila['enlace'];	
				$enviado = $fila['enviado'];
				
				$grupo_envio = $fila['grupo_envio'];	// 0=Todos, 1=IOS, 2=Android, 3=WEB
				$tx_enviado_a = "todos los dispositivos";
				if ($grupo_envio == 1) 		{ $tx_enviado_a = "IOS"; 	}
				elseif ($grupo_envio == 2)	{ $tx_enviado_a = "Android";}
				elseif ($grupo_envio == 2)	{ $tx_enviado_a = "Web";}

				$enviados_total=0; $enviados_ios=0; $enviados_android=0; $enviados_web=0;
				$recibidos_total=0; $recibidos_ios= 0; $recibidos_android=0; $recibidos_web=0; 
				$clic_total=0; $clic_ios= 0; $clic_android=0; $clic_web=0; 
				
				?>
				<tr scope="row">	
					<td align="center" valign="top"><input type="checkbox" class="none" name="borrar[]" id="borrar[]" value="<?php echo $id_notificacion;?>"></td>		
					<td>
						<div class="campa_nombre"><?php echo $nombre;?></div>
						<div class="campa_titulo"><?php echo $titulo;?></div>
						<?php if ($texto!="")  { ?><div class="campa_texto"><?php echo $texto;?></div><?php } ?>
						<?php if ($enlace!="")  { ?><div class="campa_enlace"><a href="<?php echo $enlace;?>" target="_blank"><?php echo $enlace;?></a></div><?php } ?>
						<?php

							/*
							$enviados_ios = $fila['enviados_ios'];	
							$enviados_android = $fila['enviados_android'];	
							$enviados_web = $fila['enviados_web'];									

							$recibidos_ios = $fila['recibidos_ios'];	
							$recibidos_android = $fila['recibidos_android'];
							$recibidos_web = $fila['recibidos_web'];								

							$clic_ios = $fila['clic_ios'];	
							$clic_android = $fila['clic_android'];	
							$clic_web = $fila['clic_web'];		
							
							$enviados_total = $enviados_ios + $enviados_android + $enviados_web;
							*/
							//if ($enviados_total==0) {

								$enviados_total=0; $enviados_ios=0; $enviados_android=0; $enviados_web=0;
								$recibidos_total=0; $recibidos_ios= 0; $recibidos_android=0; $recibidos_web=0; 
								$clic_total=0; $clic_ios= 0; $clic_android=0; $clic_web=0; 

								$sql2 = "SELECT * FROM app_ca_envios WHERE id_notificacion=$id_notificacion";
								$rs2 = $mysqli->query($sql2); 
								$enviados_total = $rs2->num_rows;
								while ($fila2 = $rs2->fetch_array(MYSQLI_ASSOC)) {
									$id_registro = $fila2['id'];
									$tipo = $fila2['tipo_dispositivo'];

									$enviada_notificacion = $fila2['enviado'];										
									if ($enviada_notificacion == 1) {
										if  ($tipo==1)		{ $enviados_ios ++;		}
										elseif  ($tipo==2)	{ $enviados_android++;	}
										elseif  ($tipo==3)	{ $enviados_web++;		}

									}
									$recibido = $fila2['recibido'];
									if ($recibido == 1) {
										if  ($tipo==1)		{ $recibidos_ios++;	}
										elseif  ($tipo==2)	{ $recibidos_android++;	}
										elseif  ($tipo==3)	{ $recibidos_web++;		}
									}

									$clic = $fila2['clic'];
									if ($clic == 1) {
										if  ($tipo==1)		{ $clic_ios++;	}
										elseif  ($tipo==2)	{ $clic_android++;	}
										elseif  ($tipo==3)	{ $clic_web++;		}
									}										
								}

								$sql2 = "Update app_ca_campaign SET enviados_ios='$enviados_ios', enviados_android='$enviados_android', enviados_web='$enviados_web', recibidos_ios='$recibidos_ios', recibidos_android='$recibidos_android', recibidos_web='$recibidos_web', clic_ios='$clic_ios', clic_android='$clic_android', clic_web='$clic_web'";
								$sql2 = $sql2 . " WHERE id='$id'"; 
								$rs2 = $mysqli->query($sql2);

							//}	

							$enviados_total = $enviados_ios + $enviados_android + $enviados_web;	
							$recibidos_total = $recibidos_ios + $recibidos_android + $recibidos_web;
							$clic_total = $clic_ios + $clic_android + $clic_web;	
								
							if ($enviado==1)
							{								
								?>
								<div class="campa_tx2"><?php echo "Enviado el <b>" . cambiaf_a_normal_hora($fecha_envio) . "</b>";?></div>								
								<?php
							}
						?>
					</td>
					<td align="right">
						<table border="0" cellpadding="0" cellspacing="0" width="100%">
							<TR>
								<TD><i class="fab fa-apple"></i></TD>
								<TD align="right"><b><?php echo number_format($enviados_ios, 0, ',', '.');?></b></TD>
							</TR>
							<TR>
								<TD><i class="fab fa-android"></i></TD>
								<TD align="right"><b><?php echo number_format($enviados_android, 0, ',', '.');?></b></TD>
							</TR>
							<!--
							<TR>
								<TD><i class="fab fa-internet-explorer"></i></TD>
								<TD align="right"><b><?php echo number_format($enviados_web, 0, ',', '.');?></b></TD>
							</TR>	
							-->						
							<TR>
								<TD>TOTAL</TD>
								<TD width="100" align="right"><b><?php echo number_format($enviados_total, 0, ',', '.');?></b></TD>
							</TR>
						</table>					
					</td>	
					<td align="right">
						<table border="0" cellpadding="0" cellspacing="0" width="100%">
							<TR>
								<TD><i class="fab fa-apple"></i></TD>
								<TD align="right"><b><?php echo number_format($recibidos_ios, 0, ',', '.');?></b></TD>
							</TR>
							<TR>
								<TD><i class="fab fa-android"></i></TD>
								<TD align="right"><b><?php echo number_format($recibidos_android, 0, ',', '.');?></b></TD>
							</TR>
							<!--
							<TR>
								<TD><i class="fab fa-internet-explorer"></i></TD>
								<TD align="right"><b><?php echo number_format($recibidos_web, 0, ',', '.');?></b></TD>
							</TR>	
							-->									
							<TR>
								<TD>TOTAL</TD>
								<TD width="100" align="right"><b><?php echo number_format($recibidos_total, 0, ',', '.');?></b></TD>
							</TR>
						</table>
					</td>	
					<td align="right">
						<table border="0" cellpadding="0" cellspacing="0" width="100%">
							<TR>
								<TD><i class="fab fa-apple"></i></TD>
								<TD align="right"><b><?php echo number_format($clic_ios, 0, ',', '.');?></b></TD>
							</TR>
							<TR>
								<TD><i class="fab fa-android"></i></TD>
								<TD align="right"><b><?php echo number_format($clic_android, 0, ',', '.');?></b></TD>
							</TR>
							<!--
							<TR>
								<TD><i class="fab fa-internet-explorer"></i></TD>
								<TD align="right"><b><?php echo number_format($clic_web, 0, ',', '.');?></b></TD>
							</TR>
							-->										
							<TR>
								<TD>TOTAL</TD>
								<TD width="100" align="right"><b><?php echo number_format($clic_total, 0, ',', '.');?></b></TD>
							</TR>
						</table>
					</td>						

					<?php
					if ($enviado==1) { ?>	
						<td align="center" colspan="2"><div class="btn btn-success w100">Enviado</div></td>					
					<?php } else { ?>
						<td align="center"><a class="btn btn-primary w100" href="apps_notificaciones_editar.php?id=<?php echo $id_notificacion;?>">Editar</a></td>	
						<td align="center"><button type="button" class="btn btn-info w100 preparar_envio" onClick="f_enviar1('<?php echo $id_notificacion;?>')" <?php if ($accion=="enviar2" && $id_notificacion==$id) { echo "disabled"; } ?>>Preparar envio</button></td>
					<?php } ?>

					
				  					  
				</tr>
				<?php			
			}
		} else {?>
				<tr scope="row">
				  <td colspan="7" align="center">No hay ningun registro</td>
				</tr>
		<?php }	?>
		<tr>
				<td colspan="7" class="white"><button type="button" class="btn btn-danger" onClick="f_borrar()">Borrar</button></td>
			</tr>		
		</tbody>
		<!-- Table body -->
	  </table>  
	  <!-- Table  -->

					<!-- CONTENT -->
                    </div>
				</div>
            </div>
        </div>
	</div><!-- End Wrapper -->
</form>

</body>

</html>