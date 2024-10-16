<?php
session_start();
$roles = array('0','1');
include('includes/segur.php');

$path_relative = "../";
include('../includes/conexion.php');
$n1=1;



$server_name = (isset($_GET['server_name']) ? $_GET['server_name'] : ""); if ($server_name==""){  $server_name = (isset($_POST['server_name']) ? $_POST['server_name'] : ""); }

$accion = (isset($_POST['accion']) ? $_POST['accion'] : "");

if ($accion=="aplicar") {
	$fechaFin = (isset($_POST['fechaFin']) ? $_POST['fechaFin'] : ""); 
	$fechaInicio = (isset($_POST['fechaInicio']) ? $_POST['fechaInicio'] : ""); 

	$fechaFin = str_replace("/", "-", $fechaFin);
	$fechaInicio = str_replace("/", "-", $fechaInicio);
	$rango_fechas=99;
} else {

	$rango_fechas=0;
	$rango_fechas = (isset($_GET['rango_fechas']) ? $_GET['rango_fechas'] : ""); if ($rango_fechas==""){  $rango_fechas = (isset($_POST['rango_fechas']) ? $_POST['rango_fechas'] : ""); }
	if(!is_numeric($rango_fechas)) { $rango_fechas=0; }
	

	$fechaFin = date("d-m-Y");
	//$fechaFin = date("d-m-Y", strtotime($fechaFin."+ 1 days")); 
	if ($rango_fechas==0)  {
		$cuantos_dias = 7;
		$fechaInicio = date("d-m-Y", strtotime($fechaFin . "- $cuantos_dias days")); 
	}
	elseif ($rango_fechas==1)  { 	
		$cuantos_dias = date("j") - 1;
		$fechaInicio = date("d-m-Y", strtotime($fechaFin . "- $cuantos_dias days")); 
	}
	elseif ($rango_fechas==2)  { 
		$month = date('m', strtotime('-1 month'));
		$year = date('Y', strtotime('-1 month'));	
		$day = date("d", mktime(0,0,0, $month+1, 0, $year));
		$fechaFin = date('d-m-Y', mktime(0,0,0, date('m'), 1, $year));
		$fechaInicio = date('d-m-Y', mktime(0,0,0, $month, 1, $year));
	}
	elseif ($rango_fechas==3)  { 
		$fechaInicio = date('d-m-Y', mktime(0,0,0, 1, 1, date('year')));
	}
	elseif ($rango_fechas==4)  {
		$year = date('Y', strtotime('-1 year'));
		$fechaFin = date('d-m-Y', mktime(0,0,0, 1, 1, date('year')));
		$fechaFin = date("d-m-Y", strtotime($fechaFin."- 1 days")); 
		$fechaInicio = date('d-m-Y', mktime(0,0,0, 1, 1, $year));
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

	<script src="dist/js/moment.js"></script>
	<script src="dist/js/echarts-en.min.js"></script>
	<script src="dist/js/jquery.tabledit.js"></script>

	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta/dist/css/bootstrap-select.min.css">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta/dist/js/bootstrap-select.min.js"></script>

	<script type="text/javascript" src="../js/comun.js"></script>

	<link href="css/custom.css" rel="stylesheet">

	<link href="css/bootstrap-datepicker.css" rel="stylesheet">
	<script src="js/bootstrap-datepicker.js"></script>
	<script src="locales/bootstrap-datepicker.es.min.js" charset="UTF-8"></script>


	<script type="text/JavaScript">
	<!--
	function filtro() { 
		document.form1.submit();
	}		
	function aplicar() { 
		document.getElementById("accion").value="aplicar";
		document.form1.submit();
	}			
	-->
	</script>
</head>

<body>

<div class="preloader">
	<div class="lds-ripple">
		<div class="lds-pos"></div>
		<div class="lds-pos"></div>
	</div>
</div>
<form name="form1" id="form1" method="post">

	<input name="accion" type="hidden" id="accion">

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
						<li class="mr-4">
							<label for="server_name" style="margin: 5px 0 0 0;">Dominio</label>
							<select id="server_name" name="server_name" onchange="aplicar()" class="custom-select" data-live-search="true" title="Selecciona Dominio" data-width="400px" >
								<option value="" <?php if ($server_name=="") { echo "selected";} ?>>Todos los Dominios</option>
								<?php
								$sql = "SELECT server_name FROM analytics GROUP BY server_name ORDER by server_name";
								$rs = $mysqli->query($sql); 
								while ($fila = $rs->fetch_array(MYSQLI_ASSOC)){
									$w_server_name = $fila['server_name'];			
									?><option value="<?php echo $w_server_name;?>" <?php if ($server_name==$w_server_name) { echo "selected";} ?>><?php echo $w_server_name;?></option><?php
								}				
								?>
							</select>				
						</li>										  

						<li class="mr-2 text-center">
							<label for="fechaInicio" style="margin: 5px 0 0 0;">Desde</label>
							<input type="text" id="fechaInicio" name="fechaInicio" class="form-control datepicker" style="width: 105px;" value="<?php echo str_replace("-", "/", $fechaInicio) ;?>" placeholder="dd/mm/aaaa" maxlength="10" data-date-end-date="0d"/>
						</li>
						<li class="mr-2 text-center">
							<label for="fechaFin" style="margin: 5px 0 0 0;">Hasta</label>
							<input type="text" id="fechaFin" name="fechaFin" class="form-control datepicker" style="width: 105px;" value="<?php echo str_replace("-", "/", $fechaFin) ;?>" placeholder="dd/mm/aaaa" maxlength="10" data-date-end-date="0d"/>
						</li>	
						<li class="mr-5">
							<div style="margin: 5px 0 0 0;">&nbsp;</div>
							<button type="button" class="btn btn-primary" name="filtrar" onClick="aplicar()">Aplicar</button>
						</li>						
						<li class="mr-3">
							<div for="rango_fechas" style="margin: 5px 0 0 0;">Rango</div>
							<select id="rango_fechas" name="rango_fechas" onchange="filtro()" class="custom-select" data-live-search="true" title="Selecciona fechas" style="width: 150px;">
								<option value="99" <?php if ($rango_fechas==99) { echo "selected";} ?>>Personalizado</option>
								<option value="0" <?php if ($rango_fechas==0) { echo "selected";} ?>>Últimos 7 días</option>
								<option value="1" <?php if ($rango_fechas==1) { echo "selected";} ?> >Mes actual</option>
								<option value="2" <?php if ($rango_fechas==2) { echo "selected";} ?> >Mes anterior</option>
								<option value="3" <?php if ($rango_fechas==3) { echo "selected";} ?> >Año actual</option>
								<option value="4" <?php if ($rango_fechas==4) { echo "selected";} ?> >Año anterior</option>
							</select>				
						</li>												
					</ul>					
				</div>
			</nav>
		</header>

		<?php include('includes/menu.php');		?>
        <div class="page-wrapper">
			<div class="page-breadcrumb">
                <div class="row">
                    <div class="col">
                        <h4 class="page-title mt-2"><?php echo "Estadísticas de actividad"; //echo "<br>$fechaInicio a " . $fechaFin;?></h4>
                    </div>
                </div>
            </div>

            <div class="container-fluid">

			<!--------- ********************************** -------->


<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-body">
				<h4 class="card-title">Páginas vistas</h4>
				<div id="stacked-line" style="height:300px;"></div>
			</div>
		</div>	
	</div>

	<?php

		$english= array('jan','feb','mar','apr','may','jun','jul','aug','sep','oct','nov','dec');
		$esp	= array('ene','feb','mar','abr','may','jun','jul','ago','sep','oct','nov','dic');

		$str_fechaInicio = strtotime($fechaInicio);
		$str_fechaFin = strtotime($fechaFin);

		$data_fechas = ""; $data_paginas_vistas = "";
		$total_pagina_vistas = 0;
		for($i=$str_fechaInicio; $i<=$str_fechaFin; $i+=86400){
			$f = date("Y-m-d", $i);
			$paginas_vistas = 0;
			$sql = "SELECT * FROM analytics WHERE DATE(fecha)='$f'";
            if ($server_name!="") { $sql .= " AND server_name='$server_name'"; }

			$rs = $mysqli->query($sql); 
			$paginas_vistas = $rs->num_rows;
			$fecha = date("d-m-Y", $i);	

			$fecha = strtolower(date("d M", $i));
			$fecha = str_replace($english, $esp, $fecha);	

			$data_fechas .= "'" . $fecha . "',";
			$data_paginas_vistas .= "'" . $paginas_vistas . "',";

			$total_pagina_vistas += $paginas_vistas;
		}
		$data_fechas = substr($data_fechas, 0, -1);
		$data_paginas_vistas = substr($data_paginas_vistas, 0, -1);
		?>		
		<script>
		$(function() {

			var stackedChart = echarts.init(document.getElementById('stacked-line'));
			var option = {		
				grid:		{ left: '1%', right: '2%', bottom: '50', containLabel: true },
				legend:		{ show: false },
				tooltip:	{ trigger: 'axis' },
				calculable: true,         
				color: ['#5c90a8', '#52c79f', '#c65880', '#e6a735', '#895e8b', '#72c9da', '#b61f24', '#25927d'],
				xAxis: [	{ boundaryGap: false, data: [<?php echo $data_fechas;?>] } ],
				yAxis: [	{ splitLine: { show: false} }, { type: 'value',  max: 100, splitLine: { show: false}} ],
				series: [	{ name: 'Páginas vistas',	type: 'line', areaStyle: { color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [{ offset: 0, color: 'rgba(5,141,199,0.1)'}, {offset: 1, color: 'rgba(5,141,199,0.3)'}]) },  data: [<?php echo $data_paginas_vistas;?>] } ]
			};
			stackedChart.setOption(option);

			$(function () {
				$(window).on('resize', resize);
				$(".sidebartoggler").on('click', resize);
				function resize() {
					setTimeout(function() {
						stackedChart.resize();
					}, 200);
				}
			});
		});
		</script>	
</div>

<div class="row">
	<div class="col-md-4">
		<div class="card">
			<div class="card-body">
				<h4 class="card-title">Dispositivos</h4>
				<div id="pie-dispositivos" style="height:300px;"></div>
			</div>
		</div>	
	</div>
	<div class="col-md-4">
		<div class="card">
			<div class="card-body">
				<h4 class="card-title">Plataformas</h4>
				<div id="pie-pataformas" style="height:300px;"></div>
			</div>
		</div>		
	</div>
	<div class="col-md-4">
		<div class="card">
			<div class="card-body">
				<h4 class="card-title">Navegadores</h4>
				<div id="pie-navegadores" style="height:300px;"></div>
			</div>
		</div>		
	</div>
</div>

<div class="row">
	<div class="col-md-6">

		<div class="card">
			<div class="card-body">
				<h4 class="card-title">Páginas vistas</h4>		
				<?php
				$sql = "SELECT COUNT(usuario) as cuantas FROM analytics WHERE (DATE(fecha)>='" . date("Y-m-d", $str_fechaInicio) . "' AND DATE(fecha)<='" . date("Y-m-d", $str_fechaFin) . "')";
				if ($server_name!="") { $sql .= " AND server_name='$server_name'"; }
				$sql .= " GROUP BY usuario";
				$rs = $mysqli->query($sql);
				$usuarios = $rs->num_rows;

				$sql = "SELECT COUNT(session_id) as cuantas FROM analytics WHERE (DATE(fecha)>='" . date("Y-m-d", $str_fechaInicio) . "' AND DATE(fecha)<='" . date("Y-m-d", $str_fechaFin) . "')";
				if ($server_name!="") { $sql .= " AND server_name='$server_name'"; }
				$sql .= " GROUP BY session_id";
				$rs = $mysqli->query($sql);
				$sesiones = $rs->num_rows;

				$sql = "SELECT COUNT(url) as cuantas, session_id FROM analytics WHERE (DATE(fecha)>='" . date("Y-m-d", $str_fechaInicio) . "' AND DATE(fecha)<='" . date("Y-m-d", $str_fechaFin) . "')";
				if ($server_name!="") { $sql .= " AND server_name='$server_name'"; }
				$sql .= " GROUP BY session_id HAVING cuantas=1";
				$rs = $mysqli->query($sql);
				$rebotes = $rs->num_rows;

				$tiempo=0;
				$sql = "SELECT SUM(tiempo) as tiempo_sesiones FROM analytics WHERE (DATE(fecha)>='" . date("Y-m-d", $str_fechaInicio) . "' AND DATE(fecha)<='" . date("Y-m-d", $str_fechaFin) . "')";
				if ($server_name!="") { $sql .= " AND server_name='$server_name'"; }
				$rs = $mysqli->query($sql);

				if ($rs->num_rows > 0)
				{
					$fila = $rs->fetch_assoc();
					$tiempo_sesiones = $fila['tiempo_sesiones'];
					$segundos = round($tiempo_sesiones / $sesiones);

					$horas = floor($segundos/ 3600);
					$minutos = floor(($segundos - ($horas * 3600)) / 60);
					$segundos = $segundos - ($horas * 3600) - ($minutos * 60);				
					$tiempo = substr(str_repeat(0, 1).$horas, - 2) . ':' . substr(str_repeat(0, 1).$minutos, - 2) . ":" . substr(str_repeat(0, 1).$segundos, - 2);
				}

				if ($sesiones>0) { $pagina_sesion = $total_pagina_vistas/$sesiones; }
				if ($sesiones>0) { $rebote = ($rebotes/$sesiones) * 100; }

				$usuarios = number_format($usuarios, 0, ',', '.');
				$sesiones = number_format($sesiones, 0, ',', '.');
				$total_pagina_vistas = number_format($total_pagina_vistas, 0, ',', '.');
				$pagina_sesion = number_format($pagina_sesion, 2, ',', '.');
				$rebote = number_format($rebote, 2, ',', '.');
				?>
				<table class="table table-striped">
					<thead class="grey lighten-2">
						<tr scope="row">
							<th width="100" align="right">Usuarios</th>
							<th width="100" align="right">Sesiones</th>
							<th width="100" align="right">Pag vistas</th>
							<th width="100" align="right">Pag sesión</th>
							<th width="100" align="right">Rebote</th>
							<!-- <th width="100" align="right">Tiempo sesión</th> -->
						</tr>
					</thead>
					<tbody>
					<tr scope="row">
							<td width="100" align="right"><?php echo $usuarios;?></td>
							<td width="100" align="right"><?php echo $sesiones;?></td>
							<td width="100" align="right"><?php echo $total_pagina_vistas;?></td>
							<td width="100" align="right"><?php echo $pagina_sesion;?></td>
							<td width="100" align="right"><?php echo $rebote;?>%</td>
							<!-- <td width="100" align="right"><?php echo $tiempo;?></td>	-->					
						</tr>					
					</tbody>
				</table>	
			</div>			
		</div>


		<div class="card">
			<div class="card-body">
				<h4 class="card-title">Páginas vistas</h4>
				<?php

					echo '<table class="table table-striped">';
					echo '<thead class="grey lighten-2">';
						echo '<tr scope="row">';	
							echo '<th>Página</th>';
							echo '<th width="100" align="right">Vistas</th>';	
						echo '</tr>';
					echo '</thead>';
					echo '<tbody>';

					//$sql = "SELECT COUNT(url) as visitas, id, fecha, session_id, user_id, ip, referrer, title, url, navegador, version, plataforma, dispositivo FROM analytics GROUP BY url ORDER by visitas DESC LIMIT 10";
					$sql = "SELECT COUNT(title) as visitas, id, fecha, session_id, user_id, ip, referrer, title, url, navegador, version, plataforma, dispositivo FROM analytics ";
					$sql.= " WHERE (DATE(fecha)>='" . date("Y-m-d", $str_fechaInicio) . "' AND DATE(fecha)<='" . date("Y-m-d", $str_fechaFin) . "')";
					if ($server_name!="") { $sql .= " AND server_name='$server_name'"; }
					$sql.= " GROUP BY title ORDER by visitas DESC";
					$rs = $mysqli->query($sql); 
					$contador = 1;
					while ($fila = $rs->fetch_array(MYSQLI_ASSOC)){
						$id = $fila['id'];				
						$fecha = $fila['fecha'];		
						$session_id = $fila['session_id'];	
						$user_id = $fila['user_id'];
						$ip = $fila['ip'];	
						$referrer = $fila['referrer'];
						$title = $fila['title'];
						$url = $fila['url'];
						$navegador = $fila['navegador'];
						$version = $fila['version'];
						$plataforma = $fila['plataforma'];
						$dispositivo = $fila['dispositivo'];	

						$visitas = $fila['visitas'];		
						
						$url = str_replace ($dominio, "/", $url);

						if ($contador>10) { $class="slide"; }
						echo '<tr class="' . $class . '">';	
							echo "<td>$title</td><td align='right'>$visitas</td>";
						echo '</tr>';	
						$contador++;	
					}	

                    if ($contador>10) {
                        echo '<tr class="tr_ver_mas">';
                        echo '<td colspan="2" align="center"><a href="#" class="btn btn-primary ver_mas">Ver todas</a></td>';
                        echo '</tr>';
                    }							
					echo '</tbody>';
					echo '</table>';
				?>
			</div>
		</div>

		<div class="card">
			<div class="card-body">
				<h4 class="card-title">Denuncias por CCAA</h4>
				<div id="pie-denuncias" style="height:300px;"></div>

<?php

			echo '<table class="table table-striped">';
			echo '<thead class="grey lighten-2">';
				echo '<tr scope="row">';	
					echo '<th>CCAA</th>';
					echo '<th width="100" align="right">Denuncias</th>';	
				echo '</tr>';
			echo '</thead>';
			echo '<tbody>';

		$sql = "SELECT COUNT(id_comunidad_autonoma) as cuantos, id_comunidad_autonoma FROM punto_venta_denunciado WHERE (DATE(fecha_alta)>='" . date("Y-m-d", $str_fechaInicio) . "' AND DATE(fecha_alta)<='" . date("Y-m-d", $str_fechaFin) . "')";
		$sql .= " GROUP BY id_comunidad_autonoma ORDER BY 1 DESC";
		$rs = $mysqli->query($sql); 
        while ($fila = $rs->fetch_array(MYSQLI_ASSOC)) {
			$cuantos = $fila['cuantos'];
			$id_comunidad_autonoma = $fila['id_comunidad_autonoma'];	

			$ccaa = "";
			$sql2 = "SELECT * FROM comunidades WHERE id=$id_comunidad_autonoma"; 
			$rs2 = $mysqli->query($sql2); 
			if ($rs2->num_rows > 0) {
				$fila2 = $rs2->fetch_assoc();
				$ccaa = $fila2['nombre'];
			}		

			echo '<tr>';	
				echo "<td>$ccaa</td><td align='right'>$cuantos</td>";
			echo '</tr>';


        }
		echo '</tbody>';
		echo '</table>';
?>




			</div>
		</div>

	</div>

	<div class="col-md-6">	

		<div class="card">
			<div class="card-body">
				<h4 class="card-title">Adquisición</h4>		
				<?php
					$data_fuentes = "";
					$data_fuentes_value = "";
					$sql = "SELECT COUNT(fuente) as cuantos, fuente FROM analytics WHERE fuente<>'' AND (DATE(fecha)>='" . date("Y-m-d", $str_fechaInicio) . "' AND DATE(fecha)<='" . date("Y-m-d", $str_fechaFin) . "')";
					if ($server_name!="") { $sql .= " AND server_name='$server_name'"; }
					$sql .= " GROUP BY fuente";

					//echo "$sql<br>";
					$rs = $mysqli->query($sql); 
					$total_fuentes = 0;
					$search = 0; $directo = 0; $referral = 0; $social = 0;
					while ($fila = $rs->fetch_array(MYSQLI_ASSOC)) {
						$cuantos = $fila['cuantos'];	
						$total_fuentes += $cuantos;
						
						$fuente = trim($fila['fuente']);
						if ($fuente=="Organic")		{ $search += $cuantos; }
						elseif ($fuente=="Direct")	{ $directo += $cuantos; }
						elseif ($fuente=="Referral"){ $referral += $cuantos; }						
						elseif ($fuente=="Social")	{ $social += $cuantos; }						

						$data_fuentes .= "'" . $fuente . "',";
						$data_fuentes_value .= "{value: " .  $cuantos . ", name: '" . $fuente . "'},";
					}
					$data_fuentes = substr($data_fuentes, 0, -1);
					$data_fuentes_value = substr($data_fuentes_value, 0, -1);


					$data_fuentes_social = "";
					$data_fuentes_social_value = "";
					$sql = "SELECT COUNT(tipo_fuente) as cuantos, tipo_fuente FROM analytics WHERE fuente='Social' AND (DATE(fecha)>='" . date("Y-m-d", $str_fechaInicio) . "' AND DATE(fecha)<='" . date("Y-m-d", $str_fechaFin) . "')";
					if ($server_name!="") { $sql .= " AND server_name='$server_name'"; }
					$sql .= " GROUP BY tipo_fuente";
					$rs = $mysqli->query($sql); 

					while ($fila = $rs->fetch_array(MYSQLI_ASSOC)) {
						$cuantos = $fila['cuantos'];						
						$tipo_fuente = $fila['tipo_fuente'];	

						$data_fuentes_social .= "'" . $tipo_fuente . "',";
						$data_fuentes_social_value .= "{value: " .  $cuantos . ", name: '" . $tipo_fuente . "'},";
					}
					$data_fuentes_social = substr($data_fuentes_social, 0, -1);
					$data_fuentes_social_value = substr($data_fuentes_social_value, 0, -1);					


				?>
				<table class="table table-striped">
					<thead class="grey lighten-2">
						<tr scope="row">
							<th width="100" align="right">Organic Search</th>
							<th width="100" align="right">Direct</th>
							<th width="100" align="right">Referral</th>
							<th width="100" align="right">Social</th>
							<th width="100" align="right">Total</th>					
						</tr>
					</thead>
					<tbody>
					<tr scope="row">
							<td width="100" align="right"><?php echo number_format($search, 0, ',', '.');?></td>					
							<td width="100" align="right"><?php echo number_format($directo, 0, ',', '.');?></td>					
							<td width="100" align="right"><?php echo number_format($referral, 0, ',', '.');?></td>					
							<td width="100" align="right"><?php echo number_format($social, 0, ',', '.');?></td>					
							<td width="100" align="right"><?php echo number_format($total_fuentes, 0, ',', '.');?></td>					
						</tr>					
					</tbody>
				</table>	
			</div>			
		</div>

		<div class="row">
			<div class="col-md-6">
				<div class="card">
					<div class="card-body">
						<h4 class="card-title">Adquisición</h4>
						<div id="pie-fuentes" style="height:300px;"></div>
					</div>
				</div>			
			</div>
			<div class="col-md-6">
				<div class="card">
					<div class="card-body">
						<h4 class="card-title">Adquisición Social</h4>
						<div id="pie-fuentes-social" style="height:300px;"></div>
					</div>
				</div>			
			</div>
		</div>	

		<div class="card">
			<div class="card-body">
				<h4 class="card-title">Referral</h4>
				
				<table class="table table-striped">
					<thead class="grey lighten-2">
						<tr scope="row">
							<th>URL</th>
							<th width="100" align="right">Cantidad</th>					
						</tr>
					</thead>
					<tbody>				
					<?php
						$sql = "SELECT referrer, COUNT(*) as cantidad FROM analytics WHERE fuente='Referral' AND (DATE(fecha)>='" . date("Y-m-d", $str_fechaInicio) . "' AND DATE(fecha)<='" . date("Y-m-d", $str_fechaFin) . "')";
						if ($server_name!="") { $sql .= " AND server_name='$server_name'"; }
						$sql .= " GROUP BY referrer ORDER by 2 DESC";
						$rs = $mysqli->query($sql); 
						$contador = 1;
						while ($fila = $rs->fetch_array(MYSQLI_ASSOC)) {
							$cantidad ++;
							$referrer = $fila['referrer'];
							$num_caracteres = strlen($referrer);							
							$referrer = substr($referrer, 0, 50);
							if ($num_caracteres > 50 ) { $referrer = $referrer . " ..."; }

							$cantidad = $fila['cantidad'];

							$class="";							
							if ($contador>10) { $class="slide2"; }
							?>
							<tr class="<?php echo $class; ?>">
								<td><?php echo $referrer; ?></td>					
								<td align="right"><?php echo number_format($cantidad, 0, ',', '.'); ?></td>									
							</tr>
							<?php
							$contador ++;
						}	
						if ($contador>10) {
							echo '<tr class="tr_ver_mas2">';
							echo '<td colspan="2" align="center"><a href="#" class="btn btn-primary ver_mas2">Ver todas</a></td>';
							echo '</tr>';
						}
					?>
					</tbody>
				</table>							

			</div>
		</div>			


		

	</div>
</div>
	<?php

		$data_dispositivos = "";
		$data_dispositivos_value = "";
		$sql = "SELECT COUNT(dispositivo) as cuantos, dispositivo FROM analytics WHERE (DATE(fecha)>='" . date("Y-m-d", $str_fechaInicio) . "' AND DATE(fecha)<='" . date("Y-m-d", $str_fechaFin) . "')";
		if ($server_name!="") { $sql .= " AND server_name='$server_name'"; }
		$sql .= " GROUP BY dispositivo";
		$rs = $mysqli->query($sql); 
        while ($fila = $rs->fetch_array(MYSQLI_ASSOC)) {
			$cuantos = $fila['cuantos'];	
			$dispositivo = $fila['dispositivo'];	
			$data_dispositivos .= "'" . $dispositivo . "',";
			$data_dispositivos_value .= "{value: " .  $cuantos . ", name: '" . $dispositivo . "'},";
        }
		$data_dispositivos = substr($data_dispositivos, 0, -1);
		$data_dispositivos_value = substr($data_dispositivos_value, 0, -1);


		$data_plataforma = "";
		$data_plataforma_value = "";
		$sql = "SELECT COUNT(plataforma) as cuantos, plataforma FROM analytics WHERE (DATE(fecha)>='" . date("Y-m-d", $str_fechaInicio) . "' AND DATE(fecha)<='" . date("Y-m-d", $str_fechaFin) . "')";
		if ($server_name!="") { $sql .= " AND server_name='$server_name'"; }
		$sql .= " GROUP BY plataforma";
		$rs = $mysqli->query($sql); 
        while ($fila = $rs->fetch_array(MYSQLI_ASSOC)) {
			$cuantos = $fila['cuantos'];	
			$plataforma = $fila['plataforma'];		

			$data_plataforma .= "'" . $plataforma . "',";
			$data_plataforma_value .= "{value: " .  $cuantos . ", name: '" . $plataforma . "'},";
        }
		$data_plataforma = substr($data_plataforma, 0, -1);
		$data_plataforma_value = substr($data_plataforma_value, 0, -1);

		$data_navegador = "";
		$data_navegador_value = "";
		$sql = "SELECT COUNT(navegador) as cuantos, navegador FROM analytics WHERE (DATE(fecha)>='" . date("Y-m-d", $str_fechaInicio) . "' AND DATE(fecha)<='" . date("Y-m-d", $str_fechaFin) . "')";
		if ($server_name!="") { $sql .= " AND server_name='$server_name'"; }
		$sql .= " GROUP BY navegador";
		$rs = $mysqli->query($sql); 
        while ($fila = $rs->fetch_array(MYSQLI_ASSOC)) {
			$cuantos = $fila['cuantos'];	
			$navegador = $fila['navegador'];		

			$data_navegador .= "'" . $navegador . "',";
			$data_navegador_value .= "{value: " .  $cuantos . ", name: '" . $navegador . "'},";
        }
		$data_navegador = substr($data_navegador, 0, -1);
		$data_navegador_value = substr($data_navegador_value, 0, -1);		


		$data_denuncias = "";
		$data_denuncias_value = "";
		$sql = "SELECT COUNT(id_comunidad_autonoma) as cuantos, id_comunidad_autonoma FROM punto_venta_denunciado WHERE (DATE(fecha_alta)>='" . date("Y-m-d", $str_fechaInicio) . "' AND DATE(fecha_alta)<='" . date("Y-m-d", $str_fechaFin) . "')";
		$sql .= " GROUP BY id_comunidad_autonoma ORDER BY 1 DESC";
		$rs = $mysqli->query($sql); 
        while ($fila = $rs->fetch_array(MYSQLI_ASSOC)) {
			$cuantos = $fila['cuantos'];
			$id_comunidad_autonoma = $fila['id_comunidad_autonoma'];	

			$ccaa = "";
			$sql2 = "SELECT * FROM comunidades WHERE id=$id_comunidad_autonoma"; 
			$rs2 = $mysqli->query($sql2); 
			if ($rs2->num_rows > 0) {
				$fila2 = $rs2->fetch_assoc();
				$ccaa = $fila2['nombre'];
			}		

			$data_denuncias .= "'" . $ccaa . "',";
			$data_denuncias_value .= "{value: " .  $cuantos . ", name: '" . $ccaa . "'},";
        }
		$data_denuncias = substr($data_denuncias, 0, -1);
		$data_denuncias_value = substr($data_denuncias_value, 0, -1);


	?>
		
	
	<script>
	$(function() {

		$('.ver_mas').on('click', function (e) {
			e.preventDefault();		
			$( ".slide" ).slideDown( "slow", function() {
				// Animation complete.
			});
			$('.tr_ver_mas').hide();
			return false;
		})

		$('.ver_mas2').on('click', function (e) {
			e.preventDefault();		
			$( ".slide2" ).slideDown( "slow", function() {
				// Animation complete.
			});
			$('.tr_ver_mas2').hide();
			return false;
		})		

		var Chart_dispositivos = echarts.init(document.getElementById('pie-dispositivos'));
		option = {
			tooltip: {
				trigger: 'item',
				formatter: '{a} <br/>{b} : {c} ({d}%)'
			},
			avoidLabelOverlap: false,
			color: ['#5c90a8', '#52c79f', '#c65880', '#e6a735', '#895e8b', '#72c9da', '#b61f24', '#25927d'],
			legend: {
				orient: 'vertical',
				left: 'left',
				data: [<?php echo $data_dispositivos;?>]
			},			
			series: [
				{
					name: 'Dispositivos',
					type: 'pie',
					radius: ['40%', '70%'],
					avoidLabelOverlap: false,
					itemStyle: {
						borderRadius: 10,
						borderColor: '#fff',
						borderWidth: 2
					},
					center: ['50%', '50%'],
					data: [<?php echo $data_dispositivos_value;?>],
					label: {
						normal: {
							show: false,
							position: 'center'
						},
						emphasis: {
							itemStyle: {
								shadowBlur: 10,
								shadowOffsetX: 0,
								shadowColor: 'rgba(0, 0, 0, 0.5)'
							}
						}
					}			
				}
			]
		};
		Chart_dispositivos.setOption(option);	
		//**************************************************** */

		var Chart_plataformas = echarts.init(document.getElementById('pie-pataformas'));
		option = {
			tooltip: {
				trigger: 'item',
				formatter: '{a} <br/>{b} : {c} ({d}%)'
			},
			avoidLabelOverlap: false,
			color: ['#5c90a8', '#52c79f', '#c65880', '#e6a735', '#895e8b', '#72c9da', '#b61f24', '#25927d'],
			legend: {
				orient: 'vertical',
				left: 'left',
				data: [<?php echo $data_plataforma;?>]
			},			
			series: [
				{
					name: 'Dispositivos',
					type: 'pie',
					radius: ['40%', '70%'],
					avoidLabelOverlap: false,
					itemStyle: {
						borderRadius: 10,
						borderColor: '#fff',
						borderWidth: 2
					},
					center: ['50%', '50%'],
					data: [<?php echo $data_plataforma_value;?>],
					label: {
						normal: {
							show: false,
							position: 'center'
						},
						emphasis: {
							itemStyle: {
								shadowBlur: 10,
								shadowOffsetX: 0,
								shadowColor: 'rgba(0, 0, 0, 0.5)'
							}
						}
					}			
				}
			]
		};
		Chart_plataformas.setOption(option);	

		//**************************************************** */
		var Chart_navegadores = echarts.init(document.getElementById('pie-navegadores'));
		option = {
			tooltip: {
				trigger: 'item',
				formatter: '{a} <br/>{b} : {c} ({d}%)'
			},
			avoidLabelOverlap: false,
			color: ['#5c90a8', '#52c79f', '#c65880', '#e6a735', '#895e8b', '#72c9da', '#b61f24', '#25927d'],
			legend: {
				orient: 'vertical',
				left: 'left',
				data: [<?php echo $data_navegador;?>]
			},			
			series: [
				{
					name: 'Dispositivos',
					type: 'pie',
					radius: ['40%', '70%'],
					avoidLabelOverlap: false,
					itemStyle: {
						borderRadius: 10,
						borderColor: '#fff',
						borderWidth: 2
					},
					center: ['50%', '50%'],
					data: [<?php echo $data_navegador_value;?>],
					label: {
						normal: {
							show: false,
							position: 'center'
						},
						emphasis: {
							itemStyle: {
								shadowBlur: 10,
								shadowOffsetX: 0,
								shadowColor: 'rgba(0, 0, 0, 0.5)'
							}
						}
					}			
				}
			]
		};
		Chart_navegadores.setOption(option);			

	//**************************************************** */
	var Chart_fuentes = echarts.init(document.getElementById('pie-fuentes'));
		option = {
			tooltip: {
				trigger: 'item',
				formatter: '{a} <br/>{b} : {c} ({d}%)'
			},
			avoidLabelOverlap: false,
			color: ['#5c90a8', '#52c79f', '#c65880', '#e6a735', '#895e8b', '#72c9da', '#b61f24', '#25927d'],
			legend: {
				orient: 'vertical',
				left: 'left',
				data: [<?php echo $data_fuentes;?>]
			},			
			series: [
				{
					name: 'Adquisición',
					type: 'pie',
					radius: '70%',
					avoidLabelOverlap: false,
					itemStyle: {
						borderRadius: 10,
						borderColor: '#fff',
						borderWidth: 2
					},
					center: ['50%', '50%'],
					data: [<?php echo $data_fuentes_value;?>],
					label: {
						normal: {
							show: false,
							position: 'center'
						},
						emphasis: {
							itemStyle: {
								shadowBlur: 10,
								shadowOffsetX: 0,
								shadowColor: 'rgba(0, 0, 0, 0.5)'
							}
						}
					}			
				}
			]
		};
		Chart_fuentes.setOption(option);			
		
	//**************************************************** */
	var Chart_fuentes_social = echarts.init(document.getElementById('pie-fuentes-social'));
		option = {
			tooltip: {
				trigger: 'item',
				formatter: '{a} <br/>{b} : {c} ({d}%)'
			},
			avoidLabelOverlap: false,
			color: ['#5c90a8', '#52c79f', '#c65880', '#e6a735', '#895e8b', '#72c9da', '#b61f24', '#25927d'],
			legend: {
				orient: 'vertical',
				left: 'left',
				data: [<?php echo $data_fuentes_social;?>]
			},			
			series: [
				{
					name: 'Adquisición',
					type: 'pie',
					radius: '70%',
					avoidLabelOverlap: false,
					itemStyle: {
						borderRadius: 10,
						borderColor: '#fff',
						borderWidth: 2
					},
					center: ['50%', '50%'],
					data: [<?php echo $data_fuentes_social_value;?>],
					label: {
						normal: {
							show: false,
							position: 'center'
						},
						emphasis: {
							itemStyle: {
								shadowBlur: 10,
								shadowOffsetX: 0,
								shadowColor: 'rgba(0, 0, 0, 0.5)'
							}
						}
					}			
				}
			]
		};
		Chart_fuentes_social.setOption(option);	

	//**************************************************** */
	var Chart_denuncias = echarts.init(document.getElementById('pie-denuncias'));
		option = {
			tooltip: {
				trigger: 'item',
				formatter: '{a} <br/>{b} : {c} ({d}%)'
			},
			avoidLabelOverlap: false,
			color: ['#5c90a8', '#52c79f', '#c65880', '#e6a735', '#895e8b', '#72c9da', '#b61f24', '#25927d'],
			legend: {
				orient: 'vertical',
				left: 'left',
				data: [<?php echo $data_denuncias;?>]
			},			
			series: [
				{
					name: 'Denuncias por CCAA',
					type: 'pie',
					radius: '70%',
					avoidLabelOverlap: false,
					itemStyle: {
						borderRadius: 10,
						borderColor: '#fff',
						borderWidth: 2
					},
					center: ['50%', '50%'],
					data: [<?php echo $data_denuncias_value;?>],
					label: {
						normal: {
							show: false,
							position: 'center'
						},
						emphasis: {
							itemStyle: {
								shadowBlur: 10,
								shadowOffsetX: 0,
								shadowColor: 'rgba(0, 0, 0, 0.5)'
							}
						}
					}			
				}
			]
		};
		Chart_denuncias.setOption(option);			

		$(function () {



				$(window).on('resize', resize);
				$(".sidebartoggler").on('click', resize);
				function resize() {
					setTimeout(function() {
						Chart_dispositivos.resize();
						Chart_plataformas.resize();
						Chart_navegadores.resize();
						Chart_fuentes.resize();
						Chart_fuentes_social.resize();
					}, 200);
				}
			});		
	});
	</script>

	

			<!--------- ********************************** -------->

            </div>

        </div>


	</div><!-- End Wrapper -->
</form>

<script>
	$('.datepicker').datepicker({
		language: 'es',
		locale: 'es-es',
		todayHighlight: true
	});
</script>
</body>

</html>