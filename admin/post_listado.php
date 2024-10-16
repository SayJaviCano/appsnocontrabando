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
elseif (isset( $_POST["pagina"])) { $num_pagina = $_POST["pagina"];  }
else { $num_pagina = 1; }
if(!is_numeric($num_pagina)) { $num_pagina=1; }


if ($num_pagina!=1) { 	$inicio = ($num_pagina - 1) * $TAMANO_PAGINA; }
else { $inicio = 0;  }

$n1=8;
$n2=1;

$id = (isset($_GET['id']) ? $_GET['id'] : ""); if ($id==""){  $id = (isset($_POST['id']) ? $_POST['id'] : ""); }
$valor = (isset($_GET['val']) ? $_GET['val'] : ""); if ($valor==""){  $valor = (isset($_POST['val']) ? $_POST['val'] : ""); }
$accion = (isset($_GET['accion']) ? $_GET['accion'] : ""); if ($accion==""){  $accion = (isset($_POST['accion']) ? $_POST['accion'] : ""); }
$buscar = (isset($_GET['buscar']) ? $_GET['buscar'] : ""); if ($buscar==""){  $buscar = (isset($_POST['buscar']) ? $_POST['buscar'] : ""); }

$categoria_select = (isset($_POST['cat']) ? $_POST['cat'] : ""); if ($categoria_select==""){  $categoria_select = (isset($_GET['cat']) ? $_GET['cat'] : ""); }
if(!is_numeric($categoria_select)) { $categoria_select=0; }

$congresos_categoria_select = (isset($_POST['congresos_cat']) ? $_POST['congresos_cat'] : ""); if ($congresos_categoria_select==""){  $congresos_categoria_select = (isset($_GET['congresos_cat']) ? $_GET['congresos_cat'] : ""); }
if(!is_numeric($congresos_categoria_select)) { $congresos_categoria_select=0; }


$uploadDir = "../";
if ($accion=="activar") {
	$sql2 = "Update contenidos SET activo=" . $valor . " WHERE id=" . $id;
	$rs2 = $mysqli->query($sql2); 
	getSiteMap();
}

if ($accion=="destacado_video") {

	$sql2 = "Update contenidos SET destacado_video=0 WHERE destacado_video=1";
	$rs2 = $mysqli->query($sql2); 
	
	$sql2 = "Update contenidos SET destacado_video=" . $valor . " WHERE id=" . $id;
	$rs2 = $mysqli->query($sql2); 
}

if ($accion=="destacado_img") {
	$sql2 = "Update contenidos SET destacado_img=" . $valor . " WHERE id=" . $id;
	$rs2 = $mysqli->query($sql2); 
}

if ($accion=="modificar") {
	$sql = "SELECT contenidos.id, contenidos.activo, contenidos.destacado, contenidos.fecha, contenidos.post_name, contenidos.titulo, contenidos.entradilla, contenidos.texto, contenidos.imagen, contenidos.imagen_pie, contenidos.video, contenidos.tags, contenidos_categorias.id_categoria FROM contenidos";		
	$sql.= " INNER JOIN contenidos_categorias ON contenidos_categorias.id_contenido = contenidos.id";		
	$sql.= " WHERE contenidos.tipo='post'";
	if ($buscar != "") { $sql.= " AND (contenidos.titulo LIKE '%$buscar%' OR contenidos.entradilla LIKE '%$buscar%' OR contenidos.texto LIKE '%$buscar%') OR contenidos.fecha='$buscar'"; }
	if ($categoria_select != "0") { 
		$sql.= " AND contenidos_categorias.id_categoria=$categoria_select";
	}
	$sql.= " GROUP BY contenidos.id";		
	$sql.= " ORDER BY contenidos.destacado, contenidos.fecha DESC, contenidos.id DESC";
	$sql.= " limit " . $inicio . "," . $TAMANO_PAGINA;

	$rs = $mysqli->query($sql); 
	while ($fila = $rs->fetch_array(MYSQLI_ASSOC)){
		$id = $fila['id'];	
		$destacado = $_POST["posicion_".$id];		

        if (is_numeric($destacado)) {
			if ($destacado==0) { $destacado = 99999;}
            $sql2 = "Update contenidos SET destacado=" .$destacado. " Where id=" . $id;
            $rs2 = $mysqli->query($sql2);
        }
	}
}

