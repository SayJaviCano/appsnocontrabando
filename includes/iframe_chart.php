<?php

/** Is this file a duplicate? Also present in the root directory (
 * (Root directory version used on homepage) 
 **/

$rango_fechas=0;
$rango_fechas = (isset($_GET['rango_fechas']) ? $_GET['rango_fechas'] : ""); if ($rango_fechas==""){  $rango_fechas = (isset($_POST['rango_fechas']) ? $_POST['rango_fechas'] : ""); }
if(!is_numeric($rango_fechas)) { $rango_fechas=0; }


$rango_fechas=4;

$fechaFin = date("d-m-Y");
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

<div class="bg-gris2 mt-5 mb-5">
	<div class="container">	
		<div class="row">
			<div class="col-md-3 py-4">
				<h2 class="tit_denuncias mb-4">Denuncias recibidas</h2>

				<select id="rango_fechas" name="rango_fechas" onchange="filtro()" class="custom-select" data-live-search="true" title="Selecciona fechas">				
					<option value="0" <?php if ($rango_fechas==0) { echo "selected";} ?>>Últimos 7 días</option>
					<option value="1" <?php if ($rango_fechas==1) { echo "selected";} ?> >Mes actual</option>
					<option value="2" <?php if ($rango_fechas==2) { echo "selected";} ?> >Mes anterior</option>
					<option value="3" <?php if ($rango_fechas==3) { echo "selected";} ?> >Año actual</option>
					<option value="4" <?php if ($rango_fechas==4) { echo "selected";} ?> >Año anterior</option>
				</select>
				<div class="bg-red tx-blanco mt-4 p-3 text-center">		
					<p class="num_denuncias"><?php echo $total_denuncias;?></p>
					<p>TOTAL PERIODO</p>
				</div>
			</div>
			<div class="col-md-9 py-4">				
				<div class="bg-blanco">
					<div class="p-3 text-center">
						<a href="#" class="btn btn-primary btn-map" data-type="denuncias_ccaa">CCAA</a>
						<a href="#" class="btn btn-secondary btn-map" data-type="denuncias_prov">PROVINCIA</a>
						<a href="#" class="btn btn-secondary btn-map" data-type="denuncias_punto">PUNTO DE VENTA</a>
					</div>

					<div class="echart-div">
						<div id="denuncias_ccaa" class="echart"></div>

						<div id="denuncias_prov" class="echart"></div>

						<div id="denuncias_punto" class="echart"></div>
					</div>
				</div>		
			</div>			
		</div>
	</div>
</div>
<script src="<?php echo $dominio;?>/js/echarts-en.min.js"></script>
<script src="<?php echo $dominio;?>/js/echarts-spain.js"></script>

<script>
		$(function() {	
			
			var option = {		
				grid:		{ left: '2%', right: '2%', bottom: '50', containLabel: true },
				legend:		{ show: false },
				tooltip:	{ trigger: 'axis' },
				calculable: true,         
				xAxis: { type: 'category',    data: [<?php echo $data_denuncias;?>] , axisLabel: { interval: 0, rotate: 30 }  },
				yAxis: { type: 'value'  },				
				series:
				[	
					{
						name: 'CCAA',
						type: 'bar',
						data: [<?php echo $data_denuncias_value;?>],
						label: {
							show: true,
							position: 'inside'
						},		
						barWidth: '90%',				
						itemStyle: {
							normal: {
								color: function (params) {
									var colorList = ['#842B3A', '#924350', '#9F5A66', '#AD727C', '#BA8991', '#C8A1A7', '#D6B8BD', '#E4D0D3', '#E4D0D3', '#E4D0D3', '#E4D0D3', '#E4D0D3', '#E4D0D3', '#E4D0D3', '#E4D0D3', '#E4D0D3', '#E4D0D3'];
									return colorList[params.dataIndex];
								}
							}
						}						
					}
				]
			};
			//$("#denuncias_ccaa").css("display", "block");
			$("#denuncias_ccaa").addClass("echart-active");

			

			var myChart = echarts.init(document.getElementById('denuncias_ccaa'));
			//myChart.showLoading();
			myChart.setOption(option);


			var denuncias_prov = echarts.init(document.getElementById('denuncias_prov'));
			var option = {		
				grid:		{ left: '2%', right: '2%', bottom: '50', containLabel: true },
				legend:		{ show: false },
				tooltip:	{ trigger: 'axis' },
				calculable: true,         
				xAxis: { type: 'category',    data: [<?php echo $data_denuncias_prov;?>] , axisLabel: { interval: 0, rotate: 60 }  },
				yAxis: { type: 'value'  },				
				series:
				[	
					{
						name: 'PROVINCIA',
						type: 'bar',
						data: [<?php echo $data_denuncias_prov_value;?>],
						label: {
							show: true,
							position: 'inside'
						},		
						barWidth: '90%',				
						itemStyle: {
							normal: {
								color: function (params) {
									var colorList = ['#842B3A', '#924350', '#9F5A66', '#AD727C', '#BA8991', '#C8A1A7', '#D6B8BD', '#E4D0D3', '#E4D0D3', '#E4D0D3', '#E4D0D3', '#E4D0D3', '#E4D0D3', '#E4D0D3', '#E4D0D3', '#E4D0D3', '#E4D0D3'];
									return colorList[params.dataIndex];
								}
							}
						}						
					}
				]
			};
			denuncias_prov.setOption(option);

			var denuncias_punto = echarts.init(document.getElementById('denuncias_punto'));
			var option = {		
				grid:		{ left: '2%', right: '2%', bottom: '50', containLabel: true },
				legend:		{ show: false },
				tooltip:	{ trigger: 'axis' },
				calculable: true,         
				xAxis: { type: 'category',    data: [<?php echo $data_denuncias_punto;?>] , axisLabel: { interval: 0, rotate: 60 }  },
				yAxis: { type: 'value'  },				
				series:
				[	
					{
						name: 'PUNTO DE VENTA',
						type: 'bar',
						data: [<?php echo $data_denuncias_punto_value;?>],
						label: {
							show: true,
							position: 'inside'
						},		
						barWidth: '90%',				
						itemStyle: {
							normal: {
								color: function (params) {
									var colorList = ['#842B3A', '#924350', '#9F5A66', '#AD727C', '#BA8991', '#C8A1A7', '#D6B8BD', '#E4D0D3', '#E4D0D3', '#E4D0D3', '#E4D0D3', '#E4D0D3', '#E4D0D3', '#E4D0D3', '#E4D0D3', '#E4D0D3', '#E4D0D3'];
									return colorList[params.dataIndex];
								}
							}
						}						
					}
				]
			};
			denuncias_punto.setOption(option);			


			$('.btn-map').on('click', function(){	
				target = $(this).attr("data-type");
				$('.btn-map').removeClass("btn-primary").addClass("btn-secondary");
				$(this).addClass("btn-primary");

				$('.echart').removeClass("echart-active")
				$("#"+target).addClass("echart-active");
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
					}, 200);
				}
			});
		});
		</script>	