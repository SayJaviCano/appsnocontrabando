<?php	

/*
	$sql = "SELECT * FROM contenidos WHERE tipo='post' ORDER BY fecha DESC LIMIT 1"; 
	$rs = $mysqli->query($sql); 
    if ($rs->num_rows > 0) {
        $fila = $rs->fetch_assoc();
        $fecha = $fila['fecha'];

        $separa = explode("-", $fecha);
        $anio_select_inicial = $separa[0];
    }
*/

	$anio_select_inicial = 0;
	$anio_select = $_POST["anio"] ?? "";
	$anio_select = LimpiaParametros($anio_select);

	if (!is_numeric($anio_select)) { $anio_select = $anio_select_inicial; }

	
	$mes_select_inicial = 0;
	$mes_select = $_POST["mes"] ?? "";
	$mes_select = LimpiaParametros($mes_select);	
	if (!is_numeric($mes_select)) { $mes_select = $mes_select_inicial; }	

	$seccion_select = 0;
	$seccion_select = $_POST["seccion"] ?? "";
	$seccion_select = LimpiaParametros($seccion_select);	
	if (!is_numeric($seccion_select)) { $seccion_select = 0; }	

	$input_search = $_POST["input_search"] ?? "";
	$input_search = LimpiaParametros($input_search);

	$search = $input_search;
	$arr_aux = array( " a ", " ante ", " bajo ", " cabe ", " con ", " contra ", " de ", " desde ", " en ", " entre ", " hacia ", " hasta ", " para ", " por ", " según ", " sin ", " so ", " sobre ", " tras ", " el ", " la ", " los ", " las ", " este ", " esta ", " estos ", " estas ", " un ", " una ", " unos ", " unas ", " yo ", " me " , " mí ", " conmigo " , " tú ", " te ", " ti ", " contigo ", " como ", " cuando ", " donde ");
	$tx_a_buscar = $search . " ";
	$tx_a_buscar = str_replace($arr_aux, " ", $tx_a_buscar);
	$tx_a_buscar = trim($tx_a_buscar);
	$tx_a_buscar = trim($search);
	$arary_busqueda = explode(" ", $tx_a_buscar);
	$cadena_de_busqueda = "";
	$sql_busqueda = "";
	$contador = 1;
	$cuantos =  count($arary_busqueda);
	foreach ($arary_busqueda as $palabra) {
		$cadena_de_busqueda .= "+" . $palabra . " "; // . "* ";
		$sql_busqueda .= " (contenidos.titulo like('%" . $palabra . "%') OR contenidos.entradilla like('%" . $palabra . "%')) ";

		if ($contador < $cuantos) { $sql_busqueda .= " AND "; }
		$contador++;
	}

	
?>
<form method="post" name="form1" role="form" class="f_archivo bg-gris p-3 mb-5">

	<div class="row mt-3 mb-3">
		<div class="input-group col">						
			<input class="form-control form-control-lg my-0" type="text" placeholder="Buscar" aria-label="Search" name="input_search" id="input_search" value="<?php echo $input_search; ?>">
			<div class="input-group-append">
				<a class="btn btn-primary" name="btn_search" id="btn_search"><i class="bi bi-search"></i></a>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="form-group col-6 col-md-4">
			<label for="anio">Año</label>
			<select name="anio" id="anio" class="form-control filtro">
			<option value="0" <?php if ($anio_select==0) { echo "selected"; }?>>Todos</option>
			<?php
				$sql = "SELECT DISTINCT YEAR(fecha) as aa FROM contenidos WHERE tipo='post' ORDER BY fecha DESC"; 
				$rs = $mysqli->query($sql); 
				while ($fila = $rs->fetch_array(MYSQLI_ASSOC)){
					$aa = $fila['aa'];
					?><option value="<?php echo $aa; ?>" <?php if ($anio_select==$aa) { echo "selected"; }?>><?php echo $aa;?></option><?php
				}
			?>			
			</select>				
		</div>
		<div class="form-group col-6 col-md-4">
			<label for="mes">Mes</label>
			<select name="mes" id="mes" class="form-control filtro">
				<option value="0" <?php if ($mes_select==0) { echo "selected"; }?>>Todos</option>
				<?php
				$contador = 1;
				foreach ($array_meses as &$mes) {
					?><option value="<?php echo $contador; ?>" <?php if ($mes_select==$contador) { echo "selected"; }?>><?php echo ucfirst($mes);?></option><?php
					$contador++;
				}			
				?>			
			</select>		
		</div>
		<div class="form-group col-md-4">
			<label for="seccion">Sección</label>	
			<select name="seccion" id="seccion" class="form-control filtro">
				<option value="0" <?php if ($seccion_select==0) { echo "selected"; }?>>Todas</option>
				<?php
					$sql = "SELECT * FROM categorias WHERE activo=1 ORDER BY orden, nombre";
					$rs = $mysqli->query($sql); 
					while ($fila = $rs->fetch_array(MYSQLI_ASSOC)){
						$activo = "";
						$categoria_id = $fila['id'];
						$categoria_nombre = $fila['nombre'];
						$categoria_slug = $fila['slug'];
						?><option value="<?php echo $categoria_id; ?>" <?php if ($seccion_select==$categoria_id) { echo "selected"; }?>><?php echo $categoria_nombre;?></option><?php
					}					
				?>			
			</select>			
		</div>
	</div>
