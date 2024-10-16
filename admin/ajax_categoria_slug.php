<?php
session_start();
session_start();
$roles = array('0','1');
include('includes/segur.php');

$path_relative = "../";
include('../includes/conexion.php');

$categoria_name = (isset($_POST['p']) ? $_POST['p'] : ""); 
$id = (isset($_POST['id']) ? $_POST['id'] : ""); 

if ($categoria_name!="") {
	$categoria_name = trim($categoria_name);
	$categoria_name = limpia_post_name(($categoria_name));
	$categoria_name = limpia_post_name($categoria_name);

	$sql="SELECT * FROM categorias WHERE slug like('$categoria_name%')"; 
	if ($id<>"") { $sql .= " AND id<>$id"; }
	$rs = $mysqli->query($sql); 
	if ($rs) {	
		$num_total_registros = $rs->num_rows; 
		if ($num_total_registros>0) {
			echo $categoria_name . "-" . ($num_total_registros+1);	
		} else {
			echo $categoria_name;
		}
	}
}
?>