if ($accion=="borrar") {
	foreach ($_POST["borrar"] as $key => $value) { 
		$valor = str_replace("#", ",", $value);

		$sql = "SELECT * FROM contenidos WHERE id=$valor"; 
		$rs = $mysqli->query($sql); 
		if ($rs->num_rows>0) {
			$fila = $rs->fetch_assoc();	
			$old_archivo = ($fila['imagen']);
			if ($old_archivo!="") {
				$archivo_a_borrar = $uploadDir . $old_archivo; 
				if (file_exists($archivo_a_borrar)) { unlink($archivo_a_borrar); }	
			}
		}

		$sql = "DELETE FROM contenidos WHERE id=$valor";
		$rs = $mysqli->query($sql);
	} 
	getSiteMap();
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

		function filtro() { 
			document.form1.submit();
		}			
		function f_guardar() { 
			document.getElementById('accion').value="modificar";
			document.form1.action="post_listado.php";
			document.form1.submit();
		}	

		function f_borrar() {
			var conf = confirm("¿Seguro que desea borrar este/os elemento/s?");
			if(conf==true){
				document.form1.action = "post_listado.php?accion=borrar";
				document.form1.submit()
			}
		}	
		function f_editar(id) { 
			location.href = "post_editar.php?cat=<?php echo $categoria_select;?>&pagina=<?php echo $num_pagina;?>&id=" + id;
		}	
		function f_ver(post_name) { 
			window.open(dominio + post_name + "/");
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
	<input name="pagina" type="hidden" id="pagina" value="<?php echo $pagina;?>">

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
						<li class="mr-3">
							<select id="cat" name="cat" onchange="filtro()" class="custom-select" data-live-search="true" title="Selecciona Categoría" data-width="400px" >
								<option value="0">Todas las categorías</option>
								<?php
									$sql = "SELECT * FROM categorias ORDER BY orden, nombre";
									$rs = $mysqli->query($sql); 
									while ($fila = $rs->fetch_array(MYSQLI_ASSOC)){
										$activo = "";
										if ($categoria_select == $fila['id']) { $activo = "selected"; }
										echo '<option value="' . $fila['id'] .'" ' . $activo . '>' . $fila['nombre'] .'</option>';
									}
								?>
							</select>				
						</li>	
						
						<?php if ($categoria_select=="13") {  /********* CONGRESOS *****************/ ?>
							<li class="mr-3">
								<select id="congresos_cat" name="congresos_cat" onchange="filtro()" class="custom-select" data-live-search="true" title="Selecciona Congreso" data-width="400px" >
									<option value="0">Todos los congresos</option>
									<?php
										$sql = "SELECT * FROM congresos_categorias ORDER BY orden DESC, nombre";
										$rs = $mysqli->query($sql); 
										while ($fila = $rs->fetch_array(MYSQLI_ASSOC)){
											$activo = "";
											if ($congresos_categoria_select == $fila['id']) { $activo = "selected"; }
											echo '<option value="' . $fila['id'] .'" ' . $activo . '>' . $fila['nombre'] .'</option>';
										}
									?>
								</select>				
							</li>
						<?php } ?>
						<li class=""><input type="text" id="buscar" name="buscar" class="form-control" value="<?php echo $buscar;?>" maxlength="255" /></li>
						<li class="mr-3"><button type="button" class="btn btn-primary" onClick="filtro()"><i class="fas fa-search"></i></li>
						<li class="mr-3"> <button type="button" class="btn btn-primary" onclick="f_guardar('')">Guardar</button></li>	
						<li class="mr-3"> <button type="button" class="btn btn-primary" onclick="f_editar('')">Nueva</button></li>
					</ul>
				</div>
			</nav>
		</header>

		<?php include('includes/menu.php');	?>
        <div class="page-wrapper">

            <div class="page-breadcrumb">
                <div class="row">
                    <div class="col">
                        <h4 class="page-title">Noticias</h4>
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
					<th width="50" align="center">Activo</th>	
					<th width="50" align="center">Posición Home</th>		
					<th width="50" align="center">Destacado Video</th>	
					<th width="50" align="center">Imagen</th>							
					<th width="100">Fecha</th>
					<th width="200">Imagen/Video</th>
					<th>Titular</th>
					<th width="100" align="center"></th>			
				  	<th width="100" align="center"></th>				  
				</tr>
			</thead>

			<tbody>
	<?php

/*
$sql = "SELECT * FROM contenidos where tags<>''";
$rs = $mysqli->query($sql); 
while ($fila = $rs->fetch_array(MYSQLI_ASSOC)) {
	$tags = $fila['tags'];	
	$array_tags = explode(",", $tags);
	foreach ($array_tags as $tag) {
		$tag_nombre = trim($tag);
		$tag_slug = limpia_post_name($tag_nombre);

		$sql2 = "SELECT * FROM tags WHERE slug='$tag_slug'";
		$rs2 = $mysqli->query($sql2); 
        if ($rs2->num_rows == 0) {
			$sql3 = "INSERT INTO tags (nombre, slug) VALUES ('$tag_nombre', '$tag_slug')"; 
			$rs3 = $mysqli->query($sql3); 
        }
	}
}
*/


		$sql = "SELECT contenidos.id, contenidos.activo, contenidos.destacado, contenidos.destacado_video, contenidos.destacado_img, contenidos.fecha, contenidos.post_name, contenidos.titulo, contenidos.entradilla, contenidos.texto, contenidos.imagen, contenidos.imagen_pie, contenidos.video, contenidos.tags, contenidos.id_congresos_categoria, contenidos_categorias.id_categoria FROM contenidos";		
		$sql.= " INNER JOIN contenidos_categorias ON contenidos_categorias.id_contenido = contenidos.id";		
		$sql.= " WHERE contenidos.tipo='post'";
		if ($buscar != "") { $sql.= " AND (contenidos.titulo LIKE '%$buscar%' OR contenidos.entradilla LIKE '%$buscar%' OR contenidos.texto LIKE '%$buscar%') OR contenidos.fecha='$buscar'"; }
		if ($categoria_select != "0") { 
			$sql.= " AND contenidos_categorias.id_categoria=$categoria_select";
		}

		if ($congresos_categoria_select != "0") { 
			$sql.= " AND contenidos.id_congresos_categoria=$congresos_categoria_select";
		}
		
		$sql.= " GROUP BY contenidos.id";		
		$sql.= " ORDER BY contenidos.destacado, contenidos.fecha DESC, contenidos.id DESC";


		$rs = $mysqli->query($sql); 
		$num_total_registros = $rs->num_rows;  
		$total_paginas = ceil($num_total_registros / $TAMANO_PAGINA); 
		$sql = $sql . " limit " . $inicio . "," . $TAMANO_PAGINA;
		$rs = $mysqli->query($sql); 
		if ($rs->num_rows > 0) {

			while ($fila = $rs->fetch_array(MYSQLI_ASSOC)){
				$id = $fila['id'];		
				$fecha = cambiaf_a_normal($fila['fecha']);			
				$titulo = $fila['titulo'];	
				$entradilla = $fila['entradilla'];	
				$texto = $fila['texto'];	
				if ($texto!="") {  					$texto = str_replace('src="uploads/', 'src="../uploads/', $texto);					}
				$tags = $fila["tags"];
				$imagen = $fila['imagen'];	
				$imagen_pie = $fila['imagen_pie'];	
				$video = $fila['video'];
				
				if ($imagen!="") {  $imagen = '<img src="../' . $imagen .'" class="img-fluid">'; }
				elseif ($video!="")	
				{  
					$pos = strpos($video, "youtube");
					if ($pos !== false) { 
						$video = str_replace ("watch?v=", "", $video);
						$arr_video	= explode('/', $video ); 
						$codigo_video = $arr_video[count($arr_video)-1];
						$url = "https://i.ytimg.com/vi/$codigo_video/hq720.jpg";
						if (url_exists($url))
						{
							$imagen = '<img src="https://i.ytimg.com/vi/' . $codigo_video . '/hq720.jpg" class="img-fluid">';
						} else {
							$imagen = '<img src="https://i.ytimg.com/vi/' . $codigo_video . '/hqdefault.jpg" class="img-fluid">';
						}
					} else{
						$pos = strpos($video, "vimeo");
                        if ($pos !== false) {
							$arr_video	= explode('/', $video ); 
							$codigo_video = $arr_video[count($arr_video)-1];
							$url = getVimeoThumb($codigo_video);	
							$imagen = '<img src="' . $url . '" class="img-fluid">';
						}					
					}
				}

				$slug = $fila["post_name"];
				$activo = $fila['activo'];
				if ($activo=="1")	{ $img="ico_green.gif";	$valor="0"; } 
				else				{ $img="ico_red.gif";	$valor="1";	}	

				$destacado_video = $fila['destacado_video'];
				if ($destacado_video=="1")	{ $img2="ico_green.gif";	$valor2="0"; } 
				else						{ $img2="ico_red.gif";		$valor2="1"; }		
				
				$destacado_img = $fila['destacado_img'];
				if ($destacado_img=="1")	{ $img3="ico_green.gif";	$valor3="0"; } 
				else						{ $img3="ico_red.gif";		$valor3="1"; }		


				$destacado = $fila["destacado"];
				if ($destacado==99999) { $destacado = 0;}

				$id_congresos_categoria = $fila["id_congresos_categoria"];

				$cadena_url = "&cat=$categoria_select&congresos_cat=$congresos_categoria_select";

				
				?>
				<tr scope="row">	
					<td align="center" valign="top"><input type="checkbox" class="none" name="borrar[]" id="borrar[]" value="<?php echo $id;?>"></td>		
					<td align="center"><a href="post_listado.php?id=<?php echo $id?>&val=<?php echo $valor?>&accion=activar<?php echo $cadena_url?>"><img src="images/<?php echo $img?>"></a></td>					
					<td align="center"><input type="text" id="posicion_<?php echo $id;?>" name="posicion_<?php echo $id;?>" class="form-control" value="<?php echo $destacado;?>" maxlength="5"/></td>
					<td align="center"><?php if ($video!="") { ?><a href="post_listado.php?id=<?php echo $id?>&val=<?php echo $valor2?>&accion=destacado_video<?php echo $cadena_url?>"><img src="images/<?php echo $img2?>"></a><?php } ?></td>
					<td align="center"><a href="post_listado.php?id=<?php echo $id?>&val=<?php echo $valor3?>&accion=destacado_img<?php echo $cadena_url?>"><img src="images/<?php echo $img3;?>"></a></td>

					<td><?php echo $fecha;?></td>		
				  	<td align="center">		
						<div class="caja">
							<div class="box2">
								<?php echo $imagen;?>								
							</div>
						</div>
						<?php if ($imagen_pie!="") {  echo "$imagen_pie<br>"; } ?>
					</td>
					<td>
					<!--
						<a href="https://nocontrabando.altadis.com/?p=<?php echo $id;?>" target="_blank">https://nocontrabando.altadis.com/?p=<?php echo $id;?></a><br>
						<a href="<?php echo $dominio;?>?p=<?php echo $id;?>" target="_blank"><?php echo $dominio;?>?p=<?php echo $id;?></a><br>
					-->
					<?php
					$sql2 = "SELECT categorias.id, categorias.nombre, categorias.slug FROM categorias";		
					$sql2.= " INNER JOIN contenidos_categorias ON contenidos_categorias.id_categoria = categorias.id";		
					$sql2.= " WHERE contenidos_categorias.id_contenido='$id'";
					$sql2.= " ORDER BY categorias.orden";
					$rs2 = $mysqli->query($sql2); 
					if ($rs2->num_rows > 0) {
						echo '<p style="margin-bottom:0px">';
						$contador=1;
						while ($fila2 = $rs2->fetch_array(MYSQLI_ASSOC)){

							/*
							if ($categoria_select == "1") { 
								if ($fila2["id"] == "1" && $rs2->num_rows>1) {
									$sql3 = "DELETE FROM contenidos_categorias WHERE id_categoria=1 AND id_contenido=$id";		
									$rs3 = $mysqli->query($sql3); 
									echo " -- " . $sql3 . "-- " ;
								}
							}
							*/

							$categoria_name = $fila2["nombre"];
							$categoria_slug = $fila2["slug"];
							?><a href="<?php echo $dominio . "category/$categoria_slug/";?>"><?php echo $categoria_name;?></a><?php

							if ($contador < $rs2->num_rows) {  echo " | "; }
							$contador ++;

						}
						echo "</p>";
					}
					?>

					<?php 
						if ($id_congresos_categoria!=0 ) {  /********* CONGRESOS *****************/ 
							$sql2 = "SELECT * FROM congresos_categorias where id=$id_congresos_categoria";
							$rs2 = $mysqli->query($sql2); 
							if ($rs2->num_rows > 0) {
								$fila2 = $rs2->fetch_assoc();
								$congreso_categoria_name = $fila2["nombre"];
								$congreso_categoria = $fila2["slug"];
								?><div style="margin-bottom:10px;"><a href="<?php echo $dominio . "category/congresos/$congreso_categoria/";?>"><?php echo $congreso_categoria_name;?></a></div><?php
							}
						}
					?>

						<p><b><?php echo $titulo;?></b></p>
						<?php if ($entradilla!="") { /* echo $entradilla; */} ?>

						<?php 
							if ($tags!="") {
								echo "<p>TAGS: ";
								$array_tags = explode(",", $tags);
								foreach ($array_tags as $tag) {
									$tag_name = trim($tag);	
									$tag_slug = limpia_post_name($tag_name);
									$tag_name = ucfirst($tag_name);	
								?><a href="<?php echo $dominio . "tag/$tag_slug/";?>"><?php echo $tag_name;?></a> | <?php
								}
								echo "</p>";
							}				
						?>	

						<?php if ($texto!="") { /*echo "<br>---------------------------------------------------<br>" . $texto;*/ } ?>
					</td>
					<td align="center"><button type="button" class="btn btn-primary" onClick="f_ver('<?php echo $slug;?>')">Ver</button></td>
				  	<td align="center"><button type="button" class="btn btn-primary" onClick="f_editar('<?php echo $id;?>')">Editar</button></td>				  
				</tr>
				<?php			
			}
		} else {?>
				<tr scope="row">
				  <td colspan="8" align="center">No hay ningun registro</td>
				</tr>
		<?php }	
		
		$url = "post_listado.php";

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