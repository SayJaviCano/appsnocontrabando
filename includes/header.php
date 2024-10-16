<?php

/** This should really be tidied up - it is only partially dynamic
 * TODO: refactor static checks and non looped code
 */


// Categories are used in several places: Query once and use where necessary: 

$sql = "SELECT id, nombre, slug 
        FROM categorias 
        WHERE activo=1 
        ORDER BY orden, nombre";

$rs = $mysqli->query($sql);

$categorias_reference = [];

while ($fila = $rs->fetch_array(MYSQLI_ASSOC)) {
  $categorias_reference[$fila["id"]] = $fila;
}

?>


<?php
/**
 * DISABLE DENUNCIA TICKER 
 **/

/*
  $sql = "SELECT * FROM ticker WHERE activo=1 ORDER BY orden";
  $rs = $mysqli->query($sql); 
  if ($rs->num_rows>0) {
      echo '<marquee class="marquee-content d-none d-sm-block" scrolldelay="10" behavior="scroll" direction="left" onmouseover="this.stop();" onmouseout="this.start();" >';
      $contador = 1;
      while ($fila = $rs->fetch_array(MYSQLI_ASSOC)){
          echo $fila['texto'];
          if ($contador < $rs->num_rows) {  echo "<p> · </p>"; }
          $contador ++;
      }
      echo '</marquee>';
  }
*/

?>


<!-- header -->

