<?php

/** Homepage news: 
 * 1 articulo de 100% ancho (50% foto)
 * Banner block
 * 3 articulos de 33% ancho (100% foto)
 * denincia + chart
 * 
 */

include("header.php"); ?>

<?php

$sql = "
	SELECT c.id, c.post_name, c.fecha, c.titulo, c.entradilla, 
		c.texto, c.imagen, c.imagen_pie, c.video, c.tags, 
        c.destacado_img, 
        MIN(j.id_categoria)

		FROM contenidos c

		LEFT JOIN contenidos_categorias j ON j.id_contenido = c.id 
		LEFT JOIN categorias cat ON cat.id = j.id_categoria

		WHERE c.activo=1 
		AND c.tipo='post' 
		AND cat.activo = 1

		GROUP BY c.id

		ORDER BY destacado, c.fecha DESC, c.id DESC 

		LIMIT 4";

$rs = $mysqli->query($sql);
$num_total_registros = $rs->num_rows;

if ($rs != "") {

  $rec_count = 1;

  // Call first record: 
  $fila = $rs->fetch_array(MYSQLI_ASSOC);
  $id = $fila['id'];
  $titulo = $fila['titulo'];
  $entradilla = $fila['entradilla'];
  $imagen = $fila['imagen'];
  $imagen_pie = $fila['imagen_pie'];
  $video = $fila['video'];

  $fecha = cambiaf_a_normal($fila['fecha']);

  $has_media = false;

  if ($imagen != "") {

    $has_media = true;

    $imagen = '<img src="' . $dominio_web. $imagen . '" class="img-fluid"  alt="' . $titulo . '">';
  } elseif ($video != "") {

    $pos = strpos($video, "youtube");

    if ($pos !== false) {

      $has_media = true;

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

        $has_media = true;

        $arr_video  = explode('/', $video);
        $codigo_video = $arr_video[count($arr_video) - 1];
        $url = getVimeoThumb($codigo_video);
        $imagen = '<img src="' . $url . '" class="img-fluid" alt="' . $titulo . '">';
      }
    }
  }

  // If there is no media, show full width article: 

  $slug = $fila["post_name"];
  $destacado_img = $fila['destacado_img'];

  // ENCABEZADO - NOTICIA PRINCIPAL (HOME)


  $rec_count++;

  if ($has_media) {
    $class = "noticias-card";
  } else {
    $class = "noticias-card-textonly";
  }

?>
  <section class="container">
    <div class="row">

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
            $contador_sub = 1;
            while ($fila2 = $rs2->fetch_array(MYSQLI_ASSOC)) {
              $categoria_name = $fila2["nombre"];
              $categoria_slug = $fila2["slug"];
          ?><a href="<?php echo $dominio . "category/$categoria_slug/"; ?>"><?php echo $categoria_name; ?></a>
          <?php
              if ($contador_sub < $rs2->num_rows) {
                echo " | ";
              }

              $contador_sub++;
            }

            echo " | " . $fecha . "</p>";
          }
          ?>
          <h2><a href="<?php echo $dominio . $slug . "/"; ?>" title="<?php echo $titulo; ?>" class="tit2"><?php echo $titulo; ?></a></h2>
          <?php echo $entradilla; ?>

          <a href="<?php echo $dominio . $slug . "/"; ?>" title="<?php echo $titulo; ?>" class="arrow-link">Seguir Leyendo</a>
        </div>
      </div>
    </div>
  </section>

<?php
}  // End of first record (main)
?>




<!-- Banner Section -->
<section class="container">
  <div class="row">

    <div class="col-12 text-center mb-5">

      <?php
      $sql2 = "SELECT * FROM banner_home WHERE activo=1 ORDER BY RAND() LIMIT 1";
      $rs2 = $mysqli->query($sql2);

      if ($rs2->num_rows > 0) {
        $banner_list2 = $rs2->fetch_assoc();
        $archivo = $banner_list2["archivo"];
        $texto = $banner_list2["texto"];
        $enlace = $banner_list2["enlace"];

        $target = "_self";
        $pos = strpos($enlace, "http");
        if ($pos === false) {
          $enlace = $dominio . $enlace;
        } else {
          $target = "_blank";
        }

      ?>
        <a href="<?php echo $enlace; ?>" title="<?php echo $texto; ?>"
          target="<?php echo $target; ?>">
          <img src="<?php echo $dominio . "images/banner/$archivo"; ?>"
            class="img-fluid" alt="<?php echo $texto; ?>">
        </a>
      <?php } ?>

    </div>
  </div>
