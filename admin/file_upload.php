<?php
session_start();
$roles = array('0','1','2', '3');
include('includes/segur.php');

$path_relative = "../";
include('../includes/conexion.php');

$box = $_GET["box"]; if ($box==""){ $box = $_POST["box"];}
$accion = $_GET["accion"]; if ($accion==""){ $accion = $_POST["accion"];}
if ($accion == "nuevo") { $nuevo = 1;}

$uploadDir = $_SESSION['dir_img_editor'];

$max_ancho = 920;
?>
<html>
<head>
<meta charset="utf-8">
<link href="dist/css/style.css" rel="stylesheet">
<link href="css/custom.css" rel="stylesheet">

<style type="text/css">
body { overflow-y:hidden;  padding-bottom:0px;}
</style>

<!-- SCRIPTS -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>

<script language="JavaScript">
<!--
function Verificar(obj){
	var ok=true;

	if (document.getElementById("archivo").value=="" && ok!=false){
		ok=false; alert("The File field is required");
		document.getElementById("archivo").focus();
	}
	ext=document.getElementById("archivo").value.split(".");
	extension = ext[ext.length-1].toUpperCase();

	if (document.getElementById("archivo").value != "" && ok!=false)	{	
		if (extension!="JPG" && extension!="GIF" && extension!="PNG" && extension!="MP4" && extension!="DOC" && extension!="DOCX" && extension!="XLS" && extension!="XLSX" && extension!="PDF" && extension!="ZIP" && ok!=false){  
			ok = false;
			$(".error").html("Only files with extension  \"JPG\", \"GIF\", \"PNG\" , \"MP4\", \"DOC\", \"DOCX\", \"XLS\", \"XLSX\", \"PDF\" o \"ZIP\" can be uploaded");			
			document.getElementById("archivo").focus();
		}
	}			
//---------------------------------------------------------------------		
	if (ok){
		document.getElementById("accion").value="modificar";
		document.form1.submit();
		return true;
	}
}

function cierre()
{
	parent.ocultar_pie_altaarchivos();
}
//-->
</script>
</head>
<body onLoad="cierre()">

<form method="POST" enctype="multipart/form-data" name="form1">
 
 <div class="text-right">
	<input name="accion" type="hidden" id="accion">
	<input name="box" type="hidden" id="box" value="<?php echo $box;?>">
	<input type="button" name="Button" value="Upload" class="btn btn-primary" onClick="Verificar(this.form)">
</div>
<div class="clearfix">&nbsp;</div>

<?php if ($accion != "modificar"){?>

			<input name="archivo" type="file" id="archivo" class="form-control" style="margin-bottom:5px;">
			<div class="textopeq">JPG, GIF o PNG (<?php echo $max_ancho;?> x height), MP4, DOC, XLS, PDF o ZIP. (Max. 10Mb)</div>
			<br>
			<label for="leyenda" class="">Leyenda</label>
			<input type="text" id="leyenda" name="leyenda" class="form-control" placeholder="Leyenda" maxlength="255"/>
			<div class="error text-danger">&nbsp;</div>

<?php } else {
	$nombre_archivo = ($_FILES['archivo']['name']);
	$tipo_archivo = $_FILES['archivo']['type']; 
	$leyenda = $_POST["leyenda"];

	$leyenda = trim(str_replace ("'", "´", $leyenda)); 



	$aux_n_fich = date("his");
	$arr_extension = explode(".",$nombre_archivo);
	$num = count($arr_extension)-1;
	$extension = strtolower($arr_extension[$num]);

	$aux_ext = "." . $extension;
	$aux_nom = trim(str_replace ($aux_ext, "", "$nombre_archivo")); 
	$file = $aux_nom . "_" . $aux_n_fich . "." . $extension;
	$file = limpia_cadena($file) ;
	$uploadFile = $uploadDir . $file;


	if (move_uploaded_file($_FILES['archivo']['tmp_name'], $uploadFile)) { 
		$archivo_new=$file;
		$att=chmod($uploadFile,0644);
		
		set_time_limit(600);

        if ($extension=="jpg" || $extension=="gif" || $extension=="png") {
            f_optim_img($uploadDir, $file, $max_ancho);
        }
	}
	$archivo_a_cargar = "$uploadDir$archivo_new";

?>
<script>
	var valor = "<?php echo $archivo_a_cargar; ?>";
	var leyenda = "<?php echo $leyenda; ?>";

	parent.poner_valor_altaarchivos(valor, leyenda);
</script>
<?php }?>
</body>