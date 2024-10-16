<?php include("header.php");?>

	<?php if ($congresos_categoria_nombre_select!="") {		?>

    <div class="bg-cabecera">
      <div class="container">
        <div class="col-md-12 content-cab">
          <h1><?php echo $congresos_categoria_nombre_select; ?></h1>
        </div>
      </div>
    </div>
		
	<?php } ?>

<div class="container">
  <section class="row">


    <?php
    $num_pagina = 1;
    $TAMANO_PAGINA = 10; 
    $inicio = 0;

    $sql = "SELECT * FROM contenidos WHERE activo=1 AND tipo='post' AND id_congresos_categoria=$congresos_categoria_id_select";	
	  $sql.= " ORDER BY fecha DESC, id DESC";

    $rs = $mysqli->query($sql);
    $num_total_registros = $rs->num_rows;
    $total_paginas = ceil($num_total_registros / $TAMANO_PAGINA);

    $sql .= " LIMIT " . $inicio . "," . $TAMANO_PAGINA;
    $rs = $mysqli->query($sql);


    if ($rs != "") {
      $contador = 1;

      $fila = $rs->fetch_array(MYSQLI_ASSOC);
      $id = $fila['id'];
      $fecha = cambiaf_a_normal($fila['fecha']);
      $titulo = $fila['titulo'];
      $entradilla = $fila['entradilla'];
      $imagen = $fila['imagen'];
      $imagen_pie = $fila['imagen_pie'];
      $video = $fila['video'];

      $has_media = false;

      if ($imagen != "") {
        $has_media = true;

        $imagen = '<img src="' . $dominio_web. $imagen . '" class="img-fluid" alt="' . $titulo . '">';
      } elseif ($video != "") {

        $pos = strpos($video, "youtube");
        if ($pos !== false) {
          $video = str_replace("watch?v=", "", $video);
          $arr_video  = explode('/', $video);
          $codigo_video = $arr_video[count($arr_video) - 1];
          $url = "https://i.ytimg.com/vi/$codigo_video/hq720.jpg";
          if (url_exists($url)) {
            $imagen = '<img src="https://i.ytimg.com/vi/' . $codigo_video . '/hq720.jpg" class="img-fluid" alt="' . $titulo . '">';
          } else {
            $imagen = '<img src="https://i.ytimg.com/vi/' . $codigo_video . '/hqdefault.jpg" class="img-fluid" alt="' . $titulo . '">';
          }
        } else {
          $pos = strpos($video, "vimeo");
          if ($pos !== false) {
            $arr_video  = explode('/', $video);
            $codigo_video = $arr_video[count($arr_video) - 1];
            $url = getVimeoThumb($codigo_video);
            $imagen = '<img src="' . $url . '" class="img-fluid" alt="' . $titulo . '">';
          }
        }
      }


      $slug = $fila["post_name"];

      if ($has_media) {
        $class = "noticias-card";
      } else {
        $class = "noticias-card-textonly";
      }


      // Main news: 
      if ($contador == 1) {

    ?>


      <div class="col-12 <?php echo $class ?>">

        <!-- image 1/2 width if present -->
        <?php if ($has_media) { ?>
          <div>
            <a href="<?php echo "$dominio" . $slug . "/"; ?>" title="<?php echo $titulo; ?>">
              <div class="caja n2">
                <div class="box">
                  <?php echo $imagen; ?>
                  <?php if ($video != "") { ?><div class="tapa_video"></div><?php } ?>
                </div>
              </div>
            </a>
          </div>
        <?php } ?>

            <!-- text 1/2 or full width. -->

            <div>
              <?php
              $sql2 = "SELECT categorias.id, categorias.nombre, categorias.slug FROM categorias";
              $sql2 .= " INNER JOIN contenidos_categorias ON contenidos_categorias.id_categoria = categorias.id";
              $sql2 .= " WHERE contenidos_categorias.id_contenido='$id'";
              $sql2 .= " ORDER BY categorias.orden";
              $rs2 = $mysqli->query($sql2);

              if ($rs2->num_rows > 0) {
                echo '<p class="cat">';
                $contador = 1;
                while ($fila2 = $rs2->fetch_array(MYSQLI_ASSOC)) {
                  $categoria_name = $fila2["nombre"];
                  $categoria_slug = $fila2["slug"];
              ?><a href="<?php echo $dominio . "category/$categoria_slug/"; ?>"><?php echo $categoria_name; ?></a>
              <?php
                  if ($contador < $rs2->num_rows) {
                    echo " | ";
                  }

                  $contador++;
                }

                echo " | " . $fecha . "</p>";
              }
              ?>
              <h2><a href="<?php echo "$dominio" . $slug . "/"; ?>" title="<?php echo $titulo; ?>" class="tit2"><?php echo $titulo; ?></a></h2>
              <?php echo $entradilla; ?>

              <a href="<?php echo "$dominio" . $slug . "/"; ?>" title="<?php echo $titulo; ?>" class="arrow-link">Seguir Leyendo</a>
            </div>
          </div>

        <?php } // end of first entry (large) ?>

          <!-- 9 noticias -->


          <?php

          // Next 3 news records: 
          if ($rs != "") {

            while ($fila = $rs->fetch_array(MYSQLI_ASSOC)) {

              $id = $fila['id'];
              $titulo = $fila['titulo'];
              $entradilla = $fila['entradilla'];
              $fecha = cambiaf_a_normal($fila['fecha']);
              $slug = $fila["post_name"];

              $sql2 = "SELECT categorias.id, categorias.nombre, categorias.slug 
                        FROM categorias 
                        INNER JOIN contenidos_categorias ON contenidos_categorias.id_categoria = categorias.id
                        WHERE contenidos_categorias.id_contenido='$id'
                        ORDER BY categorias.orden";

              $rs2 = $mysqli->query($sql2);

              if ($rs2->num_rows > 0) {

                $sub_slug = '';
                $cont_slug = 1;

                while ($fila2 = $rs2->fetch_array(MYSQLI_ASSOC)) {
                  $categoria_name = $fila2["nombre"];
                  $categoria_slug = $fila2["slug"];
                  $sub_slug .= '<a href="' . $dominio . "category/" . $categoria_slug . '/">' . $categoria_name . '</a>';

                  if (
                    $cont_slug < $rs2->num_rows
                  ) {
                    $sub_slug .=  " | ";
                  }

                  $cont_slug++;
                }

                $sub_slug .=  " | " . $fecha;
              } else {
                $sub_slug = "";
              }

          ?>


              <div class="col-md-4 mb-4">
                <div class="noticias-card-compact">
                  <p class="cat">
                    <a href="<?php echo $dominio . "category/$categoria_slug/"; ?>">
                      <?php echo $sub_slug; ?>
                    </a>
                  </p>
                  <h3>
                    <a href="<?php echo "$dominio" . $slug . "/"; ?>"
                      title="<?php echo $titulo; ?>" class="titular_3">
                      <?php echo $titulo; ?></a>
                  </h3>
                  <p>
                    <a href="<?php echo "$dominio" . $slug . "/"; ?>" title="<?php echo $titulo; ?>" class="arrow-link">Seguir Leyendo</a>
                  </p>
                </div>
              </div>
    <?php
            }
            $contador++;
          } //  loop while records
        } // if main or cards

    ?>


  </section>
  <!-- ajax load more news: -->
  <div id="content_publicaciones"></div>
  <div id="ver_mas" class="text-center"></div>