</section>
<!-- Fin Banner Section -->




<!-- 3 noticias -->
<section class="container">
  <div class="row">

    <?php

    // Next 3 news records: 
    if ($rs != "") {

      while ($fila = $rs->fetch_array(MYSQLI_ASSOC)) {

        $id = $fila['id'];
        $titulo = $fila['titulo'];
        $entradilla = $fila['entradilla'];
        $imagen = $fila['imagen'];
        $imagen_pie = $fila['imagen_pie'];
        $video = $fila['video'];

        $slug = $fila["post_name"];

    ?>

        <div class="col-md-4">
          <div class="noticias-card-compact">
            <?php
            $sql2 = "SELECT categorias.id, categorias.nombre, categorias.slug FROM categorias";
            $sql2 .= " INNER JOIN contenidos_categorias ON contenidos_categorias.id_categoria = categorias.id";
            $sql2 .= " WHERE contenidos_categorias.id_contenido='$id'";
            $sql2 .= " ORDER BY categorias.orden";
            $rs2 = $mysqli->query($sql2);
           
            if ($rs2->num_rows > 0) {
              echo '<p class="cat">';
              $contador_slug = 1;
              while ($fila2 = $rs2->fetch_array(MYSQLI_ASSOC)) {
                $categoria_name = $fila2["nombre"];
                $categoria_slug = $fila2["slug"];
            ?><a href="<?php echo $dominio . "category/$categoria_slug/"; ?>"><?php echo $categoria_name; ?></a>
            <?php
                if ($contador_slug < $rs2->num_rows) {
                  echo " | ";
                }
                $contador_slug++;
              }
              echo " | " . $fecha . "</p>";
            }
            ?>
            <h3><a href="<?php echo "$dominio" . $slug . "/"; ?>" title="<?php echo $titulo; ?>" class="titular_3"><?php echo $titulo; ?></a></h3>
            <p>
              <a href="<?php echo "$dominio" . $slug . "/"; ?>" title="<?php echo $titulo; ?>" class="arrow-link">Seguir Leyendo</a>
            </p>

          </div>
        </div>
    <?php
      }
      $rec_count++;
    } // if records (3 smaller news).
    ?>
  </div>
</section>
<!-- Fin noticias Section -->




<!-- Denuncia iFrame Section -->
<section class="container">
  <div class="row">
    <div class="col-12">
      <iframe id="iframe_chart" name="iframe_chart" src="<?php echo $dominio ?>iframe_chart.php" frameborder="0" scrolling="no"></iframe>
    </div>
  </div>
</section>
<!-- Fin Denuncia iFrame Section -->




<?php 
/**
 * App no contrabando - dont show app banner
 * Could really remove this block, this way we can update with copy/paste between 2 sites
**/

if (!APP_ACCESS):

/**
 * APP BANNER TRIGGER: 
 * Link to show pop/up or links to download app. 
 * The link is included in meta.php and is triggered with the query_string "pop=appNoContrabando"
 * 
 */
  $url_pop = $dominio . "?utm_source=webNoContrabando&utm_medium=pie&utm_campaign=perdemosTodos&pop=appNoContrabando";
?>
<!-- App Banner -->
<section class="container">
  <div class="row">
    <div class="col-12">

      <div style="background-color:var(--color-b);border-radius:10px;text-align:center;padding:20px;">
        <h2 style="color:#FFF;">Descargar App Gratis</h2>
        <p class="text-center mt-3">
          <a href="<?php echo $url_pop; ?>">
            <img src="<?php echo $dominio; ?>images/google-play.png" alt="Google Play" class="img-fluid">
          </a>
          <a href="<?php echo $url_pop; ?>" >
            <img src="<?php echo $dominio; ?>images/app-store.png" alt="App Store" class="img-fluid">
          </a>
        </p>
      </div>
    </div>
</section>
<!-- Fin Denuncia iFraome Section -->
<?php endif; ?>


<?php include("footer.php"); ?>