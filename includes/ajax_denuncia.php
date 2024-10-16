<?php
header("Expires: Sat, 01 Jan 2000 00:00:00 GMT");
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
header("Cache-Control: post-check=0, pre-check=0",false);
session_cache_limiter("must-revalidate");

session_start();

$path_relative="../";
require_once('conexion.php');

if ($_POST['verificacion'] != ""){
	exit();
} else {

	$acc = LimpiaParametros((isset($_POST['acc']) ? $_POST['acc'] :"empty"));

    if ($acc=="denuncia") {
        $comunidad_autonoma = LimpiaParametros((isset($_POST['comunidad_autonoma']) ? $_POST['comunidad_autonoma'] :""));
        $provincia = LimpiaParametros((isset($_POST['provincia']) ? $_POST['provincia'] :""));
        $localidad = LimpiaParametros((isset($_POST['localidad']) ? $_POST['localidad'] :""));
        $tipo_punto_venta = LimpiaParametros((isset($_POST['tipo_punto_venta']) ? $_POST['tipo_punto_venta'] :""));
        $nombre = LimpiaParametros((isset($_POST['nombre']) ? $_POST['nombre'] :""));
		$telefono_contacto = LimpiaParametros((isset($_POST['telefono_contacto']) ? $_POST['telefono_contacto'] :""));
		$direccion = LimpiaParametros((isset($_POST['direccion']) ? $_POST['direccion'] :""));
		$comentarios = LimpiaParametros((isset($_POST['comentarios']) ? $_POST['comentarios'] :""));
		$tu_telefono = LimpiaParametros((isset($_POST['tu_telefono']) ? $_POST['tu_telefono'] :""));

		$fecha = date("Y-m-d H:i:s");
		$msg_err = "";

		$procedencia = 3;  // 0=Área oficinas; 1=Portal de ventas; 3=No contrabando; 4=APP
		if ($_SERVER['SERVER_NAME'] == "appsnocontrabando.com") {
			$procedencia = 4;
		}		
		
        $sql2 = "INSERT INTO punto_venta_denunciado (fecha_alta, fecha_modificacion, id_comunidad_autonoma, id_provincia, localidad, id_tipo_punto_de_venta, nombre, telefono_contacto, direccion, comentarios, tu_telefono, procedencia, err) VALUES";
        $sql2 = $sql2 . "('$fecha', '$fecha', '$comunidad_autonoma', '$provincia', '$localidad', '$tipo_punto_venta', '$nombre', '$telefono_contacto', '$direccion', '$comentarios', '$tu_telefono', '$procedencia', '$msg_err')";
        $rs2 = $mysqli->query($sql2);

		//echo "$sql2";
    }
}


?>