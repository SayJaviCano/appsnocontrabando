<?php
session_start();
$roles = array('0','1');
include('includes/segur.php');

$path_relative = "../";
include('../includes/conexion.php');

$id = (isset($_GET['id']) ? $_GET['id'] : ""); if ($id==""){  $id = (isset($_POST['id']) ? $_POST['id'] : ""); }
$cat = (isset($_GET['cat']) ? $_GET['cat'] : ""); if ($cat==""){  $cat = (isset($_POST['cat']) ? $_POST['cat'] : ""); }
$congresos_cat = (isset($_GET['congresos_cat']) ? $_GET['congresos_cat'] : ""); if ($congresos_cat==""){  $congresos_cat = (isset($_POST['congresos_cat']) ? $_POST['congresos_cat'] : ""); }
$pagina = (isset($_GET['pagina']) ? $_GET['pagina'] : ""); if ($pagina==""){  $pagina = (isset($_POST['pagina']) ? $_POST['pagina'] : ""); }
$accion = (isset($_GET['accion']) ? $_GET['accion'] : ""); if ($accion==""){  $accion = (isset($_POST['accion']) ? $_POST['accion'] : ""); }

$n1=8;
$n2=2;

$max_title = 70;
$max_description = 160;

/************************************************************ */
$fecha = date("Y-m-d H:i:s");
$sql = "SELECT * FROM contenidos WHERE id='$id'"; 
$rs = $mysqli->query($sql); 
if ($rs->num_rows > 0) {
    $fila = $rs->fetch_assoc();
    $fecha = $fila['fecha'];
} else {
	$fecha = date("Y-m-d");
}

$separa = explode("-",$fecha);
$anio= $separa[0];
$mes = $separa[1];
$dia = $separa[2];
$_SESSION['dir_img_editor'] = "../uploads/$anio/$mes/";
$uploadDir = $_SESSION['dir_img_editor'];

$valor = rmkdir($uploadDir);

function rmkdir($path, $mode = 0777) {
     $path = rtrim(preg_replace(array("/\\\\/", "/\/{2,}/"), "/", $path), "/");
     $e = explode("/", ltrim($path, "/"));
     if(substr($path, 0, 1) == "/") {
         $e[0] = "/".$e[0];
     }
     $c = count($e);
     $cp = $e[0];
     for($i = 1; $i < $c; $i++) {
         if(!is_dir($cp) && !@mkdir($cp, $mode)) {
             return false;
         }
         $cp .= "/".$e[$i];
     }
     return @mkdir($path, $mode);
 }
/************************************************************ */


$aux_n_fich = date("his");

