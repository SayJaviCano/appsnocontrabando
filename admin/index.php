<?php
session_start();
$path_relative="../";
include('../includes/conexion.php');


if ($_SERVER['SERVER_NAME'] == "appsnocontrabando.com") { 
	header("Location: https://nocontrabando.altadis.com/admin/");
	die();
}


$usuario = "";
$clave ="";

if (isset($_POST['usuario'])) {
	$usuario = LimpiaParametros($_POST["usuario"]); 
	$clave = LimpiaParametros($_POST["clave"]); 
}

$error = "SI";
$url_redirect = "inicio.php";
if ($usuario != ""){ 
	

	$acceso_clave = $clave;
	$acceso_clave = encrypt($acceso_clave ,$clave_crip);

	$sql = "SELECT * FROM administradores WHERE activo=1 AND (email='$usuario' AND clave='$acceso_clave')";
	//echo "<br><br>$sql<br><br><br>";
	if (!$rs = $mysqli->query($sql)) {
		echo "Lo sentimos, este sitio web está experimentando problemas.";
	} else { 
		if ($rs->num_rows > 0) {
			$fila = $rs->fetch_assoc();
			$nivel = $fila['nivel'];
			if ($nivel=="0")	{ $tx_nivel = "Administrador Total";}

			$_SESSION['sess_tx_nivel'] = $tx_nivel;
			$_SESSION['sess_admin_id'] = $fila['id'];
			$_SESSION['sess_admin_nivel'] = $nivel;
			$_SESSION['sess_admin_login'] = "true";
			$_SESSION['sess_admin_nombre'] = $fila['nombre'];
			$error="NO";

			if ($_SESSION['sess_admin_nivel'] == 2) { $url_redirect = "denuncias_listado.php"; }
		}
	}

}
if ($error == "NO"){?>
	<script>location.href="<?php echo $url_redirect;?>";</script>
<?php } else {?>

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
</head>


<body id="login">
    <div class="preloader">
        <div class="lds-ripple">
            <div class="lds-pos"></div>
            <div class="lds-pos"></div>
        </div>
    </div>
	<div id="main-wrapper">
								
<div class="auth-wrapper d-flex no-block justify-content-center align-items-center">
	<div class="auth-box">
		<div id="loginform">
			<div class="logo">
				<div class="db"><a href="<?php echo $dominio;?>"><img src="images/logo.jpg" class="img-fluid"/></a></div>
			</div>
			<!-- Form -->
			<div class="row">
				<div class="col-12">
					<form method="post" name="form-login" class="form-horizontal m-t-20" id="form-login">

						<div class="input-group mb-3">
							<div class="input-group-prepend">
								<span class="input-group-text" id="basic-addon1"><i class="ti-user"></i></span>
							</div>
							<input name="usuario" type="text" maxlength="50" class="form-control form-control-lg" placeholder="Escribe tu E-Mail" aria-label="Escribe tu E-Mail" aria-describedby="basic-addon1" required value="" >
						</div>
						<div class="input-group mb-3">
							<div class="input-group-prepend">
								<span class="input-group-text" id="basic-addon2"><i class="ti-pencil"></i></span>
							</div>
							<input name="clave" type="password" maxlength="50" class="form-control form-control-lg" placeholder="Escribe tu Contraseña" aria-label="Escribe tu Contraseña" aria-describedby="basic-addon1" required>
						</div>
						<div class="form-group text-center">
							<div class="col-xs-12 p-b-20">
								<button class="btn btn-primary btn-lg w100" type="submit">Entrar</button>
								<p class="error text-center">&nbsp;</p>
							</div>
						</div>

						
					</form>
				</div>
			</div>
		</div>

	</div>
</div>


</div>    <!-- End Wrapper -->
	

</body>

</html>
<?php }?>