<?php
session_start();
$roles = array('0','1');
include('includes/segur.php');

$path_relative = "../";
include('../includes/conexion.php');

$n1=9;
$n2=2;

$id = (isset($_GET['id']) ? $_GET['id'] : ""); if ($id==""){  $id = (isset($_POST['id']) ? $_POST['id'] : ""); }
$valor = (isset($_GET['val']) ? $_GET['val'] : ""); if ($valor==""){  $valor = (isset($_POST['val']) ? $_POST['val'] : ""); }
$accion = (isset($_GET['accion']) ? $_GET['accion'] : ""); if ($accion==""){  $accion = (isset($_POST['accion']) ? $_POST['accion'] : ""); }

$tipo = (isset($_GET['tipo']) ? $_GET['tipo'] : ""); if ($tipo==""){  $tipo = (isset($_POST['tipo']) ? $_POST['tipo'] : ""); }
if (!is_numeric($tipo)) { $tipo = 0; }


if ($accion=="borrar") {
	foreach ($_POST["borrar"] as $key => $value) { 
		$valor = str_replace("#", ",", $value);
		$sql = "DELETE FROM app_dispositivos WHERE id=$valor";
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
	$(document).ready(function() {
		total_text = $('.total_pie').html();
		$('.total_sup').html(total_text);
	});
	function f_borrar() {
		var conf = confirm("¿Seguro que desea borrar este/os elemento/s?");
		if(conf==true){
			document.form1.action = "apps_dispositivos_listado.php?accion=borrar";
			document.form1.submit()
		}
	}	
	function filtro() { 
		document.form1.submit();
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
						<li class="mr-3">
							<select id="tipo" name="tipo" onchange="filtro()" class="custom-select" data-live-search="true" title="Selecciona Tipo" data-width="400px" >
								<option value="0">Todos los dispositivos</option>
								<option value="1" <?php if ($tipo==1) { echo "selected"; } ?>>IOS</option>
								<option value="2" <?php if ($tipo==2) { echo "selected"; } ?>>Android</option>
								<!-- <option value="3" <?php if ($tipo==3) { echo "selected"; } ?>>Web</option> -->
							</select>				
						</li>									  
						<li class="mr-3"> <!-- <button type="button" class="btn btn-primary" onclick="f_editar('')">Nuevo</button> --></li>
					</ul>
				</div>
			</nav>
		</header>

		<?php include('includes/menu.php');	?>
        <div class="page-wrapper">

            <div class="page-breadcrumb">
                <div class="row">
                    <div class="col">
                        <h4 class="page-title">Dispositivos</h4>
                    </div>
                </div>
            </div>

            <div class="container-fluid">
				<div class="row">
                    <div class="col card card-body">
					<!-- CONTENT -->

					<div class="total_sup" style="margin-bottom:15px;">			
					</div>

		<!-- Table  -->
		<table class="table table-striped">

			<thead class="grey lighten-2">
				<tr scope="row">		
					<th width="50" align="center"></th>		
					<th width="200">Fecha Alta</th>
					<th width="200">Fecha Conexión</th>
					<th width="50">Tipo</th>					
					<th width="200">Dispositivo</th>
					<th>Plataforma</th>		
					<!-- <th>Token</th> -->
				</tr>
			</thead>

			<tbody>
	<?php

		$sql = "SELECT * FROM app_dispositivos ";
		if ($tipo!=0) { $sql .= " WHERE tipo=$tipo"; }
		$sql .= " ORDER BY fecha_ult_conexion DESC";

		$rs = $mysqli->query($sql); 
		if ($rs->num_rows > 0) {
			$num_total_registros = $rs->num_rows; 
			$conta_ios = 0;
			$conta_android = 0;
			$conta_web = 0;

			while ($fila = $rs->fetch_array(MYSQLI_ASSOC)){
				$id = $fila['id'];				
				$token = $fila['token'];	
				$dispositivo_tipo = $fila['tipo'];	
				$fecha_alta = cambiaf_a_normal_hora($fila["fecha_alta"]);
				$fecha_ult_conexion = cambiaf_a_normal_hora($fila['fecha_ult_conexion']);

				$ip = $fila['ip'];
				$navegador = $fila['navegador'];
				$version = $fila['version'];
				$plataforma = $fila['plataforma'];
				$dispositivo = $fila['dispositivo'];																

				$tipo_tx = "Desconocido";
				if ($dispositivo_tipo=="1")		{ $tipo_tx = "IOS"; 	$conta_ios++; } 
				elseif ($dispositivo_tipo=="2")	{ $tipo_tx = "Android"; $conta_android++; } 	
				elseif ($dispositivo_tipo=="3")	{ $tipo_tx = "Web"; $conta_web++; } 							

				?>
				<tr scope="row">
					<td align="center" valign="top"><input type="checkbox" class="none" name="borrar[]" id="borrar[]" value="<?php echo $id;?>"></td>					
					<td><?php echo $fecha_alta;?></td>
					<td><?php echo $fecha_ult_conexion;?></td>
					<td><?php echo $tipo_tx;?></td>					
					<td><?php echo $dispositivo;?></td>
					<td><?php echo $plataforma;?></td>
					<!-- <td style="word-break: break-all;"><?php echo($token); ?></td> -->
				</tr>
				<?php			
			}
		} else {?>
				<tr scope="row">
				  <td colspan="8" align="center">No hay ningun registro</td>
				</tr>
		<?php }	?>
			<tr>
				<td colspan="2" class="white"><button type="button" class="btn btn-danger" onClick="f_borrar()">Borrar</button></td>
				<td colspan="6" align="center" class="white"><?php include('includes/paginacion.php');?></td>
			</tr>

			<tr scope="row">
				<td colspan="8" class="white">
					<div class="total_pie">
					Total: <?php echo number_format($num_total_registros, 0, ',', '.');?>
					<?php if ($tipo==0)  {?>
						 :: <b>IOS</b>: <?php echo number_format($conta_ios, 0, ',', '.');?>
						 - <b>Android</b>: <?php echo number_format($conta_android, 0, ',', '.');?>
						 <!-- - <b>Web</b>: <?php echo number_format($conta_web, 0, ',', '.');?> -->
					<?php } ?>				
					</div>
				</td>
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