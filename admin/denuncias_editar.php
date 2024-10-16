<?php
session_start();
$roles = array('0','1','2');
include('includes/segur.php');

$path_relative = "../";
include('../includes/conexion.php');

$n1=7;

$id = (isset($_GET['id']) ? $_GET['id'] : ""); if ($id==""){  $id = (isset($_POST['id']) ? $_POST['id'] : ""); }
if ($id==""){ $id =0;}

$valor = (isset($_GET['val']) ? $_GET['val'] : ""); if ($valor==""){  $valor = (isset($_POST['val']) ? $_POST['val'] : ""); }
$accion = (isset($_GET['accion']) ? $_GET['accion'] : ""); if ($accion==""){  $accion = (isset($_POST['accion']) ? $_POST['accion'] : ""); }

if ($accion=="modificar") {
	$comentarios_internos = $_POST["comentarios_internos"];
	$fecha = date("Y-m-d H:i:s");

	$sql = "SELECT * FROM punto_venta_denunciado WHERE id=$id"; 
	$rs = $mysqli->query($sql); 
	$num_total_registros = $rs->num_rows; 
	if ($num_total_registros>0) {	
		$sql2 = "Update punto_venta_denunciado SET fecha_modificacion='$fecha', comentarios_internos='$comentarios_internos'";	
		$sql2 = $sql2 . " WHERE id=" . $id;		
	}
	$rs2 = $mysqli->query($sql2); 

	//echo "$sql2<br>";
    ?><script>document.location.href="denuncias_listado.php"</script><?php
	die();
}

