<?php
session_start();


$box = $_GET["box"]; if ($box==""){ $box = $_POST["box"];}
$accion = $_GET["accion"]; if ($accion==""){ $accion = $_POST["accion"];}

?>
<html>
<head>
<meta charset="utf-8">

<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>

<link href="../../../dist/css/style.css" rel="stylesheet">
<link href="../../../css/custom.css" rel="stylesheet">

<style type="text/css">
body { overflow-y:hidden;  padding-bottom:0px;}
.content-box-content {    margin: 0px; }
</style>
<script language="JavaScript">
<!--
function Verificar(obj){
	var ok=true;

	if (document.getElementById("codigo").value=="" && ok!=false){
		ok=false; alert("El campo Código compartir es obligatorio");
		document.getElementById("codigo").focus();
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
	parent.ocultar_pie_youtubeDialog();
}
//-->
</script>
</head>
<body onLoad="cierre()">
	<form name="form1" id="form1" method="post" OnSubmit="return Verificar();">
			
				<div align="right" style="padding-top:10px; padding-bottom:5px;">
					<input name="accion" type="hidden" id="accion">
					<input type="button" name="Button" value="Enviar" class="btn btn-primary" onClick="Verificar(this.form)">
				</div>
	<?php if ($accion != "modificar"){?>

				<table width="100%" border="0" cellpadding="0" cellspacing="3">
					<tr valign="top">
					<td width="100%"><input type="text" name="codigo" id="codigo" class="cajas" style="width:100%"><br>
					<span class="textopeq">https://youtu.be/5wWccG-3mXg<br>https://vimeo.com/411870028</span>
					</td>
					</tr>
				</table>
	<?php } else {
		$codigo = (isset($_POST['codigo']) ? $_POST['codigo'] : ""); 
		$url = $codigo;

		if ($codigo!="") {
			$codigo = str_replace ("watch?v=", "", "$codigo");
			$arr_video	= explode('/', $codigo ); 
			$codigo = $arr_video[count($arr_video)-1];
		}
	?>
	<script>
		var url = "<?php echo $url; ?>";
		var valor = "<?php echo $codigo; ?>";
		parent.poner_valor_iframe(url, valor);
	</script>
	<?php } ?>
	</body>