</form>	
<?php

if ($input_search!="" || $anio_select!=0 || $mes_select!=0 || $seccion_select!=0) {
    $sql = "SELECT contenidos.id, contenidos.post_name, contenidos.fecha, contenidos.titulo, contenidos.entradilla, contenidos.texto, contenidos.imagen, contenidos.imagen_pie, contenidos.video, contenidos.tags, contenidos_categorias.id_categoria FROM contenidos";
    $sql.= " INNER JOIN contenidos_categorias ON contenidos_categorias.id_contenido = contenidos.id";
    $sql.= " WHERE contenidos.activo=1 AND contenidos.tipo='post'";
   
    if ($input_search!="") {
        $sql.= " AND ( ";
        $sql.= $sql_busqueda;
        $sql.= " ) ";
    }
    if ($anio_select!=0) {
		$sql.= " AND YEAR(contenidos.fecha)=$anio_select";
    }
    if ($mes_select!=0) {
        $sql.= " AND MONTH(contenidos.fecha)=$mes_select";
    }
    if ($seccion_select!=0) {
        $sql.= " AND contenidos_categorias.id_categoria=$seccion_select";
    }

    $sql.= " GROUP BY contenidos.id";
    $sql.= " ORDER BY contenidos.fecha DESC, contenidos.id DESC";

    //echo "$sql<br>";

    $rs = $mysqli->query($sql);
    $num_total_registros = $rs->num_rows;

    if ($num_total_registros > 0) {
        $contador = 1;
        while ($fila = $rs->fetch_array(MYSQLI_ASSOC)) {
            $id = $fila['id'];
            $fecha = ($fila['fecha']);
            $separa = explode("-", $fecha);
            $anio= $separa[0];
            $mes_fila = intval($separa[1]) - 1;

            $titulo = $fila['titulo'];
            $entradilla = $fila['entradilla'];
            $slug = $fila["post_name"]; ?>
				<div class="row">
					<div class="col-md-12">	
						<a href="<?php echo "$dominio" . $slug . "/"; ?>" title="<?php echo $titulo; ?>" class="titular_3"><?php echo $titulo; ?></a>
						<div class="cat">
							<?php echo $anio; ?> | <?php echo ucfirst($array_meses[$mes_fila]); ?> | 
							<?php
                            $sql2 = "SELECT categorias.id, categorias.nombre, categorias.slug FROM categorias";
            $sql2.= " INNER JOIN contenidos_categorias ON contenidos_categorias.id_categoria = categorias.id";
            $sql2.= " WHERE contenidos_categorias.id_contenido='$id'";
            $sql2.= " ORDER BY categorias.orden";
            $rs2 = $mysqli->query($sql2);
            if ($rs2->num_rows > 0) {
                $contador=1;
                while ($fila2 = $rs2->fetch_array(MYSQLI_ASSOC)) {
                    $categoria_name = $fila2["nombre"];
                    $categoria_slug = $fila2["slug"]; ?><a href="<?php echo $dominio . "category/$categoria_slug/"; ?>"><?php echo $categoria_name; ?></a><?php
                                    if ($contador < $rs2->num_rows) {
                                        echo " | ";
                                    }
                    $contador ++;
                }
            } ?>

						</div>
					</div>	
				</div>
				<hr class="clearfix">			
			<?php
            
            $contador++;
        }
    } else {
        ?>
		<div class="row">
			<div class="col-md-12 mb-5">	
				<p class="titular_3 mt-4">No hay artículos publicados para los criterios seleccionados</p>
			</div>	
		</div>		
	<?php
    }
} else {
	
}
	?>
<script>
$(document).ready(function () { 
	$(".filtro").change(function () {
		document.form1.submit();
	});

	$('#btn_search').on('click', function(){ 
		tx = no_XSS ($('#input_search').val());
		var id_textos_buscador = $("#id_tx").val();	
		url = dominio + "search/?s=" + tx + "&i=" + id_textos_buscador;
		document.form1.submit();
		return false;
	});	
});	
</script>