$sql = "SELECT * FROM punto_venta_denunciado WHERE id=$id"; 
$rs = $mysqli->query($sql); 
$num_cursos = $rs->num_rows; 
if ($num_cursos > 0) {
    $fila = $rs->fetch_assoc();
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
	$denunciante_nombre = $fila['denunciante_nombre'];
	$denunciante_usuario = $fila['denunciante_usuario'];
	$denunciante_email = $fila['denunciante_email'];
	$msg_err = $fila['err'];

	if ($nombre=="Nombre del punto de venta (si lo tiene...)") { $nombre = ""; }	
	if ($telefono_contacto=="En caso de figurar, teléfono de contacto para acceder a comprar tabaco ilícito") { $telefono_contacto = ""; }
	if ($telefono_contacto=="" || is_null($telefono_contacto)) { $telefono_contacto = "-"; }
	if ($tu_telefono=="Tú teléfono *") { $tu_telefono = ""; }

	if ($tu_telefono=="") { $tu_telefono = "-"; }

	$sql2 = "SELECT * FROM comunidades WHERE id=$id_comunidad_autonoma";
	$rs2 = $mysqli->query($sql2); 
	if ($rs2->num_rows > 0) {
		$fila2 = $rs2->fetch_assoc();
		$comunidad_autonoma = $fila2['nombre'];
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

}
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

	<script src="assets/libs/jquery/dist/jquery.min.js"></script>
    <script src="assets/libs/popper.js/dist/umd/popper.min.js"></script>
    <script src="assets/libs/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js"></script>

    <script src="dist/js/app.min.js"></script>
    <script src="dist/js/app.init.js"></script>
    <script src="dist/js/app-style-switcher.js"></script>

    <script src="dist/js/waves.js"></script>
    <script src="dist/js/sidebarmenu.js"></script>
	<script src="dist/js/custom.min.js"></script>
	
	<script type="text/javascript" src="../js/comun.js"></script>


	<script language="JavaScript">
	<!--
	
	$(document).ready(function () {

	});

	function f_volver() { 
		location.href="denuncias_listado.php";
	}

	function verificar(){
		var ok=true;

		//---------------------------------------------------------------------				
		if (ok){		
			document.getElementById("accion").value="modificar";
			document.form1.submit();
			return true;
		}
	}		
	//-->
	</script>
</head>

<body>

<div class="preloader">
	<div class="lds-ripple">
		<div class="lds-pos"></div>
		<div class="lds-pos"></div>
	</div>
</div>

<form id="form1" name="form1" method="post">
	<input name="accion" type="hidden" id="accion">
	<input type="hidden" id="id" name="id" value="<?php echo $id;?>" />

	<div id="main-wrapper">
		<header class="topbar">
			<nav class="navbar top-navbar navbar-expand-md navbar-dark">
				<div class="navbar-header">
					<a class="nav-toggler waves-effect waves-light d-block d-md-none" href="javascript:void(0)">
						<i class="ti-menu ti-close"></i>
					</a>
					<div class="navbar-brand">
						<a href="inicio.php" class="logo">
							<span class="logo-text"><img src="images/logo.jpg" class="light-logo w100"/></span>
						</a>
						<a class="sidebartoggler d-none d-md-block" href="javascript:void(0)" data-sidebartype="mini-sidebar"><i class="mdi mdi-toggle-switch mdi-toggle-switch-off font-20"></i></a>
					</div>
					<a class="topbartoggler d-block d-md-none waves-effect waves-light" href="javascript:void(0)" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><i class="ti-more"></i></a>
				</div>
				<div class="navbar-collapse collapse" id="navbarSupportedContent">
					<ul class="navbar-nav float-left mr-auto">
						<li class="nav-item search-box">&nbsp;</li>
					</ul>
					<ul class="navbar-nav float-right">
						<li class="nav-item"><button type="button" class='btn btn-primary' onclick="f_volver()"><i class="fas fa-arrow-left"></i> &nbsp;Volver</button></li>
					</ul>
				</div>
			</nav>
		</header>

		<?php include('includes/menu.php'); ?>
        <div class="page-wrapper">

            <div class="page-breadcrumb">
                <div class="row">
                    <div class="col">
                        <h4 class="page-title">Detalle denuncia</h4>
                    </div>
                </div>
            </div>

            <div class="container-fluid">
				<div class="row">
                    <div class="col card card-body">
					<!-- CONTENT -->

						<div class="row">
							<div class="col-md-12">
								<button type="button" class="btn btn-primary" onClick="verificar()">Enviar</button>
								<div class="clearfix">&nbsp;</div>
							</div>							
						</div>

						<div class="row ficha_denuncia">
							<div class="col-md-6">
								<div class="row">
									<div class="col-md-6">
										<p class="bold">Fecha de alta</p>
										<p class="det"><?php echo $fecha_alta;?></p>
									</div>		
									<div class="col-md-6">
										<p class="rojo">REF<br><?php echo $id;?></p>
									</div>																
								</div>
			
								
								<p class="titular_rojo">Datos del punto de venta denunciado</p>


								<p class="bold">Comunidad Autónoma</p>
								<p class="det"><?php echo $comunidad_autonoma;?></p>

								<p class="bold">Provincia</p>
								<p class="det"><?php echo $provincia;?></p>

								<p class="bold">Localidad</p>
								<p class="det"><?php echo $localidad;?></p>

								<p class="bold">Tipo de punto de venta</p>
								<p class="det"><?php echo $tipo_punto_de_venta;?></p>

								<p class="bold">Nombre</p>
								<p class="det"><?php echo $nombre;?></p>

								<p class="bold">Teléono contacto para acceder a comprar tabaco ilícito</p>
								<p class="det"><?php echo $telefono_contacto;?></p>

								<p class="bold">Dirección</p>
								<p class="det"><?php echo $direccion;?></p>

								<p class="bold">Comentarios</p>
								<p class="det"><?php echo $comentarios;?></p>

								<p class="bold">Tú teléfono</p>
								<p class="det"><?php echo $tu_telefono;?></p>								

							</div>
							
							<div class="col-md-6">
								<p class="bold">Última actualización</p>
								<p class="det"><?php echo $fecha_modificacion;?></p>

								<p class="titular_rojo">Comentarios internos</p>

								<textarea name="comentarios_internos" id="comentarios_internos" class="form-control" rows="10"><?php echo $comentarios_internos;?></textarea>
							</div>	
							
						</div>


						<div class="row">
							<div class="col-md-12">
								<div class="clearfix">&nbsp;</div>
								<button type="button" class="btn btn-primary" onClick="verificar()">Enviar</button>
							</div>
							<div class="col-md-12">	
								<div class="error text-danger">&nbsp;</div>
							</div>							
						</div>								

					<!-- CONTENT -->
                    </div>
				</div>
            </div>
        </div>
	</div><!-- End Wrapper -->
</form>

</body>

</html>
