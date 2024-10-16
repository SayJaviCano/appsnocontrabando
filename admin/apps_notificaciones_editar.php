<?php
session_start();
$roles = array('0','1');
include('includes/segur.php');

$path_relative = "../";
include('../includes/conexion.php');

$id = (isset($_GET['id']) ? $_GET['id'] : ""); if ($id==""){  $id = (isset($_POST['id']) ? $_POST['id'] : ""); }
if (!is_numeric($id)) { $id = 0; }

$accion = (isset($_GET['accion']) ? $_GET['accion'] : ""); if ($accion==""){  $accion = (isset($_POST['accion']) ? $_POST['accion'] : ""); }

$n1=9;
$n2=1;

$max_title = 46;
$max_description = 95;


if ($accion=="modificar") {
	$nombre = $_POST["nombre"];
	$titulo = $_POST["titulo"];
	$texto = ($_POST["texto"]);
	$enlace = ($_POST["enlace"]);
	$id_noticia = ($_POST["id_noticia"]);
	$grupo_envio = ($_POST["grupo_envio"]);

	if ($nombre=="") {	
		$fecha = date("d/m/Y");
		$nombre = "Notificación $fecha";
	}

	$sql = "SELECT * FROM app_ca_campaign WHERE id='$id'"; 
	$rs = $mysqli->query($sql); 
	$num_total_registros = $rs->num_rows; 
	if ($num_total_registros>0) {
		$fila = $rs->fetch_assoc();	
		$sql2 = "Update app_ca_campaign SET nombre='$nombre', titulo='$titulo', texto='$texto', enlace='$enlace', id_noticia=$id_noticia, grupo_envio='$grupo_envio'";
		$sql2 = $sql2 . " WHERE id='$id'"; 
		$rs2 = $mysqli->query($sql2);

	} else {
		$fecha = date("Y-m-d H:i:s");
		$sql2 = "INSERT INTO app_ca_campaign (fecha_alta, nombre, titulo, texto, enlace, id_noticia, grupo_envio";
		$sql2 .= ") VALUES ('$fecha', '$nombre', '$titulo', '$texto', '$enlace', '$id_noticia', '$grupo_envio'";
		$sql2 .= ")";     
		$rs2 = $mysqli->query($sql2);
	}	

	//echo "$sql2<br>";
	?><script>document.location.href="apps_notificaciones_listado.php"</script><?php
	die();
}

$page_title = "Nueva Notificación";
$enlace = ""; $id_noticia=0;

$fecha = date("d/m/Y");
$app_nombre_placeholder = "Notificación $fecha";

$sql = "SELECT * FROM app_ca_campaign WHERE id='$id'"; 
$rs = $mysqli->query($sql); 
if ($rs->num_rows > 0) {
	$fila = $rs->fetch_assoc();
	$fecha_alta = $fila['fecha_alta'];	
	$fecha_envio = $fila['fecha_envio'];	
	$app_nombre = $fila['nombre'];	
	$titulo = $fila['titulo'];	
	$texto = $fila['texto'];	
	$enlace = $fila['enlace'];	
	$id_noticia = $fila['id_noticia'];	
	$enviado = $fila['enviado'];	
	$grupo_envio = $fila['grupo_envio'];	// 0=Todos, 1=IOS, 2=Android, 3=Web
	
	$page_title = "Editar Notificación";
}  else {
	$app_nombre = $app_nombre_placeholder;	
}

$conta_title = $max_title - strlen($titulo);
if ($conta_title < 0) { $conta_title= '<span class="red">' . $conta_title . '</span>';}

