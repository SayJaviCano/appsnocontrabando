<?php
session_start();
$roles = array('0','1','2');
include('includes/segur.php');

$path_relative = "../";
include('../includes/conexion.php');


$fecha_hasta = (isset($_POST['fecha_hasta']) ? $_POST['fecha_hasta'] : ""); 
if ($fecha_hasta=="" || is_null($fecha_hasta)) { $fecha_hasta=date("d/m/Y"); }

$fecha_desde = (isset($_POST['fecha_desde']) ? $_POST['fecha_desde'] : ""); 
if ($fecha_desde=="" || is_null($fecha_desde)) { 
	$array_fecha_hasta = explode("/", $fecha_hasta);
	$fecha_desde = "01/" . $array_fecha_hasta[1] . "/" . $array_fecha_hasta[2];
}

$id_comunidad_autonoma = (isset($_POST['id_comunidad_autonoma']) ? $_POST['id_comunidad_autonoma'] : ""); 
if ($id_comunidad_autonoma=="" || is_null($id_comunidad_autonoma)) { $id_comunidad_autonoma=0; }

$id_provincia = (isset($_POST['id_provincia']) ? $_POST['id_provincia'] : ""); 
if ($id_provincia=="" || is_null($id_provincia)) { $id_provincia=0; }

$localidad = (isset($_POST['localidad']) ? $_POST['localidad'] : ""); 
if ($localidad=="" || is_null($localidad) || $localidad=="Localidad") { $localidad=""; }

$tipo_punto_venta = (isset($_POST['tipo_punto_venta']) ? $_POST['tipo_punto_venta'] : ""); 
if ($tipo_punto_venta=="" || is_null($tipo_punto_venta)) { $tipo_punto_venta=0; }

$procedencia = (isset($_POST['procedencia']) ? $_POST['procedencia'] : ""); 
if ($procedencia=="" || is_null($procedencia)) { $procedencia= 99; }

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

