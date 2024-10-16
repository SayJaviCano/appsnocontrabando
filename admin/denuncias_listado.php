<?php
session_start();
$roles = array('0','1','2');
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
elseif (isset( $_POST["pagina"])) { $num_pagina = $_POST["pagina"];  }
else { $num_pagina = 1; }
if(!is_numeric($num_pagina)) { $num_pagina=1; }


if ($num_pagina!=1) { 	$inicio = ($num_pagina - 1) * $TAMANO_PAGINA; }
else { $inicio = 0;  }

$n1=7;

$id = (isset($_GET['id']) ? $_GET['id'] : ""); if ($id==""){  $id = (isset($_POST['id']) ? $_POST['id'] : ""); }
$valor = (isset($_GET['val']) ? $_GET['val'] : ""); if ($valor==""){  $valor = (isset($_POST['val']) ? $_POST['val'] : ""); }
$accion = (isset($_GET['accion']) ? $_GET['accion'] : ""); if ($accion==""){  $accion = (isset($_POST['accion']) ? $_POST['accion'] : ""); }


$fecha_hasta = (isset($_POST['fecha_hasta']) ? $_POST['fecha_hasta'] : ""); 
if ($fecha_hasta=="" || is_null($fecha_hasta)) { $fecha_hasta=date("d/m/Y"); }

$fecha_desde = (isset($_POST['fecha_desde']) ? $_POST['fecha_desde'] : ""); 
if ($fecha_desde=="" || is_null($fecha_desde)) { 
	$array_fecha_hasta = explode("/", $fecha_hasta);
	$fecha_desde = "01/" . $array_fecha_hasta[1] . "/" . $array_fecha_hasta[2];
}

$id_comunidad_autonoma = (isset($_POST['id_comunidad_autonoma']) ? $_POST['id_comunidad_autonoma'] : ""); 
if ($id_comunidad_autonoma=="" || is_null($id_comunidad_autonoma)) { $id_comunidad_autonoma=0; }

$id_provincia = (isset($_POST['id_provincia']) ? $_POST['id_provincia'] : ""); 
if ($id_provincia=="" || is_null($id_provincia)) { $id_provincia=0; }

$localidad = (isset($_POST['localidad']) ? $_POST['localidad'] : ""); 
if ($localidad=="" || is_null($localidad) || $localidad=="Localidad") { $localidad=""; }

$tipo_punto_venta = (isset($_POST['tipo_punto_venta']) ? $_POST['tipo_punto_venta'] : ""); 
if ($tipo_punto_venta=="" || is_null($tipo_punto_venta)) { $tipo_punto_venta=0; }

$procedencia = (isset($_POST['procedencia']) ? $_POST['procedencia'] : ""); 
if ($procedencia=="" || is_null($procedencia)) { $procedencia= 99; }


