<?php
session_start();
session_start();
$roles = array('0','1');
include('includes/segur.php');

$path_relative = "../";
include('../includes/conexion.php');

$post_name = (isset($_POST['p']) ? $_POST['p'] : ""); 
$id = (isset($_POST['id']) ? $_POST['id'] : ""); 

if ($post_name!="") {
	$post_name = trim($post_name);
	$post_name = limpia_post_name(($post_name));
	$post_name = limpia_post_name($post_name);

	$sql="SELECT * FROM contenidos WHERE tipo='post' AND post_name like('$post_name%')"; 
	if ($id<>"") { $sql .= " AND id<>$id"; }
	$rs = $mysqli->query($sql); 
	if ($rs) {	
		$num_total_registros = $rs->num_rows; 
		if ($num_total_registros>0) {
			echo $post_name . "-" . ($num_total_registros+1);	
		} else {
			echo $post_name;
		}
	}
}
?>
