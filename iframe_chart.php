<?php

/** Is this file a duplicate? Also present in the root directory 
 * (This is root directory version used on homepage) 
 * Another version is in the includes directory.
 **/


header("Expires: Sat, 01 Jan 2000 00:00:00 GMT");
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
header("Cache-Control: post-check=0, pre-check=0",false);
session_cache_limiter("must-revalidate");

session_start();
$path_relative="";
include('includes/conexion.php');


$rango_fechas=0;
$rango_fechas = (isset($_GET['rango_fechas']) ? $_GET['rango_fechas'] : ""); if ($rango_fechas==""){  $rango_fechas = (isset($_POST['rango_fechas']) ? $_POST['rango_fechas'] : ""); }
if(!is_numeric($rango_fechas)) { $rango_fechas=1; }

//$rango_fechas=4;

$fechaFin = date("d-m-Y");
if ($rango_fechas==0)  {
	$rango_fechas=1;
}

if ($rango_fechas==1)  { 	
	$cuantos_dias = date("j") - 1;
	$fechaInicio = date("d-m-Y", strtotime($fechaFin . "- $cuantos_dias days")); 

	$tx_mes = $array_meses[date("n")-1];
	$tx_periodo = mb_strtoupper($tx_mes, 'UTF-8'). ", " . date("Y"); 
}
elseif ($rango_fechas==2)  { 
	$month = date('m', strtotime('-1 month'));
	$year = date('Y', strtotime('-1 month'));	
	$day = date("d", mktime(0,0,0, $month+1, 0, $year));
	$fechaFin = date('d-m-Y', mktime(0,0,0, date('m'), 1, $year));
	$fechaInicio = date('d-m-Y', mktime(0,0,0, $month, 1, $year));

	$tx_mes = $array_meses[date('n', strtotime('-1 month'))-1];
	$tx_periodo = mb_strtoupper($tx_mes, 'UTF-8'). ", " . $year; 

}
elseif ($rango_fechas==3)  { 
	$fechaInicio = date('d-m-Y', mktime(0,0,0, 1, 1, date('Y')));
	$tx_periodo = date('Y'); 
}
elseif ($rango_fechas==4)  {
	$year = date('Y', strtotime('-1 year'));
	$fechaFin = date('d-m-Y', mktime(0,0,0, 1, 1, date('Y')));
	$fechaFin = date("d-m-Y", strtotime($fechaFin."- 1 days")); 
	$fechaInicio = date('d-m-Y', mktime(0,0,0, 1, 1, $year));

	$tx_periodo =$year; 
}
$str_fechaInicio = strtotime($fechaInicio);
$str_fechaFin = strtotime($fechaFin);

