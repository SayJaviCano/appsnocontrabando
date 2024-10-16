<?php
session_start();
$roles = array('0','1');
include('includes/segur.php');

$path_relative = "../";
include('../includes/conexion.php');

$id = (isset($_GET['id']) ? $_GET['id'] : ""); if ($id==""){  $id = (isset($_POST['id']) ? $_POST['id'] : ""); }
$valor = (isset($_GET['val']) ? $_GET['val'] : ""); if ($valor==""){  $valor = (isset($_POST['val']) ? $_POST['val'] : ""); }
$accion = (isset($_GET['accion']) ? $_GET['accion'] : ""); if ($accion==""){  $accion = (isset($_POST['accion']) ? $_POST['accion'] : ""); }
if ($id==""){ $id =0;}

$n1=10;

$_SESSION['dir_img_editor'] = "../images/nosotros/";
$uploadDir = $_SESSION['dir_img_editor'];

$aux_n_fich = date("his");

if ($accion=="modificar") {
	$activo = $_POST["activo"];
	$orden = $_POST["orden"];
	$pregunta = ($_POST["pregunta"]);	
	$respuesta = ($_POST["respuesta"]);

	$sql = "SELECT * FROM nosotros WHERE id='$id'"; 
	$rs = $mysqli->query($sql); 
	$num_total_registros = $rs->num_rows; 
	if ($num_total_registros>0) {
		$sql2 = "Update nosotros SET activo=$activo, orden='$orden', pregunta='$pregunta', respuesta='$respuesta'";
		$sql2 = $sql2 . " WHERE id='$id'"; 
	} else {
		$sql2 = "INSERT INTO nosotros (activo, orden, pregunta, respuesta) VALUES ($activo, '$orden', '$pregunta', '$respuesta')";     
	}
	$rs2 = $mysqli->query($sql2);
	//echo "$sql2<br>";
	?><script>location.href="nosotros_listado.php";</script><?php
}

$orden = 1;
$activo = 1;
$sql = "SELECT * FROM nosotros WHERE id='$id'"; 
$rs = $mysqli->query($sql); 
if ($rs->num_rows > 0) {
	$fila = $rs->fetch_assoc();
	$activo = $fila['activo'];
	$orden = $fila['orden'];
	$pregunta = ($fila['pregunta']);
	$respuesta = ($fila['respuesta']);
} else {
	$sql = "SELECT * FROM nosotros ORDER BY orden DESC LIMIT 1"; 
	$rs = $mysqli->query($sql); 
    if ($rs->num_rows > 0) {
		$fila = $rs->fetch_assoc();
		$orden = $fila['orden'] + 1;
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
	
	<script>var root_path = "";</script>
	<script type="text/javascript" src="ckeditor/ckeditor.js" charset="utf-8"></script>  	

	<script type="text/javascript" src="../js/comun.js"></script>


	<script language="JavaScript">
	<!--
	$(document).ready(function () { 
		CKEDITOR.replace('respuesta', { height : '400'});
	});

	function verificar(){
		var ok=true;

		if (document.getElementById("pregunta").value=="" && ok!=false){
			ok=false;
			$(".error").html("El campo pregunta es obligatorio");
			document.getElementById("pregunta").focus();
		}		

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

<form method="post" enctype="multipart/form-data" name="form1">
	<input name="accion" type="hidden" id="accion">


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
						<li class="nav-item"> </li>
					</ul>
				</div>
			</nav>
		</header>
		
		<?php include('includes/menu.php'); ?>
        <div class="page-wrapper">

            <div class="page-breadcrumb">
                <div class="row">
                    <div class="col">
                        <h4 class="page-title">Nosotros</h4>
                    </div>
                </div>
            </div>

            <div class="container-fluid">
				<div class="row">
                    <div class="col card card-body">
					<!-- CONTENT -->

						<div class="row">
							<div class="col-md-2">
								<button type="button" class="btn btn-primary" onClick="verificar()">Guardar</button>
							</div>		
							<div class="col-md-10">	
								<div class="error text-danger">&nbsp;</div>
							</div>												
						</div>
						<div class="clearfix">&nbsp;</div>

						<div class="row">
							<div class="col-md-12">
								<div class="row">
									<div class="col-md-2">
										<label for="activo" class="mr-sm-2">Activo</label>
										<select name="activo" id="activo" class="form-control mr-sm-5">
											<option value="1" <?php if($activo=="1") { echo "selected"; } ?>>SI</option>
											<option value="0" <?php if($activo=="0") { echo "selected"; } ?>>NO</option>
										</select>
									</div>
									<div class="col-md-2">
										<label for="slug" class="mr-sm-2">Orden</label>
										<input type="text" id="orden" name="orden" class="form-control" value="<?php echo $orden;?>" maxlength="255"/>
									</div>		
								</div>
								<div class="row">	
									<div class="col-md-12">
										<label for="pregunta" class="mr-sm-2">Pregunta</label>
										<textarea name="pregunta" id="pregunta" class="form-control"><?php echo $pregunta;?></textarea>
									</div>		

									<div class="col-md-12">
										<label for="respuesta" class="mr-sm-2">Respuesta</label>
										<textarea name="respuesta" id="respuesta" class="form-control"><?php echo $respuesta;?></textarea>
									</div>																											
								</div>								
						
						
								<div class="clearfix">&nbsp;</div>
								<div class="row">
									<div class="col-md-2">
										<button type="button" class="btn btn-primary" onClick="verificar()">Guardar</button>
									</div>
									<div class="col-md-10">	
										<div class="error text-danger">&nbsp;</div>
									</div>							
								</div>

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
