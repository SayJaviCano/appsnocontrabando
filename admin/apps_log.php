<?php
session_start();
$roles = array('0','1');
include('includes/segur.php');

$path_relative = "../";
include('../includes/conexion.php');

$n1=9;
$n2=3;


$procedencia = (isset($_POST['procedencia']) ? $_POST['procedencia'] : ""); if ($procedencia==""){  $procedencia = (isset($_GET['procedencia']) ? $_GET['procedencia'] : ""); }


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
		function filtro() { 
			procedencia = document.getElementById('procedencia').value;
			location.href = "apps_log.php?procedencia=" + procedencia;
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
							<select id="procedencia" name="procedencia" onchange="filtro()" class="custom-select" data-live-search="true" title="Selecciona procedencia" data-width="400px" >
								<option value="">Todas las procedencias</option>
								<option value="Pulsera" <?php if ($procedencia == "Pulsera") { echo "selected"; }?> >Pulsera</option>
								<option value="Medios" <?php if ($procedencia == "Medios") { echo "selected"; }?> >Medios</option>
								<option value="Web" <?php if ($procedencia == "Web") { echo "selected"; }?> >Web</option>
								<option value="Dípticos" <?php if ($procedencia == "Dípticos") { echo "selected"; }?> >Dípticos</option>
								<option value="Prueba" <?php if ($procedencia == "Prueba") { echo "selected"; }?> >Prueba</option>									
							</select>						
						</li>									  
						<li class="mr-3"></li>
					</ul>
				</div>
			</nav>
		</header>

		<?php include('includes/menu.php');	?>
        <div class="page-wrapper">

            <div class="page-breadcrumb">
                <div class="row">
                    <div class="col">
                        <h4 class="page-title">LOG</h4>
                    </div>
                </div>
            </div>

            <div class="container-fluid">
				<div class="row">
                    <div class="col card card-body">
					<!-- CONTENT -->


		<!-- Table  -->
		<table class="table table-striped">

			<thead class="grey lighten-2">
				<tr scope="row">			
					<th>Fecha</th>
					<th>QR</th>
					<th>Procedencia</th>
					<th>Estanco</th>	
					<th>Dispositivo</th>	
					<th>Plataforma</th>		
					<th>Session ID</th>
					<th>IP</th>		
					<th>Navegador</th>
					<th>Redirect</th>		
				</tr>
			</thead>

			<tbody>
	<?php

		$sql = "SELECT * FROM log_app";
		if ($procedencia != "") {
			$sql.= " WHERE procedencia = '$procedencia'";
		}
		$sql.= " ORDER BY fecha DESC";
		$rs = $mysqli->query($sql); 
		if ($rs->num_rows > 0) {
			$num_total_registros = $rs->num_rows; 
			$conta_ios = 0;
			$conta_android = 0;
			$conta_web = 0;

			while ($fila = $rs->fetch_array(MYSQLI_ASSOC)){

				$fecha = cambiaf_a_normal_hora($fila["fecha"]);
				$qr = $fila['qr'];
				$estanco = $fila['estanco'];
				$procedencia = $fila['procedencia'];
				$session_id = $fila['session_id'];
				$ip = $fila['ip'];
				$navegador = $fila['navegador'];
				$version = $fila['version'];
				$plataforma = $fila['plataforma'];
				$dispositivo = $fila['dispositivo'];			
				$url_redirect = $fila['url_redirect'];			

				?>
				<tr scope="row">
					<td><?php echo $fecha;?></td>	
					<td><?php echo $qr;?></td>
					<td><?php echo $procedencia;?></td>
					<td><?php echo $estanco;?></td>
					<td><?php echo $dispositivo;?></td>
					<td><?php echo $plataforma;?></td>
					<td><?php echo $session_id;?></td>					
					<td><?php echo $ip;?></td>
					<td><?php echo $navegador . " " . $version;?></td>
					<td><?php echo $url_redirect;?></td>
					
				</tr>
				<?php			
			}
		} else {?>
				<tr scope="row">
				  <td colspan="9" align="center">No hay ningun registro</td>
				</tr>
		<?php }	?>

			<tr scope="row">
				<td colspan="9" class="white">
					Total: <?php echo number_format($num_total_registros, 0, ',', '.');?>			
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