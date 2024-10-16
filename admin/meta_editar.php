<?php
session_start();
$roles = array('0','1');
include('includes/segur.php');

$path_relative = "../";
include('../includes/conexion.php');



$max_title = 70;
$max_description = 160;
$conta_description = 0; 

$slug = (isset($_GET['slug']) ? $_GET['slug'] : ""); if ($slug==""){  $slug = (isset($_POST['slug']) ? $_POST['slug'] : ""); }
$accion = (isset($_GET['accion']) ? $_GET['accion'] : ""); if ($accion==""){  $accion = (isset($_POST['accion']) ? $_POST['accion'] : ""); }

$n1=8;

$_SESSION['dir_img_editor'] = "../images/content/";
$uploadDir = $_SESSION['dir_img_editor'];

$aux_n_fich = date("his");

if ($accion=="modificar") {
	$activo = $_POST["activo"];
	$slug = $_POST["slug"];
	$meta_title = ($_POST["meta_title"]);	
	$meta_description = ($_POST["meta_description"]);
	$titulo = ($_POST["titulo"]);	
	$entradilla = ($_POST["entradilla"]);
	$content = "";

	$sql = "SELECT * FROM paginas WHERE slug='$slug'"; 
	$rs = $mysqli->query($sql); 
	$num_total_registros = $rs->num_rows; 
	if ($num_total_registros>0) {
		$sql2 = "Update paginas SET activo=$activo, slug='$slug', meta_title='$meta_title', meta_description='$meta_description', titulo='$titulo', entradilla='$entradilla', content='$content'";
		$sql2 = $sql2 . " WHERE slug='$slug'"; 
	} else {
		$sql2 = "INSERT INTO paginas (activo, slug, meta_title, meta_description, titulo, entradilla, content) VALUES ($activo, '$slug', '$meta_title', '$meta_description', '$titulo', '$entradilla', '$content')";     
	}
	$rs2 = $mysqli->query($sql2);
}


$sql = "SELECT * FROM paginas WHERE slug='$slug'"; 
$rs = $mysqli->query($sql); 
$num_cursos = $rs->num_rows; 
if ($num_cursos > 0) {
	$fila = $rs->fetch_assoc();
	$activo = $fila['activo'];
	$slug = $fila['slug'];
	$meta_title = ($fila['meta_title']);
	$meta_description = ($fila['meta_description']);

	$titulo = $fila['titulo'];	
	$entradilla = $fila['entradilla'];
	$content = $fila['content'];	
} 

$conta_title = $max_title - strlen($meta_title);
if ($conta_title < 0) { $conta_title= '<span class="red">' . $conta_title . '</span>';}

$conta_description = $max_description - strlen($meta_description);
if ($conta_description < 0) { $conta_description= '<span class="red">' . $conta_description . '</span>';}
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

	function verificar(){
		var ok=true;

		if (document.getElementById("slug").value=="" && ok!=false){
			ok=false;
			$(".error").html("El campo <b>URL</b> es obligatorio");
			document.getElementById("slug").focus();
		}		

		//---------------------------------------------------------------------				
		if (ok){		
			document.form1.action="meta_editar.php";
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
                        <h4 class="page-title">Página <?php echo "<b>$titulo</b>";?></h4>
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
									<div class="col-md-4">
										<label for="slug" class="mr-sm-2">Url</label>
										<input type="text" id="slug" name="slug" class="form-control" value="<?php echo $slug;?>" maxlength="255" readonly/>
									</div>									
								</div>
								<div class="row">	
									<div class="col-md-12">
										<label for="meta_title" class="mr-sm-2">Meta Title <span id="charNumt" class="contador"><?php echo $conta_title;?></span></label>
										<input type="text" id="meta_title" name="meta_title" class="form-control" value="<?php echo $meta_title;?>" maxlength="255" onKeyUp="countChars(this, <?php echo $max_title;?>, 'charNumt')"/>
									</div>		
									<div class="col-md-12">
										<label for="meta_description" class="mr-sm-2">Meta Description <span id="charNum" class="contador"><?php echo $conta_description;?></span></label>
										<textarea name="meta_description" id="meta_description" class="form-control" onKeyUp="countChars(this, <?php echo $max_description;?>, 'charNum')"><?php echo $meta_description;?></textarea>
									</div>																																
								</div>	

								<div class="row">
									<div class="col-md-12">
										<label for="nombre" class="mr-sm-2">Título</label>
										<input type="text" id="titulo" name="titulo" class="form-control" value="<?php echo $titulo;?>" maxlength="255"/>
									</div>
									<div class="col-md-12">
										<label for="entradilla" class="mr-sm-2">Entradilla</label>
										<textarea name="entradilla" id="entradilla" class="form-control"><?php echo $entradilla;?></textarea>
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
