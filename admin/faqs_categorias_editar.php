<?php
session_start();
$roles = array('0','1');
include('includes/segur.php');

$path_relative = "../";
include('../includes/conexion.php');

$n1=5;
$n2=2;

$id = (isset($_GET['id']) ? $_GET['id'] : ""); if ($id==""){  $id = (isset($_POST['id']) ? $_POST['id'] : ""); }
$valor = (isset($_GET['val']) ? $_GET['val'] : ""); if ($valor==""){  $valor = (isset($_POST['val']) ? $_POST['val'] : ""); }
$accion = (isset($_GET['accion']) ? $_GET['accion'] : ""); if ($accion==""){  $accion = (isset($_POST['accion']) ? $_POST['accion'] : ""); }
if ($id==""){ $id =0;}

$aux_n_fich = date("his");

if ($accion=="modificar") {
	$slug = $_POST["slug"];	
	$activo = $_POST["activo"];
	$orden = $_POST["orden"];
	$nombre = ($_POST["nombre"]);

	$sql = "SELECT * FROM faqs_categorias WHERE id=$id"; 
	$rs = $mysqli->query($sql); 
	$num_total_registros = $rs->num_rows; 
	if ($num_total_registros>0) {
		$fila = $rs->fetch_assoc();	
		$sql2 = "Update faqs_categorias SET slug='$slug',activo=$activo, orden=$orden, nombre='$nombre'";	
		$sql2 = $sql2 . " WHERE id=" . $id;
	} else {
		$sql2 = "INSERT INTO faqs_categorias (slug, activo, orden, nombre) VALUES ('$slug', $activo, '$orden', '$nombre')";     

	}
	$rs2 = $mysqli->query($sql2); 
    ?><script>document.location.href="faqs_categorias_listado.php"</script><?php
}

$sql = "SELECT * FROM faqs_categorias WHERE id=" . $id; 
$rs = $mysqli->query($sql); 
$num_cursos = $rs->num_rows; 
if ($num_cursos > 0) {
	$fila = $rs->fetch_assoc();
	$slug = $fila['slug'];
	$activo = $fila['activo'];
	$orden = $fila['orden'];
	$nombre_categoria = $fila['nombre'];
} else {
	$sql = "SELECT * FROM faqs_categorias ORDER BY orden DESC LIMIT 1"; 
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
		$("#nombre").blur(function() {
			slug = document.getElementById("slug").value;
			nombre_categoria = document.getElementById("nombre").value;
			if (slug=="") { carga_categoria_slug(nombre_categoria); }	
		});		
	});

	function carga_categoria_slug(nombre_categoria) {
		$.ajax({
		type: "POST",
		url: 'ajax_faqs_categoria_slug.php', 
		data: { p: nombre_categoria, id: '<?php echo $id;?>' },
		success: function(data) {
			document.getElementById("slug").value = data;
		}
		}).fail( function( jqXHR, textStatus, errorThrown ) {
			alert(errorThrown );
		});
	}	


	function f_volver() { 
		location.href="faqs_categorias_listado.php";
	}
	function verificar(){
		var ok=true;
	

		if (document.getElementById("slug").value=="" && ok!=false){
			ok=false;
			$(".error").html("El campo <b>URL</b> es obligatorio");
			document.getElementById("slug").focus();
		}			
		//---------------------------------------------------------------------				
		if (ok){		
			document.form1.action="faqs_categorias_editar.php";
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
						
					</ul>
				</div>
			</nav>
		</header>

		<?php include('includes/menu.php'); ?>
        <div class="page-wrapper">

            <div class="page-breadcrumb">
                <div class="row">
                    <div class="col">
                        <h4 class="page-title">Faq Categoría</h4>
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
										<label for="orden" class="mr-sm-2">Orden</label>
										<input type="text" id="orden" name="orden" class="form-control" value="<?php echo $orden;?>" maxlength="255"/>
									</div>
								</div>

								<div class="row">
									<div class="col-md-6">
										<label for="nombre" class="mr-sm-2">Categoría</label>
										<input type="text" id="nombre" name="nombre" class="form-control" value="<?php echo $nombre_categoria;?>" maxlength="255"/>
									</div>
									<div class="col-md-6">
										<label for="slug" class="mr-sm-2">Url</label>
										<input type="text" id="slug" name="slug" class="form-control" value="<?php echo $slug;?>" maxlength="255" />
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