if ($accion=="modificar") {
	$activo = $_POST["activo"];
	$fecha = cambia_fecha_a_mysql($_POST["fecha"]);	
	$post_name = $_POST["post_name"];
	
	$nombre_archivo = ($_FILES['imagen']['name']);
	$tipo_archivo = $_FILES['imagen']['type']; 
	$arr_extension = explode(".",$nombre_archivo);
	$num = count($arr_extension)-1;
	$extension = strtolower($arr_extension[$num]);
	$aux_ext = "." . $extension;
	$aux_nom = trim(str_replace ($aux_ext, "", "$nombre_archivo")); 
	$file = $aux_nom . "_" . $aux_n_fich . "." . $extension;
	$file = limpia_cadena($file) ;
	$uploadFile = $uploadDir . $file;
	$imagen_new = "";
	if (move_uploaded_file($_FILES['imagen']['tmp_name'], $uploadFile)) { 
		$imagen_new=$file;
		$att=chmod($uploadFile,0644);				
	}
	$video = ($_POST["video"]);	

	if ($video!="")	
	{  
		$video_aux = $video;
		$pos = strpos($video, "youtube");
		if ($pos !== false) { 
			$video = str_replace ("watch?v=", "", $video);
			$arr_video	= explode('/', $video ); 
			$codigo_video = $arr_video[count($arr_video)-1];
			$video_aux = "https://www.youtube.com/embed/$codigo_video";
		}

		$pos = strpos($video, "youtu.be");
		if ($pos !== false) { 
			$video_aux = str_replace('https://youtu.be/', 'https://www.youtube.com/embed/', $video_aux);
		}		

		$pos = strpos($video, "vimeo");
		if ($pos !== false) {
			$pos2 = strpos($video, "embed");
			if ($pos2 !== false) {
			} else {
				$video_aux = str_replace('https://vimeo.com/', 'https://player.vimeo.com/video/', $video_aux);
			}
		}	

		$video = $video_aux;
	}
	

	$imagen_pie	= ($_POST["imagen_pie"]);	


	$livechat_vimeo = $_POST["livechat_vimeo"];	
	if ($livechat_vimeo!="1") { $livechat_vimeo = "0"; }
	

	$titulo = ($_POST["titulo"]);	
	$entradilla = ($_POST["entradilla"]);
	$texto = ($_POST["texto"]);

	$meta_title = ($_POST["meta_title"]);
	$meta_description = ($_POST["meta_description"]);
	$tags = ($_POST["tags"]);	
	
	$borrar_imagen = $_POST["borrar_imagen"];
	$id_congresos_categoria = $_POST["congresos_categorias"];
	if ($id_congresos_categoria=="") { $id_congresos_categoria=0; }


	$sql = "SELECT * FROM contenidos WHERE post_name='$post_name'"; 
	$rs = $mysqli->query($sql); 
	$num_total_registros = $rs->num_rows; 
	if ($num_total_registros>0) {
		$fila = $rs->fetch_assoc();	
		$id_contenido = $fila['id'];
		$old_imagen = ($fila['imagen']);

		$sql2 = "Update contenidos SET activo=$activo, fecha='$fecha', post_name='$post_name', titulo='$titulo', entradilla='$entradilla', texto='$texto', meta_title='$meta_title', meta_description='$meta_description', video='$video', tags='$tags', imagen_pie='$imagen_pie', livechat_vimeo='$livechat_vimeo'";
		$sql2 .= ", id_congresos_categoria=$id_congresos_categoria";
		if ($imagen_new!="") {
			$imagen_new = $uploadDir .$imagen_new;
			$imagen_new = str_replace('../uploads/', 'uploads/', $imagen_new);	

			$sql2 .= ", imagen='$imagen_new'";			
			if ($old_imagen!="") {
				$archivo_a_borrar = "../" . $old_imagen; 
				if (file_exists($archivo_a_borrar)) { unlink($archivo_a_borrar); }	
			}
		}	
		elseif ($borrar_imagen=="1") {
			$archivo_a_borrar = "../" . $old_imagen; 
			if (file_exists($archivo_a_borrar)) { unlink($archivo_a_borrar); }	

			$sql2 .= ", imagen=''";
		}			
		$sql2 = $sql2 . " WHERE id='$id_contenido'"; 
		$rs2 = $mysqli->query($sql2);

	} else {
		$sql2 = "INSERT INTO contenidos (activo, destacado, destacado_video, fecha, post_name, titulo, entradilla, texto, meta_title, meta_description, video, tags, tipo, imagen_pie, livechat_vimeo, id_congresos_categoria";
		if ($imagen_new!="") { $sql2 .= ", imagen"; 	}
		$sql2 .= ") VALUES ($activo, 99999, 0, '$fecha', '$post_name', '$titulo', '$entradilla', '$texto', '$meta_title', '$meta_description', '$video', '$tags', 'post', '$imagen_pie', '$livechat_vimeo', id_congresos_categoria";
		if ($imagen_new!="") { 
			$imagen_new = $uploadDir .$imagen_new;
			$imagen_new = str_replace('../uploads/', 'uploads/', $imagen_new);	
			$sql2 .= ", '$imagen_new'";
		}
		$sql2 .= ")";     
		$rs2 = $mysqli->query($sql2);
		$id_contenido = $mysqli->insert_id;
	}	


	$cuantas_categorias = 0;
	$sql2 = "SELECT * FROM categorias ORDER BY orden, nombre";
	$rs2 = $mysqli->query($sql2); 
	while ($fila2 = $rs2->fetch_array(MYSQLI_ASSOC)){
		$id_categoria = $fila2['id'];
		$id_categoria_post = ($_POST["categoria_" . $id_categoria]);
		if (is_numeric($id_categoria_post)) {
			$sql = "SELECT * FROM contenidos_categorias WHERE id_contenido=$id_contenido AND id_categoria=$id_categoria_post";
			$rs = $mysqli->query($sql); 
            if ($rs->num_rows==0) {
                $sql3 = "INSERT INTO contenidos_categorias (id_contenido, id_categoria) VALUES ('$id_contenido', '$id_categoria')";
                $rs3 = $mysqli->query($sql3);
				$cuantas_categorias++;		   
            } else {
				$cuantas_categorias++;
			}
		} else {
			$sql3 = "DELETE FROM contenidos_categorias WHERE id_contenido=$id_contenido AND id_categoria=$id_categoria";
			$rs3 = $mysqli->query($sql3);
		}
	}	
	
	if ($cuantas_categorias==0) {
		$sql3 = "INSERT INTO contenidos_categorias (id_contenido, id_categoria) VALUES ('$id_contenido', '5')"; // 5 = Sin Categoria
		$rs3 = $mysqli->query($sql3);	   
	}

	getSiteMap();
	?><script>document.location.href="post_listado.php?cat=<?php echo $cat;?>&congresos_cat=<?php echo $congresos_cat;?>&pagina=<?php echo $pagina;?>"</script><?php
	die();
}

