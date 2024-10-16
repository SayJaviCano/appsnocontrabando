<?php
session_start();
$roles = array('0','1');
include('includes/segur.php');

$path_relative = "../";
include('../includes/conexion.php');

$id = (isset($_GET['id']) ? $_GET['id'] : ""); if ($id==""){  $id = (isset($_POST['id']) ? $_POST['id'] : ""); }
if ($id==""){ $id =0;}
$accion = (isset($_GET['accion']) ? $_GET['accion'] : ""); if ($accion==""){  $accion = (isset($_POST['accion']) ? $_POST['accion'] : ""); }

$n1=3;
$n2=2;
$max_ancho = 410;

$uploadDir = "../images/banner/";
$aux_n_fich = date("his");

if ($accion=="modificar") {
	$activo = $_POST["activo"];
	$texto = $_POST["texto"];
	$enlace = $_POST["enlace"];
	
	$nombre_archivo = ($_FILES['archivo']['name']);
	$tipo_archivo = $_FILES['archivo']['type']; 
	$arr_extension = explode(".",$nombre_archivo);
	$num = count($arr_extension)-1;
	$extension = strtolower($arr_extension[$num]);
	$aux_ext = "." . $extension;
	$aux_nom = trim(str_replace ($aux_ext, "", "$nombre_archivo")); 
	$file = $aux_nom . "_" . $aux_n_fich . "." . $extension;
	$file = limpia_cadena($file) ;
	$uploadFile = $uploadDir . $file;
	$imagen_new = "";
	if (move_uploaded_file($_FILES['archivo']['tmp_name'], $uploadFile)) { 
		$imagen_new=$file;
		$att=chmod($uploadFile,0644);	
		
		set_time_limit(600);
        if ($extension=="jpg" || $extension=="gif" || $extension=="png") {
            f_optim_img($uploadDir, $file, $max_ancho);
        }
	}

	$sql = "SELECT * FROM banner_home WHERE id='$id'"; 
	$rs = $mysqli->query($sql); 
	$num_total_registros = $rs->num_rows; 
	if ($num_total_registros>0) {
		$fila = $rs->fetch_assoc();	
		$id_contenido = $fila['id'];
		$old_imagen = ($fila['archivo']);

		$sql2 = "Update banner_home SET activo=$activo, texto='$texto', enlace='$enlace'";
		if ($imagen_new!="") {		
			$sql2 .= ", archivo='$imagen_new'";
			if ($old_imagen!="") {
				$archivo_a_borrar = $uploadDir . $old_imagen; 
				if (file_exists($archivo_a_borrar)) { unlink($archivo_a_borrar); }	
			}
		}		
		$sql2 = $sql2 . " WHERE id='$id'"; 
		$rs2 = $mysqli->query($sql2);

	} else {
		$sql2 = "INSERT INTO banner_home (activo, texto, enlace";
		if ($imagen_new!="") { $sql2 .= ", archivo"; 	}
		$sql2 .= ") VALUES ($activo, '$texto', '$enlace'";
		if ($imagen_new!="") { 
			$sql2 .= ", '$imagen_new'";
		}
		$sql2 .= ")";     
		$rs2 = $mysqli->query($sql2);
	}	
	//echo "<br>$sql2<br>";

	?><script>document.location.href="banner_listado.php"</script><?php
	die();
}

$page_title = "Nuevo Banner";
$fecha = date("Y-m-d H:i:s");
$sql = "SELECT * FROM banner_home WHERE id='$id'"; 
$rs = $mysqli->query($sql); 
if ($rs->num_rows > 0) {
	$fila = $rs->fetch_assoc();
	$activo = $fila['activo'];
	$texto = $fila['texto'];
	$enlace = $fila["enlace"];
	$archivo = $fila["archivo"];	
	$page_title = "Editar Banner";
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

	<link rel="stylesheet" href="dist/css/bootstrap-tagsinput.css">
	<script src="dist/js/bootstrap-tagsinput.js"></script>

	
	<script>var root_path = "";</script>
	<script type="text/javascript" src="ckeditor/ckeditor.js?<?php echo time();?>" charset="utf-8"></script>  	

	<script type="text/javascript" src="../js/comun.js"></script>

	<script language="JavaScript">
	<!--
	$(document).ready(function () { 

	});

	function verificar(){
		var ok=true;

		if (document.getElementById("texto").value=="" && ok!=false){
			ok=false;
			$(".error").html("El campo <b>texto</b> es obligatorio");
			document.getElementById("post_name").focus();
		}		

		//---------------------------------------------------------------------				
		if (ok){		
			document.form1.action="banner_editar.php";
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
	<input name="id" type="hidden" id="id" value="<?php echo $id;?>">
	
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
                    <div class="col-12 card card-body">
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
									<div class="col-md-10">
										<label for="slug" class="mr-sm-2">Enlace</label>
										<input type="text" id="enlace" name="enlace" class="form-control" value="<?php echo $enlace;?>" maxlength="255"/>
									</div>									
								</div>

								<div class="row">
									<div class="col-md-12">
										<label for="nombre" class="mr-sm-2">Texto</label>
										<input type="text" id="texto" name="texto" class="form-control" value="<?php echo $texto;?>" maxlength="255"/>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12 mt-3">
										<label for="archivo" class="mr-sm-2">Imagen <span class="texto_p">JPG, GIF, PNG: 410 x alto</span></label>
										<input name="archivo" type="file" id="archivo" class="form-control" accept=".jpg, .gif, .png">
										<?php if ($archivo!="") { ?>
											<img src="<?php echo $uploadDir. $archivo;?>" class="img-fluid">
										<?php } ?>	
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

				</div>
            </div>
        </div>
	</div><!-- End Wrapper -->
</form>

</body>

</html>
