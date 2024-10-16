<?php
session_start();
$roles = array('0','1');
include('includes/segur.php');

$path_relative = "../";
include('../includes/conexion.php');

if (isset( $_GET["regPagina"])) { $TAMANO_PAGINA = $_GET["regPagina"];  } 
else { $TAMANO_PAGINA = 0; }
if (!isset($_SESSION['sess_TAMANO_PAGINA'])) { $TAMANO_PAGINA = 25;  } 

if ($TAMANO_PAGINA==0) {
	$TAMANO_PAGINA = $_SESSION['sess_TAMANO_PAGINA'];
	if ($TAMANO_PAGINA==0) { $TAMANO_PAGINA = 25; }
}else {
	$_SESSION['sess_TAMANO_PAGINA'] = $TAMANO_PAGINA;
}

if (isset( $_GET["pagina"])) { $num_pagina = $_GET["pagina"];  }
else {$num_pagina = 1; }
if ($num_pagina!=1) { 	$inicio = ($num_pagina - 1) * $TAMANO_PAGINA; }
else { $inicio = 0;  }


$n1=3;
$n2=2;

$id = (isset($_GET['id']) ? $_GET['id'] : ""); if ($id==""){  $id = (isset($_POST['id']) ? $_POST['id'] : ""); }
$valor = (isset($_GET['val']) ? $_GET['val'] : ""); if ($valor==""){  $valor = (isset($_POST['val']) ? $_POST['val'] : ""); }
$accion = (isset($_GET['accion']) ? $_GET['accion'] : ""); if ($accion==""){  $accion = (isset($_POST['accion']) ? $_POST['accion'] : ""); }


$uploadDir = "../images/banner/";

if ($accion=="activar") {
	$sql2 = "Update banner_home SET activo=" . $valor . " WHERE id=" . $id;
	$rs2 = $mysqli->query($sql2); 
}

if ($accion=="borrar") {
	foreach ($_POST["borrar"] as $key => $value) { 
		$valor = str_replace("#", ",", $value);

		$sql = "SELECT * FROM banner_home WHERE id=$valor"; 
		$rs = $mysqli->query($sql); 
		if ($rs->num_rows>0) {
			$fila = $rs->fetch_assoc();	
			$old_archivo = ($fila['archivo']);
			if ($old_archivo!="") {
				$archivo_a_borrar = $uploadDir . $old_archivo; 
				if (file_exists($archivo_a_borrar)) { unlink($archivo_a_borrar); }	
			}
		}
		$sql = "DELETE FROM banner_home WHERE id=$valor";
		$rs = $mysqli->query($sql);
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

	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta/dist/css/bootstrap-select.min.css">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta/dist/js/bootstrap-select.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta/dist/js/i18n/defaults-*.min.js"></script>

	<script type="text/javascript" src="../js/comun.js"></script>

	<script type="text/JavaScript">
	<!--		
	dominio = "<?php echo $dominio;?>";

		
	function f_borrar() {
			var conf = confirm("¿Seguro que desea borrar este/os elemento/s?");
			if(conf==true){
				document.form1.action = "banner_listado.php?accion=borrar";
				document.form1.submit()
			}
		}	
		function f_editar(id) { 
			location.href = "banner_editar.php?id=" + id;
		}						
	-->
	</script>
</head>

<div class="preloader">
	<div class="lds-ripple">
		<div class="lds-pos"></div>
		<div class="lds-pos"></div>
	</div>
</div>

<form name="form1" id="form1" method="post">
	<input name="accion" type="hidden" id="accion">
	<input name="aux_borrar" type="hidden" id="aux_borrar">
	<input name="id" type="hidden" id="id" value="">

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
						<li class="nav-item mr-3"> <button type="button" class="btn btn-primary" onclick="f_editar('')">Nuevo</button></li>
					</ul>
				</div>
			</nav>
		</header>

		<?php include('includes/menu.php');	?>
        <div class="page-wrapper">

            <div class="page-breadcrumb">
                <div class="row">
                    <div class="col">
                        <h4 class="page-title">Banner Home</h4>
                    </div>
                </div>
            </div>

            <div class="container-fluid">
				<div class="row">
                    <div class="col card card-body">
					<!-- CONTENT -->


		<!-- Table  -->
		<table class="table table-striped">

			<thead class="grey lighten-2">
				<tr scope="row">		
					<th width="50" align="center"></th>		
					<th width="70"  align="center">Activo</th>							
					<th width="200">Banner</th>
					<th>Texto</th>
					<th width="100" align="center"></th>							  
				</tr>
			</thead>

			<tbody>
	<?php
		$sql = "SELECT * FROM banner_home ORDER BY activo, id";
		$rs = $mysqli->query($sql); 
		$num_total_registros = $rs->num_rows;  
		$total_paginas = ceil($num_total_registros / $TAMANO_PAGINA); 
		$sql = $sql . " limit " . $inicio . "," . $TAMANO_PAGINA;
		$rs = $mysqli->query($sql); 

		if ($rs->num_rows > 0) {

			while ($fila = $rs->fetch_array(MYSQLI_ASSOC)){
				$id = $fila['id'];	
				$archivo = $fila['archivo'];	
				$texto = $fila['texto'];
				$enlace = $fila["enlace"];

				$activo = $fila['activo'];
				if ($activo=="1")	{ $img="ico_green.gif";	$valor="0"; } 
				else				{ $img="ico_red.gif";	$valor="1";	}	
				
				$pos = strpos($enlace, "http");
				if ($pos === false) { $enlace = $dominio . $enlace; }
				
				?>
				<tr scope="row">	
					<td align="center" valign="top"><input type="checkbox" class="none" name="borrar[]" id="borrar[]" value="<?php echo $id;?>"></td>		
					<td align="center"><a href="banner_listado.php?id=<?php echo $id?>&val=<?php echo $valor?>&accion=activar"><img src="images/<?php echo $img?>"></a></td>
				  	<td align="center"><a href="<?php echo $enlace;?>" title="<?php echo $texto;?>" target="_blank"><img src="<?php echo $dominio."images/banner/$archivo";?>" class="img-fluid" alt="<?php echo $texto;?>" width="250"></a></td>
					<td><?php echo $texto;?><br><a href="<?php echo $enlace;?>" title="<?php echo $texto;?>" target="_blank"><?php echo $enlace;?></a></td>
				  	<td align="center"><button type="button" class="btn btn-primary" onClick="f_editar('<?php echo $id;?>')">Editar</button></td>				  
				</tr>
				<?php			
			}
		} else { ?>
				<tr scope="row">
				  <td colspan="8" align="center">No hay ningun registro</td>
				</tr>
		<?php }	
		
		$url = "banner_listado.php";

		?>
			<tr>
				<td colspan="2" class="white"><button type="button" class="btn btn-danger" onClick="f_borrar()">Borrar</button></td>
				<td colspan="5" align="center" class="white"><?php include('includes/paginacion.php');?></td>
			</tr>	
		</tbody>
		<!-- Table body -->
	  </table>  
	  <!-- Table  -->

					<!-- CONTENT -->
                    </div>
				</div>
            </div>
        </div>
	</div><!-- End Wrapper -->
</form>

</body>

</html>