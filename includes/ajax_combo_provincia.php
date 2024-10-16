<?php
header("Expires: Sat, 01 Jan 2000 00:00:00 GMT");
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
header("Cache-Control: post-check=0, pre-check=0",false);
session_cache_limiter("must-revalidate");

session_start();

$path_relative="../";
require_once('conexion.php');
?>
 <option value="0">Provincia</option>
 <?php
	$id_comunidad_autonoma = LimpiaParametros($_POST["ca"]);
	if (!is_numeric($id_comunidad_autonoma)) { $id_comunidad_autonoma = 0; }

	$prov_sel = LimpiaParametros($_POST["prov_sel"]);
	if (!is_numeric($prov_sel)) { $prov_sel = 0; }

	$sql = "SELECT * FROM provincias WHERE id_comunidad=$id_comunidad_autonoma ORDER BY orden";
	$rs = $mysqli->query($sql); 
	
	while ($fila = $rs->fetch_array(MYSQLI_ASSOC)){
		$provincia_id = $fila['id'];
		$provincia_nombre = $fila['nombre'];
		?><option value="<?php echo $provincia_id; ?>" <?php if ($prov_sel == $provincia_id) { echo "selected"; } ?>><?php echo $provincia_nombre; ?></option><?php
	}
?>