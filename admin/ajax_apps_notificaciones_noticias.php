<?php
session_start();
$roles = array('0','1','2','3');
include('includes/segur.php');

$path_relative = "../";
include('../includes/conexion.php');

?>
<html>
<head>
<title></title>
<style type="text/css">
	body {padding-top:90px; }
	.cab { position: fixed;height: 80px;top: 0;background: #FFF; width: 100%; }
  .bot-top  { position: absolute; top: 15px; right:30px; }
	</style>
<?php

$accion = (isset($_GET['accion']) ? $_GET['accion'] : ""); if ($accion==""){  $accion = (isset($_POST['accion']) ? $_POST['accion'] : ""); }

if ($accion=="seleccionar") {

  $contador = 0;	
  $id_sel = $_POST["id_sel"];

  $sql = "SELECT * FROM contenidos WHERE id=$id_sel AND activo=1";
  $rs = $mysqli->query($sql);
  if ($rs->num_rows > 0) {
    $fila = $rs->fetch_assoc();
    $titulo = $fila['titulo'];	
    $entradilla = strip_tags($fila['entradilla']);

    $entradilla = strip_tags($fila['entradilla']);
    $entradilla = trim(preg_replace('/\s+/', ' ', $entradilla));

    $titulo = tx_corto($titulo, 160);
    $entradilla = tx_corto($entradilla, 160);

    $enlace = $dominio_apps . $fila["post_name"] . "/" . "?utm_source=app&utm_medium=publicacion&utm_campaign=notificacionesApp";
  }       


	echo "<script>parent.f_notificacion('$titulo', '$entradilla', '$enlace', '$id_sel');</script>";
	echo "<script>parent.$.fancybox.close();</script>";	
  die();
}
?>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

  <link href="dist/css/style.css" rel="stylesheet">
  <link href="css/custom.css" rel="stylesheet">

  <script src="assets/libs/jquery/dist/jquery.min.js"></script>
  <script src="assets/libs/popper.js/dist/umd/popper.min.js"></script>
  <script src="assets/libs/bootstrap/dist/js/bootstrap.min.js"></script>

  <script type="text/javascript" src="../js/comun.js"></script>

<script language="JavaScript">
<!--

$(document).ready(function () { 

});

function seleccionar()
{
	var ok=true;

  if(!document.querySelector('input[name="id_sel"]:checked')) {
      ok=false;
      alert('Selecciona una noticia');
  }
	if (ok){
		document.getElementById("accion").value = "seleccionar";
		document.form1.submit();
	}	
}

-->
</script>

</head>

<body>	


    <div class="px-3">		
      <div class="cab">
        <p class="titular text-center mt-3">Noticias</p>
        <input type="button" class="btn btn-primary bot-top" value="Seleccionar" onClick="seleccionar()" >
      </div>

      <form name="form1" id="form1" method="post">
        <input name="accion" type="hidden" id="accion" />
        
		<!-- Table  -->
		<table class="table table-striped">

			<thead class="grey lighten-2">
				<tr scope="row">		
          <td width="30"></td>
					<td width="90">Fecha</td>
					<td>Noticia</td>			  
				</tr>
			</thead>

			<tbody>
	<?php

		$sql = "SELECT * FROM contenidos WHERE activo=1 AND tipo='post' ORDER BY fecha DESC, id DESC LIMIT 100";	
		$rs = $mysqli->query($sql); 
		if ($rs->num_rows > 0) {
			$num_total_registros = $rs->num_rows; 
			while ($fila = $rs->fetch_array(MYSQLI_ASSOC)){
				$id = $fila['id'];		
				$fecha = cambiaf_a_normal($fila['fecha']);			
				$titulo = $fila['titulo'];	
				$entradilla = $fila['entradilla'];				
				?>
				<tr scope="row">	
					<td align="center" valign="top">
              <div class="radio_group">
                  <input type="radio" class="none" name="id_sel" id="id_sel<?php echo $id;?>" value="<?php echo $id;?>"><label for="id_sel<?php echo $id;?>">&nbsp;</label>
              </div>
            </td>		
					<td><?php echo $fecha;?></td>		
					<td>
						<b><?php echo $titulo;?></b>
						<?php if ($entradilla!="") {  echo "<br>" . $entradilla; } ?>
					</td>		  
				</tr>
				<?php			
			}
		}
    ?>
		</tbody>
		<!-- Table body -->
	  </table>  
	  <!-- Table  -->
        
        <p class="text-center"><input type="button" class="btn btn-primary mt-3" value="Seleccionar" onClick="seleccionar()" ></p>
      </form>
    </div>


</body>
</html>