$id_congresos_categoria = 0;
$page_title = "Nueva Noticia";
$fecha = date("Y-m-d H:i:s");
$sql = "SELECT * FROM contenidos WHERE id='$id'"; 
$rs = $mysqli->query($sql); 
if ($rs->num_rows > 0) {
	$fila = $rs->fetch_assoc();

	$activo = $fila['activo'];
	$id_congresos_categoria = $fila['id_congresos_categoria'];
	$fecha = $fila['fecha'];			
	$post_name = $fila["post_name"];
	$imagen = $fila['imagen'];	
	$imagen_pie = $fila['imagen_pie'];	
	$video = $fila['video'];

	$livechat_vimeo_activo ="";
	$livechat_vimeo = $fila['livechat_vimeo'];
	if ($livechat_vimeo) { $livechat_vimeo_activo = "checked"; }
	
	$titulo = $fila['titulo'];	
	$entradilla = $fila['entradilla'];	
	$texto = $fila['texto'];	

	$tags = $fila["tags"];
	$meta_title = $fila["meta_title"];
	$meta_description = $fila["meta_description"];

	if ($texto!="") {  
		$texto = str_replace('src="uploads/', 'src="../uploads/', $texto);					
	}

	$img=""; $vid="";
	if ($imagen!="")	{ $img = '<img src="../' . $imagen .'" class="img-fluid" >'; }
	if ($video!="")		{ 
		$video_aux = $video;
		$pos = strpos($video, "vimeo");
        if ($pos !== false) 
		{
			$pos2 = strpos($video, "embed");
            if ($pos2 !== false) {
            } else {
                $video_aux = str_replace('https://vimeo.com/', 'https://player.vimeo.com/video/', $video_aux);
            }
        }
		$vid = '<iframe width="100%" src="' . $video_aux. '" allowfullscreen="allowfullscreen" frameborder="0"></iframe>';
	}
	
	
	$page_title = "Editar Noticia";
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

	<link rel="stylesheet" href="dist/css/bootstrap-tagsinput.css">
	<script src="dist/js/bootstrap-tagsinput.js"></script>

	
	<script>var root_path = "";</script>
	<script type="text/javascript" src="ckeditor/ckeditor.js?<?php echo time();?>" charset="utf-8"></script>  	

	<script type="text/javascript" src="../js/comun.js"></script>

	<script language="JavaScript">
	<!--
	$(document).ready(function () { 
		CKEDITOR.replace('entradilla', { height : '150'});
		CKEDITOR.replace('texto', { height : '700'});

		//alert(CKEDITOR.version);

		$("#titulo").blur(function() {
			post_name = document.getElementById("post_name").value;
			titulo = document.getElementById("titulo").value;
			if (post_name=="") { carga_post_name(titulo); }	
		});
	
	});

	function verificar(){
		var ok=true;

		if (document.getElementById("post_name").value=="" && ok!=false){
			ok=false;
			$(".error").html("El campo <b>URL</b> es obligatorio");
			document.getElementById("post_name").focus();
		}		

		//---------------------------------------------------------------------				
		if (ok){		
			document.form1.action="post_editar.php";
			document.getElementById("accion").value="modificar";
			document.form1.submit();
			return true;
		}
	}

	function carga_post_name(post_name) {

		$.ajax({
		type: "POST",
		url: 'ajax_post_name.php', 
		data: { p: post_name, id: '<?php echo $id;?>' },
		success: function(data) {
			document.getElementById("post_name").value = data;
		}
		}).fail( function( jqXHR, textStatus, errorThrown ) {
			alert(errorThrown );
		});
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
	<input name="cat" type="hidden" id="cat" value="<?php echo $cat;?>">
	<input name="congresos_cat" type="hidden" id="congresos_cat" value="<?php echo $congresos_cat;?>">
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
									<div class="col-md-2">
										<label for="activo" class="mr-sm-2">Activo</label>
										<select name="activo" id="activo" class="form-control mr-sm-5">
											<option value="1" <?php if($activo=="1") { echo "selected"; } ?>>SI</option>
											<option value="0" <?php if($activo=="0") { echo "selected"; } ?>>NO</option>
										</select>
									</div>
									<div class="col-md-2">
										<label for="fecha" class="mr-sm-2">Fecha</label>
										<input type="text" id="fecha" name="fecha" class="form-control" value="<?php echo cambiaf_a_normal($fecha);?>" maxlength="255"/>
									</div>									
									<div class="col-md-8">
										<label for="slug" class="mr-sm-2">Url</label>
										<input type="text" id="post_name" name="post_name" class="form-control" value="<?php echo $post_name;?>" maxlength="255"/>
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
								<div class="row">
									<div class="col-md-6 mt-3">
										<label for="imagen" class="mr-sm-2">Imagen <span class="texto_p">JPG, GIF o PNG, 1170 x alto</span></label>
										<input name="imagen" type="file" id="imagen" class="form-control" accept=".jpg, .gif, .png">
										<?php if ($img!="") { 
											echo $img; 
											?><br><input name="borrar_imagen" type="checkbox" id="borrar_imagen" value="1"/> [Borrar]<?php
										} ?>	
									</div>

									<div class="col-md-6 mt-3">
										<label for="video" class="mr-sm-2">Vídeo</label>
										<input type="text" id="video" name="video" class="form-control" value="<?php echo $video;?>" maxlength="255"/>
										<?php if ($vid!="") { echo '<div class="caja"><div class="box3">' . $vid . '</div></div>'; } ?>	
									</div>	
								</div>
								<div class="row">									
									<div class="col-md-6 mt-3">
										<input type="text" id="imagen_pie" name="imagen_pie" class="form-control" value="<?php echo $imagen_pie;?>" maxlength="255" placeholder="Leyenda imagen"/>
									</div>																											
								</div>									
								<div class="row">									
									<div class="col-md-12 mt-3">
										<label for="content" class="mr-sm-2">Contenido</label>
										<textarea name="texto" id="texto" class="form-control"><?php echo $texto;?></textarea>
									</div>																											
								</div>		

								<div class="row mt-3">	
									<div class="col-md-12">
										<label for="meta_title" class="mr-sm-2">Meta Title <span id="charNumt" class="contador"><?php echo $conta_title;?></span></label>
										<input type="text" id="meta_title" name="meta_title" class="form-control" value="<?php echo $meta_title;?>" maxlength="255" onKeyUp="countChars(this, <?php echo $max_title;?>, 'charNumt')"/>
									</div>		
									<div class="col-md-12">
										<label for="meta_description" class="mr-sm-2">Meta Description <span id="charNum" class="contador"><?php echo $conta_description;?></span></label>
										<textarea name="meta_description" id="meta_description" class="form-control" onKeyUp="countChars(this, <?php echo $max_description;?>, 'charNum')"><?php echo $meta_description;?></textarea>
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
							<h4>Categorías</h4>
							<ul class="categorias">
								<?php
									$sql = "SELECT * FROM categorias WHERE activo=1 ORDER BY orden, nombre";
									$rs = $mysqli->query($sql); 
									while ($fila = $rs->fetch_array(MYSQLI_ASSOC)){
										$activo = "";
										$id_categoria = $fila['id'];

										$sql2 = "SELECT * FROM contenidos_categorias WHERE id_categoria = $id_categoria AND id_contenido=$id";
										$rs2 = $mysqli->query($sql2); 
											if ($rs2->num_rows > 0) {  $activo = "checked"; }
										?>
										<li>
											<div class="checkbox_group">
												<input type="checkbox" class="none" name="categoria_<?php echo $id_categoria;?>" id="categoria_<?php echo $id_categoria;?>" value="<?php echo $id_categoria;?>" <?php echo $activo;?>>
												<label for="categoria_<?php echo $id_categoria;?>"><?php echo $fila['nombre']?></label>
											</div>	
											
											<?php if ($id_categoria=="13") { ?>
											<div style="padding-left:15px">
											<?php
												$sql3 = "SELECT * FROM congresos_categorias ORDER BY orden DESC, nombre";
												$rs3 = $mysqli->query($sql3); 
												while ($fila3 = $rs3->fetch_array(MYSQLI_ASSOC)){
													$activo = "";
													$w_id_contresos_categorias = $fila3['id'];

													if ($id_congresos_categoria == $w_id_contresos_categorias) { $activo = "checked"; }
													?>
													<div class="radio_group">
														<input type="radio" class="none" name="congresos_categorias" id="congresos_categorias_<?php echo $w_id_contresos_categorias;?>" value="<?php echo $w_id_contresos_categorias;?>" <?php echo $activo;?>>
														<label for="congresos_categorias_<?php echo $w_id_contresos_categorias;?>"><?php echo $fila3['nombre']?></label>
													</div>
													<?php
													
												}
											?>
											</div>
											<?php } ?>
										</li><?php
									}
								?>
							</ul>
						</div>

						<div class="card card-body">
							<h4>Etiquetas</h4>
							<input type="text" id="tags" name="tags" class="form-control" value="<?php echo $tags;?>" data-role="tagsinput"/>
						</div>	
						
						<div class="card card-body">
							<h4>Live Chat Vimeo</h4>
							<div class="checkbox_group">
								<input type="checkbox" class="none" name="livechat_vimeo" id="livechat_vimeo" value="1" <?php echo $livechat_vimeo_activo;?>>
								<label for="livechat_vimeo">Activo</label>
							</div>
						</div>	

					</div>
				</div>
            </div>
        </div>
	</div><!-- End Wrapper -->
</form>

</body>

</html>
