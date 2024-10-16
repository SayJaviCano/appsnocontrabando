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
    if ($acc=="contacto") {


        $nombre = LimpiaParametros((isset($_POST['nombre']) ? $_POST['nombre'] :""));
		$email = LimpiaParametros((isset($_POST['email']) ? $_POST['email'] :""));
		$consulta = LimpiaParametros((isset($_POST['consulta']) ? $_POST['consulta'] :""));


		/************ enviar mail con la consulta *****************/
		$plantilla = "plantilla.html";
		$fp = fopen($plantilla,'r');
		$texto_mail = fread($fp, filesize($plantilla));		

		$consulta = str_replace ("\n", "<br>", $consulta);

		$tx .= "<p style='font-family:Arial, Helvetica, sans-serif; font-size:16px; color:#757575;'>Se ha recibido el siguiente mensaje a través del formulario de la web
		NO contrabando.</p>";					
		$tx .= "<p style='font-family:Arial, Helvetica, sans-serif; font-size:16px; color:#757575;'><b>Nombre</b>: $nombre</p>";	
		$tx .= "<p style='font-family:Arial, Helvetica, sans-serif; font-size:16px; color:#757575;'><b>E-Mail</b>: $email</p>";				
		$tx .= "<p style='font-family:Arial, Helvetica, sans-serif; font-size:16px; color:#757575;'><b>Consulta</b>:<br>$consulta</p><br><br>";		
		$tx .= '<p><a href="https://nocontrabando.altadis.com/" style="font-family:Arial, Helvetica, sans-serif; font-size:16px; color:#757575;"><b>NOcontrabando.altadis.com</b></a></p>';	

		$texto_plano = trim($texto_mail);
		$texto_plano = strip_tags($texto_plano);				
		$texto_plano = str_replace ("\n", " ", $texto_plano);

		$texto_mail = str_replace ("#TX#", $tx, $texto_mail);
		$ruta_img = $dominio . "images/";
		$texto_mail = str_replace ("../images/", $ruta_img, $texto_mail);				

		$asunto = "Consulta desde contacto";
		$para = LimpiaParametros((isset($_POST['para']) ? $_POST['para'] :""));
		if ($para=="") { $para = "nocontrabando.altadis@gmail.com"; }

		//$tobcc = "";	$tobccname = "";
		$tobcc = array ("fm@saysawa.com", "jc@saysawa.com");	$tobccname = array ("", "");

		$valor = SendEmail($de, $deNombre ,$para, $paraNombre, $asunto, $texto_mail, $texto_plano, $tobcc, $tobccname, "", "");		
		/******************************************************* */
		

      
    }
}


?>