<header>
  <div id="desktop-nav">
    <div class="">
      <div id="top-nav-desktop">
        <div class="button">
          <button id="hamburger-button" aria-label="Hamburger button"><span>Menu</span></button>
        </div>

        <div class="image">
          <a href="<?php echo $dominio; ?>" title="No Contrabando"><img src="<?php echo $dominio; ?>images/no_contrabando_altadis.jpg" class="img-fluid" alt="No Contrabando"></a>
        </div>

      </div>
    </div>

    <div class="mainnavpanel">

      <ul>

        <li class="nav-item <?php if ($page_name == "nosotros") {
                              echo "active";
                            } ?>">
          <a class="nav-link" href="<?php echo $dominio; ?>nosotros/" title="Nosotros">Nosotros</a>
        </li>

        <li class="nav-item dropdown <?php if ($n1 == "category") {
                                        echo "active";
                                      } ?>">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Secciones">Secciones</a>

          <div class="submenupanel">
            <ul>
              <?php

              foreach ($categorias_reference as $cat) {

                $activo = ($page_name == $cat["slug"]) ? " active " : "";
              ?>

                <li>
                  <a class="<?php echo $activo; ?>"
                    href="<?php echo "$dominio" . "category/" . $cat['slug']  . "/"; ?>"
                    title="<?php echo $cat['nombre'] ?>"><?php echo $cat['nombre'] ?></a>
                </li>

              <?php } ?>

            </ul>
          </div>
        </li>

        <li class="nav-item <?php if ($page_name == "buzon-de-denuncias" || $page_name == "conoce-putos-de-venta-legal") {
                              echo "active";
                            } ?>">
          <a class="nav-link" href="<?php echo $dominio; ?>buzon-de-denuncias/" title="Buzón de denuncias">Denuncia</a>
        </li>

        <li class="nav-item <?php if ($page_name == "preguntanos" || $page_name == "preguntas-por-categorias" || $n1 == "preguntanos") {
                              echo "active";
                            } ?>">
          <a class="nav-link" href="<?php echo $dominio; ?>preguntanos/" title="Pregúntanos">Pregúntanos</a>
        </li>

        <li class="nav-item <?php if ($page_name == "archivo") {
                              echo "active";
                            } ?>">
          <a class="nav-link" href="<?php echo $dominio; ?>archivo/" tiles="Archivo">Archivo</a>
        </li>

        <li class="nav-item <?php if ($page_name == "contacto") {
                              echo "active";
                            } ?>">
          <a class="nav-link" href="<?php echo $dominio; ?>contacto/" title="Contacto">Contacto</a>
        </li>
      </ul>

    </div>
  </div>
  <!-- end #desktop-nav -->


  <nav id="mobile-nav">

    <div class="image">
      <a href="<?= $dominio; ?>" alt="No contrabando"><img src="<?= $dominio; ?>images/imperial-brands-logo.png" alt="No contrabando" class="img-fluid"></a>
    </div>

    <div><!--space legacy CSS--></div>

    <div id="top-nav-mobile">
      <div class="navigation">

        <ul>

          <li class="nav-item <?php if ($page_name == "nosotros") {
                                echo "active";
                              } ?>">
            <a class="nav-link" href="<?php echo $dominio; ?>nosotros/" title="Nosotros">Nosotros</a>
          </li>


          <!-- secciones (dropdowns) -->
          <li>
            <a class="nav-link" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Secciones">Secciones</a>

            <ul>
              <li class="submenu-back"><button type="button" class="backToPrevious">volver</button></li>

              <?php

              $sql = "SELECT * FROM categorias WHERE activo=1 ORDER BY orden, nombre";
              $rs = $mysqli->query($sql);

              while ($fila = $rs->fetch_array(MYSQLI_ASSOC)) {
                $activo = "";
                $categoria_nombre = $fila['nombre'];
                $categoria_slug = $fila['slug'];
              ?>

                <li><a class="<?php if ($page_name == $categoria_slug) {
                                echo "active";
                              } ?>" href="<?php echo "$dominio" . "category/" . "$categoria_slug/"; ?>" title="<?php echo $categoria_nombre; ?>"><?php echo $categoria_nombre; ?></a></li>

              <?php } ?>


              <li>
                <a class="<?php if ($page_name == "fiscalidade-do-tabaco") {
                            echo "active";
                          } ?>" href="<?php echo $dominio; ?>fiscalidade-do-tabaco" title="Fiscalidade">Fiscalidade</a>
              </li>

            </ul>

            <button type="button" class="openSubMenu">Open</button>
          </li>
          <!-- // end sections -->


          <li class="nav-item <?php if ($page_name == "buzon-de-denuncias" || $page_name == "conoce-putos-de-venta-legal") {
                                echo "active";
                              } ?>">
            <a class="nav-link" href="<?php echo $dominio; ?>buzon-de-denuncias/" title="Buzón de denuncias">Denuncia</a>
          </li>

          <li class="nav-item <?php if ($page_name == "preguntanos" || $page_name == "preguntas-por-categorias" || $n1 == "preguntanos") {
                                echo "active";
                              } ?>">
            <a class="nav-link" href="<?php echo $dominio; ?>preguntanos/" title="Pregúntanos">Pregúntanos</a>
          </li>

          <li class="nav-item <?php if ($page_name == "archivo") {
                                echo "active";
                              } ?>">
            <a class="nav-link" href="<?php echo $dominio; ?>archivo/" tiles="Archivo">Archivo</a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="https://www.hacienda.gob.es/es-ES/Areas%20Tematicas/CMTabacos/Paginas/EstadisticassobreelMercadodeTabacos.aspx" title="Estadísticas CMT" target="_blank" rel="noreferrer noopener">Estadísticas CMT</a>
          </li>

          <li class="nav-item <?php if ($page_name == "contacto") {
                                echo "active";
                              } ?>">
            <a class="nav-link" href="<?php echo $dominio; ?>contacto/" title="Contacto">Contacto</a>
          </li>

        </ul>
      </div>

    </div>
  
  </nav>


</header>
<!-- end header -->


<!-- quick links - mobile only -->
<nav id="mobile_footer_nav" class="mobile-footer">

  <div class="mob-nav-btn">
    <a href="<?php echo $dominio; ?>buzon-de-denuncias/" title="TU DENUNCIA">
      <i class="fa-solid fa-triangle-exclamation"></i><span>Tu denuncia</span>
    </a>
  </div>

  <div class="mob-nav-btn">
    <a href="<?php echo $dominio; ?>contacto/" title="Contacto">
      <i class="fa-solid fa-envelope"></i>
    </a>
  </div>

  <div class="mob-nav-btn mobile-menu-button">
    <span id="hamburger-mobile-button" aria-label="mobile-menu">
      <i class="fa-solid fa-bars"></i>
      <i class="fa-solid fa-x"></i>
    </span>
  </div>

  

 

</nav>
<!-- quick links - mobile only -->