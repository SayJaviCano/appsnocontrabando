<?php
require_once('includes/mobile_detect.php');
$detect = new Mobile_Detect();
$es_movil = $detect->isMobile();

$http_host = $_SERVER['HTTP_HOST'];

if ($http_host=="appsnocontrabando.com") {
    $dominio = "https://appsnocontrabando.com/";    
} else {
    $dominio = "https://nocontrabando.altadis.com/";
}

$use_sts = true;
if ($use_sts && isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') {
    header('Strict-Transport-Security: max-age=10886400; includeSubDomains; preload');
    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: SAMEORIGIN');
    header('X-XSS-Protection: 1; mode=block');
} elseif ($use_sts) {
    header('Location: https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'], true, 301);
    die();
}
$ruta_real = "";

$dominio_apps = "https://appsnocontrabando.com/";
$dominio_web = "https://nocontrabando.altadis.com/";


$clave_crip = "KEY-NOContrabando#04|21";


// ****************  BBDD  **************************** //
$maquinaConexion = "localhost";
$DB = "altadis_nocontrabando";	
$usuarioConexion = "nocontrabando_bd";
$claveConexion = "76Gimz7#";

$de = "ilicito@es.imptob.com"; //"nocontrabando.altadis@gmail.com";
$deNombre = "No Contrabando";

$mail_host	= "smtp.acens.com";
$mail_username	= "clientes.saysawa.com";
$mail_password  = "Clientes2015";



?>