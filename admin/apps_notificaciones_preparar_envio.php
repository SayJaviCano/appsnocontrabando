<?php
session_start();
$roles = array('0','1','2','3');
include('includes/segur.php');

$path_relative = "../";
include('../includes/conexion.php');

$id_notificacion = (isset($_GET['id_notificacion']) ? $_GET['id_notificacion'] : 0); if ($id_notificacion==0){  $id_notificacion = (isset($_POST['id_notificacion']) ? $_POST['id_notificacion'] : 0); }

?>
<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<link href="dist/css/style.css" rel="stylesheet">
<link href="css/custom.css" rel="stylesheet">

<script src="assets/libs/jquery/dist/jquery.min.js"></script>
<script src="assets/libs/popper.js/dist/umd/popper.min.js"></script>
<script src="assets/libs/bootstrap/dist/js/bootstrap.min.js"></script>


<script type="text/javascript">
	$(document).ready(function(){
		parent.realizar_envio('<?php echo $id_notificacion;?>');
	});
</script>

</head>
<body>
<?php
	$sql = "DELETE FROM app_ca_envios WHERE id_notificacion=$id_notificacion";
	$rs = $mysqli->query($sql); 

	$sql = "SELECT * FROM app_ca_campaign WHERE enviado=0 AND id=$id_notificacion";
	$rs = $mysqli->query($sql); 
	if ($rs->num_rows > 0) {
		$fila = $rs->fetch_assoc();
		$nombre = $fila['nombre'];
		$titulo = $fila['titulo'];
		$texto = $fila['texto'];
		$enlace = $fila['enlace'];
		$grupo_envio = $fila['grupo_envio'];	// 0=Todos, 1=IOS, 2=Android, 3=Web
	}	

	$sql = "SELECT * FROM app_dispositivos WHERE token<>'' ";
	if ($grupo_envio==1) { $sql = "SELECT * FROM app_dispositivos WHERE token<>'' AND grupo_envio=1"; }
	if ($grupo_envio==2) { $sql = "SELECT * FROM app_dispositivos WHERE token<>'' AND grupo_envio=2"; }
	if ($grupo_envio==3) { $sql = "SELECT * FROM app_dispositivos WHERE token<>'' AND grupo_envio=3"; }	
	$sql .= " ORDER BY id";
	$rs = $mysqli->query($sql); 

	if ($rs!="") {
		while ($fila = $rs->fetch_array(MYSQLI_ASSOC)){
			$id_dispositivo = ($fila['id']);
			$tipo_dispositivo = ($fila['tipo']);

			$sql2 = "INSERT INTO app_ca_envios (id_notificacion, id_dispositivo, tipo_dispositivo, enviado) VALUES ($id_notificacion, $id_dispositivo, $tipo_dispositivo, 0)";
			$rs2 = $mysqli->query($sql2); 

			$contador++;			
		}
	}
?>
</body>
</html>