$conta_description = $max_description - strlen($texto);
if ($conta_description < 0) { $conta_description= '<span class="red">' . $conta_description . '</span>';}

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title></title>

  	<link href="../css/jquery.fancybox.min.css" rel="stylesheet">	

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
	
	<script src="../js/jquery.fancybox.min.js"></script>	

	<script>var root_path = "";</script>
	<script type="text/javascript" src="../js/comun.js"></script>

	<script language="JavaScript">
	<!--
	$(document).ready(function () { 
	
	});


	function f_notificacion(titulo, texto, enlace, id_noticia)
	{
		document.getElementById("titulo").value = titulo;
		document.getElementById("texto").value = texto;
		document.getElementById("enlace").value = enlace;
		document.getElementById("id_noticia").value = id_noticia;

		countChars(document.getElementById("titulo"), <?php echo $max_title;?>, 'charNumt');
		countChars(document.getElementById("texto"), <?php echo $max_description;?>, 'charNum');

	}



	function verificar(){
		var ok=true;

		if (document.getElementById("nombre").value=="" && ok!=false){
			ok=false;
			$(".error").html("El campo nombre es obligatorio");
			document.getElementById("nombre").focus();
		}		

		//---------------------------------------------------------------------				
		if (ok){		
			document.form1.action="apps_notificaciones_editar.php";
			document.getElementById("accion").value="modificar";
			document.form1.submit();
			return true;
		}
	}

	function countChars(obj, maxLength, id_salida){
		var strLength = obj.value.length;
		var charRemain = (maxLength - strLength);
		
		if(charRemain < 0){
			document.getElementById(id_salida).innerHTML = '<span class="red">'+ (charRemain) +'</span>';
		}else{
			document.getElementById(id_salida).innerHTML = charRemain;
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
	<input name="id" type="hidden" id="id" value="<?php echo $id;?>">
	<input name="id_noticia" type="hidden" id="id_noticia" value="<?php echo $id_noticia;?>">
	
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
                        <h4 class="page-title"><?php echo "$page_title";?></h4>
                    </div>
                </div>
            </div>

            <div class="container-fluid">
				<div class="row">
                    <div class="col-8 card card-body">
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
									<div class="col-md-12">
										<label for="nombre" class="mr-sm-2">Nombre</label>
										<input type="text" id="nombre" name="nombre" class="form-control" value="<?php echo $app_nombre;?>" maxlength="255" placeholder="<?php echo $app_nombre_placeholder;?>"/>
									</div>									
			
									<div class="col-md-12">
										<a class="btn btn-primary" href="ajax_apps_notificaciones_noticias.php" data-fancybox="" data-options='{ "type" : "iframe", "iframe" : { "preload" : false, "css" : { "width" : "1100px",  "height" : "700px" } } }'>Selecciona una noticia</a>	
										<hr>		
									</div>
								</div>

								<div class="row mt-3">	
									<div class="col-md-12">
										<label for="titulo" class="mr-sm-2">Título <span id="charNumt" class="contador"><?php echo $conta_title;?></span></label>
										<input type="text" id="titulo" name="titulo" class="form-control" value="<?php echo $titulo;?>" maxlength="255" onKeyUp="countChars(this, <?php echo $max_title;?>, 'charNumt')"/>
									</div>		
									<div class="col-md-12">
										<label for="texto" class="mr-sm-2">Texto <span id="charNum" class="contador"><?php echo $conta_description;?></span></label>
										<textarea name="texto" id="texto" class="form-control" onKeyUp="countChars(this, <?php echo $max_description;?>, 'charNum')"><?php echo $texto;?></textarea>
									</div>

									<div class="col-md-12">
										<label for="enlace" class="mr-sm-2">Enlace</label>
										<input type="text" id="enlace" name="enlace" class="form-control" value="<?php echo $enlace;?>" maxlength="255" placeholder="Enlace"/>	
									</div>									
								</div>																										
															

								<div class="row mt-3">
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
					<div class="col-4">
						<div class="card card-body">
							<h4>Enviar a</h4>
							<ul class="categorias">
								<li>
									<div class="radio_group">
										<input type="radio" class="none" name="grupo_envio" id="grupo_todos" value="0" <?php if ($grupo_envio==0) { echo "checked"; }?>>
										<label for="grupo_todos"><i class="mdi mdi-cellphone"></i> TODOS</label>
									</div>									
								</li>
								<li>
									<div class="radio_group">
										<input type="radio" class="none" name="grupo_envio" id="grupo_ios" value="1" <?php if ($grupo_envio==1) { echo "checked"; }?>>
										<label for="grupo_ios"><i class="fab fa-apple"></i> IOS</label>
									</div>									
								</li>	
								<li>
									<div class="radio_group">
										<input type="radio" class="none" name="grupo_envio" id="grupo_android" value="2" <?php if ($grupo_envio==2) { echo "checked"; }?>>
										<label for="grupo_android"><i class="fab fa-android"></i> Android</label>
									</div>									
								</li>	
								<!--
								<li>
									<div class="radio_group">
										<input type="radio" class="none" name="grupo_envio" id="grupo_web" value="3" <?php if ($grupo_envio==3) { echo "checked"; }?>>
										<label for="grupo_web"><i class="fab fa-internet-explorer"></i> Web</label>
									</div>									
								</li>																							
								-->
							</ul>
						</div>
						
					</div>
				</div>
            </div>
        </div>
	</div><!-- End Wrapper -->
</form>

</body>

</html>