if ($accion=="borrar") {
	foreach ($_POST["borrar"] as $key => $value) { 
		$valor = str_replace("#", ",", $value);
		$sql = "DELETE FROM punto_venta_denunciado WHERE id=$valor";
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

	<script type="text/javascript" src="../js/comun.js"></script>

	<script type="text/JavaScript">
	<!--		
	dominio = "<?php echo $dominio;?>";
	$(document).ready(function () { 
		$("#id_comunidad_autonoma").change(function () {
			$("#id_comunidad_autonoma option:selected").each(function () {
				elegido=$(this).val();	
				$.post("../includes/ajax_combo_provincia.php", { ca: elegido }, function(data){ $("#id_provincia").html(data);      });     
			});
		});

		<?php if ($id_comunidad_autonoma!=0) { ?>
			elegido=<?php echo $id_comunidad_autonoma; ?>;			
			prov_sel=<?php echo $id_provincia; ?>;
			$.post("../includes/ajax_combo_provincia.php", { ca: elegido, prov_sel: prov_sel }, function(data){ $("#id_provincia").html(data); });     
		<?php } ?>
	});	

	function f_exportar() { 
		document.form1.target="_blank";
		document.form1.action="denuncias_exportar.php";
		document.form1.submit();
	}	


		function filtro() { 
			document.form1.action = "denuncias_listado.php";
			document.form1.submit();
		}
		function f_borrar() {
			var conf = confirm("¿Seguro que desea borrar este/os elemento/s?");
			if(conf==true){
				document.form1.action = "denuncias_listado.php?accion=borrar";
				document.form1.submit()
			}
		}	
		function f_editar(id) { 
			location.href = "denuncias_editar.php?pagina=<?php echo $num_pagina;?>&id=" + id;
		}	
					
	-->
	</script>
</head>
<body>
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
	<input name="pagina" type="hidden" id="pagina" value="<?php echo $pagina;?>">

	<div id="main-wrapper">
		<header class="topbar">
			<nav class="navbar top-navbar navbar-expand-md navbar-dark filtros">
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
					<ul class="navbar-nav float-left mr-auto ml-3">
						<li class="mr-3"><label for="fecha_desde">Desde</label> <input type="text" id="fecha_desde" name="fecha_desde" class="form-control" value="<?php echo $fecha_desde;?>" placeholder="Desde" style="width:120px"/></li>	
						<li class="mr-3"><label for="fecha_hasta">Hasta</label> <input type="text" id="fecha_hasta" name="fecha_hasta" class="form-control" value="<?php echo $fecha_hasta;?>" placeholder="Hasta" style="width:120px"/></li>						
						<li class="mr-3">
							<label for="tipo_punto_venta">Tipos de punto de venta</label>
							<select name="tipo_punto_venta" id="tipo_punto_venta" class="form-control">
								<option value="0">Todos</option>
								<?php
									$sql = "SELECT * FROM tipos_punto_de_venta ORDER BY orden";
									$rs = $mysqli->query($sql); 
									while ($fila = $rs->fetch_array(MYSQLI_ASSOC)){
										$tipo_punto_venta_id = $fila['id'];
										$tipo_punto_ventanombre = $fila['nombre'];
										$activo = "";
										if ($tipo_punto_venta == $tipo_punto_venta_id) { $activo = "selected"; }
										?><option value="<?php echo $tipo_punto_venta_id; ?>" <?php echo $activo; ?>><?php echo $tipo_punto_ventanombre; ?></option><?php
									}
								?>			
							</select>										
						</li>							
						<li class="mr-3">
							<label for="procedencia">Procedencia</label>
							<select name="procedencia" id="procedencia" class="form-control">
								<option value="">Todas</option>
								<option value="0" <?php if ( intval($procedencia) == 0) { echo "selected"; }?>>Área oficinas</option>
								<option value="1" <?php if ( intval($procedencia) == 1) { echo "selected"; }?>>Portal de ventas</option>
								<option value="3" <?php if ( intval($procedencia) == 3) { echo "selected"; }?>>No contrabando</option>
								<option value="4" <?php if ( intval($procedencia) == 4) { echo "selected"; }?>>APP</option>
							</select>							
						</li>
						<li class="mr-3">
							<label for="procedencia">Comunidad autónoma</label>
							<select name="id_comunidad_autonoma" id="id_comunidad_autonoma" class="form-control">
								<option value="0">Todas</option>
								<?php
									$sql = "SELECT * FROM comunidades ORDER BY orden";
									$rs = $mysqli->query($sql); 
									while ($fila = $rs->fetch_array(MYSQLI_ASSOC)){
										$comunidad_id = $fila['id'];
										$comunidad_nombre = $fila['nombre'];
										$activo = "";
										if ($id_comunidad_autonoma == $comunidad_id) { $activo = "selected"; }
										?><option value="<?php echo $comunidad_id; ?>" <?php echo $activo; ?>><?php echo $comunidad_nombre; ?></option><?php
									}
								?>
							</select>
						</li>
						<li class="mr-3">
							<label for="id_provincia">Provincia</label>
							<select name="id_provincia" id="id_provincia" class="form-control">
								<option value="0">Todas</option>
							</select>								
						</li>	
						<li class="mr-3">
							<label for="localidad">Localidad</label>
							<input type="text" id="localidad" name="localidad" class="form-control" value="<?php echo $localidad;?>" maxlength="255" placeholder="Localidad" />
						</li>						

						<li class="mr-3">							
							<button id="btn-filtrar" type="button" class="btn btn-primary" onclick="filtro()" style="margin-top:15px">Filtrar</button>
						</li>
					</ul>

				</div>
			</nav>
		</header>

		<?php include('includes/menu.php');	?>
        <div class="page-wrapper">

            <div class="page-breadcrumb">
                <div class="row">
                    <div class="col-6">
                        <h4 class="page-title">Listado de denuncias</h4>
                    </div>

					<div class="col-6 text-right">
						<button type="button" class="btn btn-primary mt-3 mr-3" onclick="f_exportar()" >Exportar</button>
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
					<th width="100">Fecha Alta</th>
					<th width="300">Comunidad<br>Provincia<br>Localidad</th>
					<th width="300">Tipo<br>Nombre<br>Teléfono</th>
					<th>Comentarios</td>
					<th width="100" align="center">&nbsp;</th>				  
				</tr>
			</thead>

			<tbody>
	<?php


		$sql = "SELECT * FROM punto_venta_denunciado WHERE id<>0";
		if ($fecha_desde!="") { $sql .=  " AND  fecha_alta >= '" . cambia_fecha_a_mysql($fecha_desde) . "'"; }
		if ($fecha_hasta!="") { $sql .=  " AND  fecha_alta <= '" . cambia_fecha_a_mysql($fecha_hasta) . "'"; }
		if ($id_comunidad_autonoma!="0") { $sql .=  " AND  id_comunidad_autonoma=$id_comunidad_autonoma"; }
		if ($id_provincia!="0") { $sql .=  " AND  id_provincia=$id_provincia"; }
		if ($localidad != "") { $sql.= " AND (localidad LIKE '%$localidad%') "; }
		if ($tipo_punto_venta!="0") { $sql .=  " AND  id_tipo_punto_de_venta=$tipo_punto_venta"; }
		if ($procedencia!=99) { $sql .=  " AND  procedencia=$procedencia"; }
		$sql.= " ORDER BY fecha_alta DESC, id DESC";

		//echo "$sql<br>";

		$rs = $mysqli->query($sql); 

		if ($rs->num_rows > 0) {

			while ($fila = $rs->fetch_array(MYSQLI_ASSOC)){
				$id = $fila['id'];		
				$fecha_alta = cambiaf_a_normal($fila['fecha_alta']);
				$fecha_modificacion = $fila['fecha_modificacion'];		
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
				if ($comentarios=="Comentarios") { $comentarios = ""; }
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



				$sql2 = "SELECT * FROM comunidades WHERE id=$id_comunidad_autonoma";
				$rs2 = $mysqli->query($sql2); 
                if ($rs2->num_rows > 0) {
                    $fila2 = $rs2->fetch_assoc();
                    $comunidad = $fila2['nombre'];
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

				
				?>
				<tr scope="row">	
					<td align="center" valign="top"><input type="checkbox" class="none" name="borrar[]" id="borrar[]" value="<?php echo $id;?>"></td>		
					<td><?php echo $fecha_alta?></td>
					<td>
						<?php echo $comunidad?><br>
						<?php echo $provincia?><br>
						<?php echo $localidad?>
					</td>
					<td>
						<?php echo $tipo_punto_de_venta;?><br>
						<?php echo $nombre;?><br>
						<?php echo $telefono_contacto;?>
					</td>		
					<td><?php echo $comentarios?></td>
				  	<td align="center"><button type="button" class="btn btn-primary" onClick="f_editar('<?php echo $id;?>')">Editar</button></td>				  
				</tr>
				<?php			
			}
		} else {?>
				<tr scope="row">
				  <td colspan="8" align="center">No hay ningun registro</td>
				</tr>
		<?php }		?>
			<tr>
				<td colspan="2" class="white"><button type="button" class="btn btn-danger" onClick="f_borrar()">Borrar</button></td>
				<td colspan="5" align="center" class="white"></td>
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