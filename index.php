<?php
header("Expires: Sat, 01 Jan 2000 00:00:00 GMT");
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
header("Cache-Control: post-check=0, pre-check=0",false);
session_cache_limiter("must-revalidate");

session_start();
$path_relative="";

$ip_autorizada = 0;
//if ($_SERVER['SERVER_NAME'] == "asus" ) { $ip_autorizada = 1; } // FM

$a = (isset($_GET['a']) ? $_GET['a'] :""); 
if ($a==1) { $_SESSION['sess_ip_autorizada'] = 1; }
$ip_autorizada = (isset($_SESSION['sess_ip_autorizada'] ) ? $_SESSION['sess_ip_autorizada'] : $ip_autorizada); 
$ip_autorizada = 1;

include('includes/conexion.php');

/*
$http_host = $_SERVER['HTTP_HOST'];
if ($http_host=="appsnocontrabando.com") {
   // if ($es_movil==false)	{  header('Location: https://nocontrabando.altadis.com', true, 301); die(); }       
}
*/


if ($ip_autorizada==1) {
	require_once('includes/meta.php');
} else { ?>
<!doctype html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Altadis - Líder en la lucha contra el comercio ilícito de tabaco</title>
	<style type="text/css">
	html { display: table; height: 100%; width: 100%; background: #FFFFFF;}
	body {  display: table-row; color:#000000; font-family: Arial,Helvetica Neue,Helvetica,sans-serif;}
	.center {  display: table-cell; vertical-align: middle; text-align: center; }
	.img-fluid, .modal-dialog.cascading-modal.modal-avatar .modal-header, .video-fluid { max-width: 100%;    height: auto; }	
	</style>
</head>
<body>
	<div class="center">
		<img src="<?php echo $dominio;?>images/no_contrabando_rojo.png" alt="NO Contrabando" class="img-fluid"/>
	</div>
</body>
</html>
<?php }?>