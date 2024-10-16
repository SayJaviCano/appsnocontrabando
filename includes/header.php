<header>
    <?php
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
    ?>
    <div class="bg-red">
        <div class="container header1">
            <div class="row justify-content-center align-items-center">
                <div class="col-12 col-md-6">
                    <a class="navbar-brand" href="<?php echo $dominio;?>" title="No Contrabando"><img src="<?php echo $dominio;?>images/no_contrabando_blanco.png" class="img-fluid" alt="No Contrabando"></a>
                </div>			
                <div class="col-6 text-right d-none d-md-block">
                    <a href="https://www.altadis.com/" title="Altadis" target="_blank" rel="noreferrer noopener"><img src="<?php echo $dominio;?>images/altadis_blanco.png" class="img-fluid" alt="Altadis"></a>
                </div>			
            </div>
        </div>
    </div>

    <div class="menu">
        <nav class="navbar navbar-expand-lg navbar-light" id="myNavbar">            
            <div class="container">
                <div class="collapse navbar-collapse" id="mainNav">
                    <ul class="navbar-nav">
                        <li class="nav-item <?php if ($page_name=="home") { echo "active"; } ?>"><a class="nav-link" href="<?php echo $dominio;?>" title="Inicio">Inicio</a></li>
                        <li class="nav-item <?php if ($page_name=="nosotros") { echo "active"; } ?>" ><a class="nav-link" href="<?php echo $dominio;?>nosotros/" title="Nosotros">Nosotros</a></li> 
                        <li class="nav-item dropdown <?php if ($n1=="category") { echo "active"; } ?>">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Secciones">Secciones</a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <div class="cab-dropdown">Secciones</div>
                            <?php
                                $sql = "SELECT * FROM categorias WHERE activo=1 ORDER BY orden, nombre";
                                $rs = $mysqli->query($sql); 
                                while ($fila = $rs->fetch_array(MYSQLI_ASSOC)){
                                    $activo = "";
                                    $categoria_nombre = $fila['nombre'];
                                    $categoria_slug = $fila['slug'];
                                    ?>
                                    <a class="dropdown-item <?php if ($page_name==$categoria_slug) { echo "active"; } ?>" href="<?php echo "$dominio" . "category/". "$categoria_slug/";?>" title="<?php echo $categoria_nombre;?>"><?php echo $categoria_nombre;?></a>
                                    <?php
                                }
                            ?>
                            </div>                            
                        </li>
                        <li class="nav-item <?php if ($page_name=="buzon-de-denuncias" || $page_name=="conoce-putos-de-venta-legal") { echo "active"; } ?>"><a class="nav-link" href="<?php echo $dominio;?>buzon-de-denuncias/" title="Buzón de denuncias">Denuncia</a></li>
                        <li class="nav-item <?php if ($page_name=="preguntanos" || $page_name=="preguntas-por-categorias" || $n1=="preguntanos") { echo "active"; } ?>"><a class="nav-link" href="<?php echo $dominio;?>preguntanos/" title="Pregúntanos">Pregúntanos</a></li>  
                        <li class="nav-item <?php if ($page_name=="archivo") { echo "active"; } ?>"><a class="nav-link" href="<?php echo $dominio;?>archivo/" tiles="Archivo">Archivo</a></li>   
                        <li class="nav-item"><a class="nav-link" href="https://www.hacienda.gob.es/es-ES/Areas%20Tematicas/CMTabacos/Paginas/EstadisticassobreelMercadodeTabacos.aspx" title="Estadísticas CMT" target="_blank"  rel="noreferrer noopener">Estadísticas CMT</a></li>              
                        <li class="nav-item <?php if ($page_name=="contacto") { echo "active"; } ?>"><a class="nav-link" href="<?php echo $dominio;?>contacto/" title="Contacto">Contacto</a></li>                     
                    </ul>

                    <?php if ($_SERVER['SERVER_NAME'] != "appsnocontrabando.com") { ?>
                        <ul class="navbar-nav ml-auto social">
                            <li class="nav-item"><a href="https://www.linkedin.com/showcase/no-contrabando-altadis/" title="Síguenos en Linkedin" target="_blank" class="linkedin" rel="noreferrer noopener"><i class="bi bi-linkedin"></i></a></li> 
                            <li class="nav-item"><a href="https://www.facebook.com/pages/No-Contrabando/382387838608294" title="Síguenos en Facebook" target="_blank" class="facebook" rel="noreferrer noopener"><i class="bi bi-facebook"></i></a></li>            
                            <li class="nav-item"><a href="https://twitter.com/No_Contrabando" title="Síguenos en Twitter" target="_blank" class="twitter" rel="noreferrer noopener"><i class="fa fa-twitter-x-square"></i></a></li>
                            <li class="nav-item"><a href="https://www.youtube.com/channel/UC0Ug8tmC0r2yWKCGp_5kqxQ/feed" title="Síguenos en YouTube" target="_blank" class="youTube" rel="noreferrer noopener"><i class="bi bi-youtube"></i></a></li>            
                        </ul>
                    <?php } ?>
                </div>
            </div>
        </nav>
    </div>
</header>

<?php if ($page_name!="buzon-de-denuncias") { ?>
<a class="btn btn-primary rounded-pill btn-lg btn-denuncia" href="<?php echo $dominio;?>buzon-de-denuncias/" title="Buzón de denuncias">TU DENUNCIA</a>
<?php } ?>

<?php if ($_SERVER['SERVER_NAME'] == "appsnocontrabando.com") {  ?>		
    <a class="app-share rounded-circle" href="https://appsnocontrabando.com/share/"><i class="bi bi-share-fill"></i></a>
<?php } ?>

<a class="btn-contact rounded-circle" href="<?php echo $dominio;?>contacto/"><i class="bi bi-envelope-fill"></i></a>
<a id="wsnavtoggle" class="navbar-toggle wsanimated-arrow rounded-circle" data-toggle="collapse" data-target="#mainNav"><span></span></a>
<a class="navbar-back rounded-circle"><i class="bi bi-arrow-left"></i></a>