</div>


<script>
pagina = 1;
total_paginas = <?php echo $total_paginas;?>;
$(document).ready(function() {	
	mas_resultados_listado('<?php echo $num_pagina; ?>', '<?php echo $total_paginas;?>');
	$("#ver_mas").click(function() { carga_listado(); });	
});

function mas_resultados_listado(pagina, total_paginas) {
	pagina = parseInt(pagina) + 1;
	var mas_resultados = "<div class=\"btn btn-primary ver_mas\">Ver más</div> <div class=\"clearfix\">&nbsp;</div>";
	if (pagina > total_paginas)	{ mas_resultados = "";	}
	$('#ver_mas').html(mas_resultados);
}

function carga_listado() {
	pagina = pagina + 1;
	url_ajax = dominio + 'includes/ajax_congresos_categorias_listado.php?c=<?php echo $congresos_categoria_id_select;?>&p='+ pagina;

	$.ajax({
	  url: url_ajax, 
	  success: function(data) {		
		var new_div = $('<div class="row page_' + pagina+ '">' + data + '</div>').hide();
		$('#content_publicaciones').append(new_div);
		new_div.slideDown( "slow", function() {
			$.scrollTo('.page_' + pagina, 500, { axis:'y', offset: -50 } );
		});		
	  }
	});
}
</script>

<?php include("footer.php");?>