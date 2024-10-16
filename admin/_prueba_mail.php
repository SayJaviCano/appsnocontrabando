<?php
session_start();

date_default_timezone_set('Europe/Madrid');
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE); // Notificar E_NOTICE también puede ser bueno (para informar de variables no inicializadas o capturar errores en nombres de variables ...)

$path_relative="../";
require_once('../includes/conexion.php');


$de = "ilicito@es.imptob.com";
$deNombre = ("No Contrabando");


$para = array ("fm@saysawa.com","fran.moratinos@gmail.com");
$paraNombre = array ("", "");


$tobcc = "";	$tobccname = "";

/*
$mail_host	= "smtp.planet-salud.com";
$mail_username	= "info.planet-salud.com";
$mail_password  = "PSnov3001";


$mail_host	= "smtp.acens.com";				// => OK
$mail_username	= "clientes.saysawa.com";
$mail_password  = "Clientes2015";

$mail_host	= "relay1.saysawa.com";				//GIGAS 146.255.102.83 => 504 Gateway Time-out
$mail_username	= "sender@relay1.saysawa.com";
$mail_password	= "Relay2019#";

$mail_host	= "relay2.saysawa.com";				// ACENS 217.116.4.218 => SMTP connect() failed.
$mail_username	= "sender@relay2.saysawa.com";
$mail_password	= "Relay_2$2019#";
*/

$mail_host	= "relay1.saysawa.com";				//GIGAS 146.255.102.83 => 504 Gateway Time-out
$mail_username	= "sender@relay1.saysawa.com";
$mail_password	= "Relay2019#";

$asunto = ("prueba $mail_host ÁéÍñ");
$texto_mail = "<table border='1'><tr><td>host: </td> <td>$mail_host</td></tr><tr><td>username: </td><td>$mail_username</td></tr></table>";
$texto_plano = "prueba plain $mail_host";

echo "<br>host: $mail_host<br>";
echo "de: $de<br>";

$valor = SendEmail($de, $deNombre ,$para, $paraNombre, $asunto, $texto_mail, $texto_plano, $tobcc, $tobccname, '', '');
if ($valor=="") { $valor="OK"; } 
echo "<br>$valor<br>";
?>
