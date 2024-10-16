<?php
session_start();
$roles = array('0','1','2', '3');
include('includes/segur.php');

$path_relative = "../";
include('../includes/conexion.php');
?>
<html>
<head>
<meta charset="utf-8">

<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>

<link rel="stylesheet" href="css/custom.css" type="text/css">
<style type="text/css">
body {    background-color: #FFFFFF; font-family: Arial,Helvetica Neue,Helvetica,sans-serif; font-size: 12px;}
.content { padding:20px; }
a{ color:#000000; font-size:12px; padding-top:5px;}
.img_autor { -webkit-border-radius: 50%; -moz-border-radius: 50%; border-radius: 50%; border:1px solid #FFFFFF; vertical-align:middle; width: 25px;}

.attachments-browser {    position: relative;    width: 100%;    height: 100%;    overflow: hidden; }
ul { list-style: none; }
.attachments, .attachments-browser .uploader-inline {    position: absolute;    top: 10px;    left: 0;    right: 0px;    bottom: 0;    overflow: auto;    outline: 0; }
.attachments {    padding: 2px 8px 8px; }

.attachment {    position: relative;    float: left;    padding: 8px;    margin: 0;    color: #444;    cursor: pointer;    list-style: none;    text-align: center;    -webkit-user-select: none;    -moz-user-select: none;    -ms-user-select: none;    user-select: none;    width: 20%;  box-sizing: border-box; }

.attachment-preview::before {content: ""; display: block; padding-top: 100%; }
.attachment-preview {    position: relative;    box-shadow: inset 0 0 15px rgba(0,0,0,.1),inset 0 0 0 1px rgba(0,0,0,.05);    background: #eee;    cursor: pointer; }


.attachment .thumbnail {    overflow: hidden;    position: absolute;    top: 0;    right: 0;    bottom: 0;    left: 0;    opacity: 1;    transition: opacity .1s; }
.attachment .thumbnail .centered {    position: absolute;    top: 0;    left: 0;    width: 100%;    height: 100%;    -webkit-transform: translate(50%,50%);    transform: translate(50%,50%); }

.attachment .thumbnail .centered img {    -webkit-transform: translate(-50%,-50%);    transform: translate(-50%,-50%); }
.attachment .thumbnail img {    position: absolute; }
.attachment .thumbnail img {    top: 0;    left: 0;}
.attachment .landscape img {    max-height: 100%; }
.media-modal * {    box-sizing: content-box; }
img {    border: none; }

.attachment .thumbnail::after {    content: "";    display: block;    position: absolute;    top: 0;    left: 0;    right: 0;    bottom: 0;    box-shadow: inset 0 0 0 1px rgba(0,0,0,.1);    overflow: hidden; }
.wp-core-ui .attachment-preview {    cursor: pointer; }

.attachment .filename {    position: absolute;    left: 0;    right: 0;    bottom: 0;    overflow: hidden;    max-height: 100%;    word-wrap: break-word;    text-align: center;   font-size: 12px;  background: rgba(255,255,255,.8);    box-shadow: inset 0 0 0 1px rgba(0,0,0,.15); }
.attachment .thumbnail::after { content: "";    display: block;    position: absolute;    top: 0;    left: 0;    right: 0;    bottom: 0;    box-shadow: inset 0 0 0 1px rgba(0,0,0,.1);    overflow: hidden; }
.attachment .filename div {    padding: 5px 10px; }
</style>
<script>
function cierre()
{
	parent.ocultar_pie_galeria();

}
</script>
</head>
<body onload="cierre()">

<form name="form1" id="form1" method="post">

<div class="attachments-browser">
<?php

   //retorna un arreglo de los directorios que existen
   //en una ruta indicada en $directorio
   function lee_directorios($directorio)
   {
      $dires=array();
      $midir=opendir($directorio);
      $i=0;
      while($archivo=readdir($midir))
         if (is_dir($archivo) && $archivo!="." && $archivo!="..")
            $dires[$i++]=$archivo;
      return $dires;
   }
   //retorna un arreglo de archivos de un directorio dado
   //que cumplan con la extension indicada en $filtro
   function lee_archivos($directorio,$filtro)
   {
      $archs=array();
      $midir=opendir($directorio);
      $i=0;
      while($archivo=readdir($midir))
      {
         //$ext=substr($archivo,-3);
         //if (!is_dir($archivo) && ($ext==$filtro || !$filtro))
         if (!is_dir($archivo))
            $archs[$i++]=$archivo;
      }
      return $archs;
   }


$ruta = $_SESSION['dir_img_editor'];
$ficheros =  lee_archivos($ruta,"jpg");
//$ficheros =  lee_archivos($ruta,"");

if (sizeof($ficheros) > 0) {
   $x=0;

   ?><ul class="attachments">
   <?php
   for($i = 0; $i < sizeof($ficheros); ++$i)
   {
      $x=$x+1;
      $ext = substr($ficheros[$i],-3);
      if ($ext=="pdf" || $ext=="doc" || $ext=="docx" || $ext=="xls" || $ext=="xlsx"|| $ext=="zip") {
          ?>
            <li class="attachment">
               <a href="javascript:carga_img('<?php echo $ruta.$ficheros[$i]; ?>')">
                  <div class="attachment-preview type-application landscape">
                     <div class="thumbnail">				
                           <div class="centered">
                              <img src="images/document.png" class="icon" draggable="false">
                           </div>
                           <div class="filename">
                              <div><?php echo $ficheros[$i];?></div>
                           </div>
                     </div>			
                  </div>
                </a>
            </li>          
          <?php
      } elseif ($ext=="mp4") {
         ?>
         <li class="attachment">
            <a href="javascript:carga_img('<?php echo $ruta.$ficheros[$i]; ?>')">
               <div class="attachment-preview type-video landscape">
                  <div class="thumbnail">				
                        <div class="centered">
                           <img src="images/video.png" class="icon" draggable="false">
                        </div>
                        <div class="filename">
                           <div><?php echo $ficheros[$i];?></div>
                        </div>
                  </div>			
               </div>
            </a>
         </li>
         <?php 
      } else {
         ?>
         <li class="attachment">
            <a href="javascript:carga_img('<?php echo $ruta.$ficheros[$i]; ?>')">
               <div class="attachment-preview type-image landscape">
                  <div class="thumbnail">				
                        <div class="centered">
                           <img src="<?php echo $ruta . $ficheros[$i];?>">
                        </div>				
                  </div>			
               </div>
            </a>
         </li>
         <?php 
      }

	}
}
?>
</ul>
</div>



<script>
function carga_img(img)
{
	var valor = img;
	parent.poner_valor_galeria(valor);
}
</script>


	</form>

</body>
</html>