<style type="text/css">
body {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 11px;
}
td { padding: 0 5px 0 5px; font-size: 12px; border:1px solid #CCC;}
</style>

</head>
<body>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
        <td bgcolor="#CCCCCC"><b>Fecha alta</b></td>
        <td bgcolor="#CCCCCC"><b>Fecha modificaci&oacute;n</b></td>
        <td bgcolor="#CCCCCC"><b>Comunidad</b></td>
        <td bgcolor="#CCCCCC"><b>Provincia</b></td>
        <td bgcolor="#CCCCCC"><b>Localidad</b></td>
        <td bgcolor="#CCCCCC"><b>Tipo de punto de venta</b></td>
        <td bgcolor="#CCCCCC"><b>Nombre</b></td>
		<td bgcolor="#CCCCCC"><b>Tel&eacute;fono de contacto</b></td>
        <td bgcolor="#CCCCCC"><b>Direcci&oacute;n</b></td>
        <td bgcolor="#CCCCCC"><b>Comentarios</b></td>
        <td bgcolor="#CCCCCC"><b>Procedencia</b></td>
		<td bgcolor="#CCCCCC"><b>Denunciante Tel&eacute;fono</b></td>
        <td bgcolor="#CCCCCC"><b>Denunciante Nombre</b></td>
        <td bgcolor="#CCCCCC"><b>Denunciante eMail</b></td>
        <td bgcolor="#CCCCCC"><b>Comentarios Internos</b></td>
      </tr>
	<?php


		$sql = "SELECT * FROM punto_venta_denunciado WHERE id<>0";
		if ($fecha_desde!="") { $sql .=  " AND  fecha_alta >= '" . cambia_fecha_a_mysql($fecha_desde) . "'"; }
		if ($fecha_hasta!="") { $sql .=  " AND  fecha_alta <= '" . cambia_fecha_a_mysql($fecha_hasta) . "'"; }
		if ($id_comunidad_autonoma!="0") { $sql .=  " AND  id_comunidad_autonoma=$id_comunidad_autonoma"; }
		if ($id_provincia!="0") { $sql .=  " AND  id_provincia=$id_provincia"; }
		if ($localidad != "") { $sql.= " AND (localidad LIKE '%$localidad%') "; }
		if ($tipo_punto_venta!="0") { $sql .=  " AND  id_tipo_punto_de_venta=$tipo_punto_venta"; }
		if ($procedencia!=99) { $sql .=  " AND  procedencia=$procedencia"; }
		$sql.= " ORDER BY fecha_alta DESC";

		//echo "$sql<br>";

		$rs = $mysqli->query($sql); 

		if ($rs->num_rows > 0) {

			while ($fila = $rs->fetch_array(MYSQLI_ASSOC)){
				$id = $fila['id'];		
				$fecha_alta = cambiaf_a_normal($fila['fecha_alta']);
				$fecha_modificacion = cambiaf_a_normal($fila['fecha_modificacion']);		
				$id_comunidad_autonoma = $fila['id_comunidad_autonoma'];		
				$id_provincia = $fila['id_provincia'];	
				$localidad = $fila['localidad'];	
				$id_tipo_punto_de_venta = $fila['id_tipo_punto_de_venta'];	
				$tu_telefono = $fila['tu_telefono'];

				$nombre = $fila['nombre'];
				if ($nombre=="" || is_null($nombre)) { $nombre = "-"; }
				$telefono_contacto = $fila['telefono_contacto'];
				$direccion = $fila['direccion'];
				$comentarios = $fila['comentarios'];
				if ($comentarios!="") { $comentarios = str_replace("\n", "<br>", $comentarios); }

				$comentarios_internos = $fila['comentarios_internos'];
				$procedencia = $fila['procedencia'];
				$tx_procedencia = "";
				if		( intval($procedencia) == 0) { $tx_procedencia = "Área oficinas"; }
				elseif	( intval($procedencia) == 1) { $tx_procedencia = "Portal de ventas"; }
				elseif	( intval($procedencia) == 3) { $tx_procedencia = "No contrabando"; }
				elseif	( intval($procedencia) == 4) { $tx_procedencia = "APP"; }
				
				
				$denunciante_nombre = $fila['denunciante_nombre'];
				$denunciante_usuario = $fila['denunciante_usuario'];
				$denunciante_email = $fila['denunciante_email'];
				$msg_err = $fila['err'];

				if ($nombre=="Nombre del punto de venta (si lo tiene...)") { $nombre = ""; }
				
				if ($telefono_contacto=="En caso de figurar, teléfono de contacto para acceder a comprar tabaco ilícito") { $telefono_contacto = ""; }
				if ($telefono_contacto=="" || is_null($telefono_contacto)) { $telefono_contacto = "-"; }
				if ($tu_telefono=="Tú teléfono *") { $tu_telefono = ""; }



				$sql2 = "SELECT * FROM comunidades WHERE id=$id_comunidad_autonoma";
				$rs2 = $mysqli->query($sql2); 
                if ($rs2->num_rows > 0) {
                    $fila2 = $rs2->fetch_assoc();
                    $comunidad = $fila2['nombre'];
                }
				$sql2 = "SELECT * FROM provincias WHERE id_comunidad=$id_comunidad_autonoma AND id=$id_provincia";
				$rs2 = $mysqli->query($sql2); 
                if ($rs2->num_rows > 0) {
                    $fila2 = $rs2->fetch_assoc();
                    $provincia = $fila2['nombre'];
                }	
				$sql2 = "SELECT * FROM tipos_punto_de_venta WHERE id=$id_tipo_punto_de_venta";
				$rs2 = $mysqli->query($sql2); 
                if ($rs2->num_rows > 0) {
                    $fila2 = $rs2->fetch_assoc();
                    $tipo_punto_de_venta = $fila2['nombre'];
                }	

				
				?>
				<tr>
					<td><?php echo $fecha_alta?></td>
					<td><?php echo $fecha_modificacion?></td>
					<td><?php echo $comunidad?></td>
					<td><?php echo $provincia?></td>
					<td><?php echo $localidad?></td>
					<td><?php echo $tipo_punto_de_venta;?></td>
					<td><?php echo $nombre;?></td>
					<td><?php echo $telefono_contacto;?></td>
					<td><?php echo $direccion?></td>
					<td><?php echo $comentarios?></td>
					<td><?php echo $tx_procedencia?></td>
					<td><?php echo $tu_telefono?></td>
					<td><?php echo $denunciante_nombre?></td>
					<td><?php echo $denunciante_email?></td>
					<td><?php echo $comentarios_internos?></td>
				</tr>
				<?php			
			}
		}
		?>

	  </table>  


</body>

</html>