$total_denuncias = 0;
$data_denuncias = "";
$data_denuncias_value = "";
$sql = "SELECT COUNT(id_comunidad_autonoma) as cuantos, id_comunidad_autonoma FROM punto_venta_denunciado WHERE (DATE(fecha_alta)>='" . date("Y-m-d", $str_fechaInicio) . "' AND DATE(fecha_alta)<='" . date("Y-m-d", $str_fechaFin) . "')";
$sql .= " AND id_comunidad_autonoma<>0";
$sql .= " GROUP BY id_comunidad_autonoma ORDER BY 1 DESC";
$rs = $mysqli->query($sql); 
while ($fila = $rs->fetch_array(MYSQLI_ASSOC)) {
	$cuantos = $fila['cuantos'];
	$total_denuncias += $cuantos;
	$id_comunidad_autonoma = $fila['id_comunidad_autonoma'];	

	$ccaa = " -- $id_comunidad_autonoma --";
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

$total_denuncias = number_format($total_denuncias, 0, ',', '.');

$data_denuncias_prov = "";
$data_denuncias_prov_value = "";
$sql = "SELECT COUNT(id_provincia) as cuantos, id_provincia FROM punto_venta_denunciado WHERE (DATE(fecha_alta)>='" . date("Y-m-d", $str_fechaInicio) . "' AND DATE(fecha_alta)<='" . date("Y-m-d", $str_fechaFin) . "')";
$sql .= " AND id_provincia<>0";
$sql .= " GROUP BY id_provincia ORDER BY 1 DESC";
$rs = $mysqli->query($sql); 
while ($fila = $rs->fetch_array(MYSQLI_ASSOC)) {
	$cuantos = $fila['cuantos'];
	$id_provincia = $fila['id_provincia'];	

	$prov = "";
	$sql2 = "SELECT * FROM provincias WHERE id=$id_provincia"; 
	$rs2 = $mysqli->query($sql2); 
	if ($rs2->num_rows > 0) {
		$fila2 = $rs2->fetch_assoc();
		$prov = $fila2['nombre'];
	}		

	$data_denuncias_prov .= "'" . $prov . "',";
	$data_denuncias_prov_value .= "{value: " .  $cuantos . ", name: '" . $prov . "'},";
}
$data_denuncias_prov = substr($data_denuncias_prov, 0, -1);
$data_denuncias_prov_value = substr($data_denuncias_prov_value, 0, -1);


$data_denuncias_punto = "";
$data_denuncias_punto_value = "";
$sql = "SELECT COUNT(id_tipo_punto_de_venta) as cuantos, id_tipo_punto_de_venta FROM punto_venta_denunciado WHERE (DATE(fecha_alta)>='" . date("Y-m-d", $str_fechaInicio) . "' AND DATE(fecha_alta)<='" . date("Y-m-d", $str_fechaFin) . "')";
$sql .= " AND id_tipo_punto_de_venta<>0";
$sql .= " GROUP BY id_tipo_punto_de_venta ORDER BY 1 DESC";
$rs = $mysqli->query($sql); 
while ($fila = $rs->fetch_array(MYSQLI_ASSOC)) {
	$cuantos = $fila['cuantos'];
	$id_tipo_punto_de_venta = $fila['id_tipo_punto_de_venta'];	

	$tipos_punto_de_venta = "";
	$sql2 = "SELECT * FROM tipos_punto_de_venta WHERE id=$id_tipo_punto_de_venta"; 
	$rs2 = $mysqli->query($sql2); 
	if ($rs2->num_rows > 0) {
		$fila2 = $rs2->fetch_assoc();
		$tipos_punto_de_venta = $fila2['nombre'];
	}		

	$data_denuncias_punto .= "'" . $tipos_punto_de_venta . "',";
	$data_denuncias_punto_value .= "{value: " .  $cuantos . ", name: '" . $tipos_punto_de_venta . "'},";
}
$data_denuncias_punto = substr($data_denuncias_punto, 0, -1);
$data_denuncias_punto_value = substr($data_denuncias_punto_value, 0, -1);
?>

<!doctype html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<meta name="title" content="Denuncias recibidas" />

    <title>Denuncias recibidas</title>

 <!-- our project just needs Font Awesome Solid + Brands -->
 <link href="<?php echo $dominio;?>/css/fa/fontawesome.css" rel="stylesheet" />
<link href="<?php echo $dominio;?>/css/fa/brands.css" rel="stylesheet" />
<link href="<?php echo $dominio;?>/css/fa/solid.css" rel="stylesheet" />
<link href="<?php echo $dominio;?>/css/bs/css/bootstrap.min.css" rel="stylesheet" />

<link href="<?php echo $dominio;?>css/custom.css?v=20240926" rel="stylesheet" type="text/css">




	<script>dominio = "<?php echo $dominio;?>"; </script>

  <script src="<?php echo $dominio;?>js/jquery-3.6.0.min.js"></script>	
<script src="<?php echo $dominio;?>css/bs/js/bootstrap.min.js"></script>	

	<script src="<?php echo $dominio;?>js/echarts-en.min.js"></script>
	
	<script>
		function filtro() { 
			document.form1.submit();
		}
	</script>

  </head>
<body style="padding-top:0px;">

<div class="bg-gris2 mt-5 mb-5">
	<div class="container">	
	<form name="form1" id="form1" method="post">
		<div class="row">
			<div class="col-md-3 pt-4">
				<h2 class="tit_denuncias mb-4">Denuncias recibidas</h2>

				<select id="rango_fechas" name="rango_fechas" onchange="filtro()" class="custom-select" data-live-search="true" title="Selecciona fechas">				
					<option value="1" <?php if ($rango_fechas==1) { echo "selected";} ?> >Mes en curso</option>
					<option value="2" <?php if ($rango_fechas==2) { echo "selected";} ?> >Mes anterior</option>
					<option value="3" <?php if ($rango_fechas==3) { echo "selected";} ?> >Año en curso</option>
					<option value="4" <?php if ($rango_fechas==4) { echo "selected";} ?> >Año anterior</option>
				</select>
				<div class="bg-red tx-blanco mt-4 p-3 text-center">		
					<p class="num_denuncias"><?php echo $total_denuncias;?></p>
					<p class="tot_denuncias">TOTAL <?php echo $tx_periodo;?></p>
				</div>
			</div>
			<div class="col-md-9 py-4">				
				<div class="bg-blanco box_shadow">
					<div class="p-3 text-center">
						<a href="#" class="btn btn-map btn-map-active" data-type="1">CCAA</a>
						<a href="#" class="btn btn-map" data-type="2">PROVINCIA</a>
						<a href="#" class="btn btn-map" data-type="3">PUNTO DE VENTA</a>
					</div>

					<div class="echart-div">
						<div id="denuncias" class="echart"></div>
					</div>
				</div>		
			</div>			
		</div>
	</div>
	</form>
</div>


<script>

/**
 * Color list is applied in 3 places. 
 * Use HSL to generate a list of colors with different lightness values.
 * Base color is hsl(24, 20%, 25%) - #4b3c32
 **/ 

  var COLOR_LIST = [];

  for (var i = 0; i < 26; i++) {
    let lumin = 25 + i * 5;
    if (lumin > 80) lumin = 80;
    COLOR_LIST.push('hsl(24, 20%, ' + lumin + '%)');
  }


		$(function() {	
			
			var option_1 = {		
				grid:		{ left: '2%', right: '2%', bottom: '50', containLabel: true },
				legend:		{ show: false },
				tooltip: {
					trigger: 'item',
					useHTML: true,
					formatter: function(params) {
					  return `<table><tr><td style="text-align: center;">${params.name}</td></tr><tr><td style="text-align: center;">${params.data.value}</td></tr></table>`;
					},
					style: {
					  textAlign: 'center'
					}
				 },
				calculable: true,         
				xAxis: { type: 'category',    data: [<?php echo $data_denuncias;?>] , axisLabel: { interval: 0, rotate: 30 }  },
				yAxis: { type: 'value'  },				
				series:
				[	
					{
						name: 'CCAA',
						type: 'bar',
						data: [<?php echo $data_denuncias_value;?>],		
						barWidth: '90%',				
						itemStyle: {
							normal: {
								color: function (params) {
									var colorList = COLOR_LIST;
									return colorList[params.dataIndex];
								}
							}
						}						
					}
				]
			};
			var denuncias = echarts.init(document.getElementById('denuncias'));
			denuncias.setOption(option_1);


			var option_2 = {		
				grid:		{ left: '2%', right: '2%', bottom: '50', containLabel: true },
				legend:		{ show: false },
				tooltip: {
					trigger: 'item',
					useHTML: true,
					formatter: function(params) {
					  return `<table><tr><td style="text-align: center;">${params.name}</td></tr><tr><td style="text-align: center;">${params.data.value}</td></tr></table>`;
					},
					style: {
					  textAlign: 'center'
					}
				 },
				calculable: true,         
				xAxis: { type: 'category',    data: [<?php echo $data_denuncias_prov;?>] , axisLabel: { interval: 0, rotate: 60 }  },
				yAxis: { type: 'value'  },				
				series:
				[	
					{
						name: 'PROVINCIA',
						type: 'bar',
						data: [<?php echo $data_denuncias_prov_value;?>],	
						barWidth: '90%',				
						itemStyle: {
							normal: {
								color: function (params) {
									var colorList = COLOR_LIST;
									return colorList[params.dataIndex];
								}
							}
						}						
					}
				]
			};

			var option_3 = {		
				grid:		{ left: '2%', right: '2%', bottom: '50', containLabel: true },
				legend:		{ show: false },
				tooltip: {
					trigger: 'item',
					useHTML: true,
					formatter: function(params) {
					  return `<table><tr><td style="text-align: center;">${params.name}</td></tr><tr><td style="text-align: center;">${params.data.value}</td></tr></table>`;
					},
					style: {
					  textAlign: 'center'
					}
				 },
				calculable: true,         
				xAxis: { type: 'category',    data: [<?php echo $data_denuncias_punto;?>] , axisLabel: { interval: 0, rotate: 60 }  },
				yAxis: { type: 'value'  },				
				series:
				[	
					{
						name: 'PUNTO DE VENTA',
						type: 'bar',
						data: [<?php echo $data_denuncias_punto_value;?>],	
						barWidth: '90%',				
						itemStyle: {
							normal: {
								color: function (params) {
									var colorList = COLOR_LIST;
									return colorList[params.dataIndex];
								}
							}
						}						
					}
				]
			};
	



			$('.btn-map').on('click', function(){	

				$('.btn-map').removeClass("btn-map-active");
				$(this).addClass("btn-map-active");

				target = $(this).attr("data-type");

				if (target==1) { denuncias.setOption(option_1); }
				else if (target==2) { denuncias.setOption(option_2); }
				else if (target==3) { denuncias.setOption(option_3); }



				//$("#"+target).addClass("echart-active");
				//$("#"+target).css("display", "block");

				return false;
			}); 


			

			$(function () {
				$(window).on('resize', resize);
				$(".sidebartoggler").on('click', resize);
				function resize() {
					setTimeout(function() {
						denuncias_ccaa.resize();
						denuncias_prov.resize();
						denuncias_punto.resize();
					}, 200);
				}
			});
		});
		</script>	

</body>
</html>		