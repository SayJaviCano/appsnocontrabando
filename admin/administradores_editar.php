<?php
session_start();
$roles = array('0','1');
include('includes/segur.php');

$path_relative = "../";
include('../includes/conexion.php');

$n1=2;
$n2=1;

$id = (isset($_GET['id']) ? $_GET['id'] : ""); if ($id==""){  $id = (isset($_POST['id']) ? $_POST['id'] : ""); }
$valor = (isset($_GET['val']) ? $_GET['val'] : ""); if ($valor==""){  $valor = (isset($_POST['val']) ? $_POST['val'] : ""); }
$accion = (isset($_GET['accion']) ? $_GET['accion'] : ""); if ($accion==""){  $accion = (isset($_POST['accion']) ? $_POST['accion'] : ""); }
if ($id==""){ $id =0;}


$aux_n_fich = date("his");

if ($accion=="modificar") {
	$activo = $_POST["activo"];
	$nombre = $_POST["nombre"];
	$apellidos = $_POST["apellidos"];
	$email = $_POST["adm_usuario"];
	$clave = $_POST["adm_clave"];
	$clave_encript = encrypt($clave ,$clave_crip);
	$nivel = $_POST["nivel"];;

	$sql = "SELECT * FROM administradores WHERE id=$id"; 
	$rs = $mysqli->query($sql); 
	$num_total_registros = $rs->num_rows; 
	if ($num_total_registros>0) {	
		$sql2 = "Update administradores SET activo=$activo, nombre='$nombre', apellidos='$apellidos', email='$email', clave='$clave_encript', nivel='$nivel'";	
		$sql2 = $sql2 . " WHERE id=" . $id;		
	} else {
		$sql2 = "INSERT INTO administradores (activo, nombre, apellidos, email, clave, nivel) VALUES ($activo, '$nombre', '$apellidos', '$email', '$clave_encript', '$nivel')";     
	}
	$rs2 = $mysqli->query($sql2); 

	//echo "$sql2<br>";
    ?><script>document.location.href="administradores_listado.php"</script><?php
}


$adm_email = "";
$clave_decript = "";

$sql = "SELECT * FROM administradores WHERE id=$id AND id<>1"; 
$rs = $mysqli->query($sql); 
$num_cursos = $rs->num_rows; 
if ($num_cursos > 0) {
    $fila = $rs->fetch_assoc();
    $activo = $fila['activo'];
	$adm_nombre = $fila['nombre'];
	$adm_apellidos = $fila['apellidos'];
	$adm_email = $fila['email'];
	$clave = $fila['clave'];
	$clave_decript = decrypt($clave ,$clave_crip);
	$nivel = $fila['nivel'];
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
		//CheckBox mostrar contraseña
		$('#ShowPassword').click(function () {
			$('#adm_clave').attr('type', $(this).is(':checked') ? 'text' : 'password');
		});
	});

	function mostrarPassword(){
		var cambio = document.getElementById("adm_clave");
		if(cambio.type == "password"){
			cambio.type = "text";
			$('.icon').removeClass('fa fa-eye-slash').addClass('fa fa-eye');
		}else{
			cambio.type = "password";
			$('.icon').removeClass('fa fa-eye').addClass('fa fa-eye-slash');
		}
	} 

	function f_volver() { 
		location.href="administradores_listado.php";
	}

	function verificar(){
		var ok=true;

		if (document.getElementById("adm_usuario").value == "" && ok!=false)	{	
			alert("El Usuario (email) es obligatorio");
			document.getElementById("adm_usuario").focus();
		}	
		//---------------------------------------------------------------------				
		if (ok){		
			document.form1.action="administradores_editar.php";
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

<form method="post" enctype="multipart/form-data" name="form1" autocomplete="off">
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
						<li class="nav-item"><button type="button" class='btn btn-info' onclick="f_volver()"><i class="fas fa-arrow-left"></i> &nbsp;Volver</button></li>
					</ul>
				</div>
			</nav>
		</header>

		<?php include('includes/menu.php'); ?>
        <div class="page-wrapper">

            <div class="page-breadcrumb">
                <div class="row">
                    <div class="col">
                        <h4 class="page-title">Administradores</h4>
                    </div>
                </div>
            </div>

            <div class="container-fluid">
				<div class="row">
                    <div class="col card card-body">
					<!-- CONTENT -->

					<div class="row">
							<div class="col-md-12">
								<button type="button" class="btn btn-primary" onClick="verificar()">Guardar</button>
								<div class="clearfix">&nbsp;</div>
							</div>							
						</div>

						<div class="row">
							<div class="col-md-2">
								<label for="activo" class="mr-sm-2">Activo</label>
								<select name="activo" id="activo" class="form-control mr-sm-5">
									<option value="1" <?php if($activo=="1") { echo "selected"; } ?>>SI</option>
									<option value="0" <?php if($activo=="0") { echo "selected"; } ?>>NO</option>
								</select>
							</div>
							
							<div class="col-md-2">
								<label for="nivel" class="mr-sm-2">Perfil</label>
								<select name="nivel" id="nivel" class="form-control">
									<?php if ($_SESSION['sess_admin_nivel']==0) { ?><option value="0" <?php if($nivel=="0") { echo "selected"; } ?>>Administrador Total</option><?php } ?>
									<option value="1" <?php if($nivel=="1") { echo "selected"; } ?>>Administrador</option>
									<option value="2" <?php if($nivel=="2") { echo "selected"; } ?>>Gestor denuncias</option>
								</select>	
							</div>	
							
						</div>
						<div class="row">					
							<div class="clearfix">&nbsp;</div>
									
							<div class="col-md-4">
								<label for="nombre" class="mr-sm-2">Nombre</label>
								<input type="text" id="nombre" name="nombre" class="form-control" value="<?php echo $adm_nombre;?>" maxlength="255" />
							</div>
							<div class="col-md-4">
								<label for="apellidos" class="mr-sm-2">Apellidos</label>
								<input type="text" id="apellidos" name="apellidos" class="form-control" value="<?php echo $adm_apellidos;?>" maxlength="255" />
							</div>
							<div class="clearfix">&nbsp;</div>


							<div class="col-md-4">
								<label for="adm_usuario" class="mr-sm-2">E-Mail usuario</label>							
								<input type="text" id="adm_usuario" name="adm_usuario" class="form-control" value="<?php echo $adm_email;?>" maxlength="255"/>							
							</div>
							<div class="col-md-4">
								<label for="clave" class="mr-sm-2">Clave</label>
								<div class="input-group">
									<input type="password" id="adm_clave" name="adm_clave" class="form-control" value="<?php echo $clave_decript;?>" maxlength="255"/>		
									<div class="input-group-append">
										<button id="show_password" class="btn btn-primary" type="button" onclick="mostrarPassword()"> <span class="fa fa-eye-slash icon"></span> </button>
									</div>
								</div>		
							</div>													
						</div>

						<div class="row">
							<div class="col-md-12">
								<div class="clearfix">&nbsp;</div>
								<button type="button" class="btn btn-primary" onClick="verificar()">Guardar</button>
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
