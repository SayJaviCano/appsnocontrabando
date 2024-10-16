<?php
$t = LimpiaParametros((isset($_GET['t']) ? $_GET['t'] :""));
$v = LimpiaParametros((isset($_GET['v']) ? $_GET['v'] :""));

$t = htmlentities($t);
$v = htmlentities($v);

$p = LimpiaParametros(isset($_GET['p']) ? $_GET['p'] :0); 
if (!is_numeric($p)) { $p = 0; }

$request = ($_SERVER['REQUEST_URI']);
$pagina_actual = "https://";
if (!isset($_SERVER['HTTPS'])) { $pagina_actual = "http://"; } 
$pagina_actual .=  $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

$tmp = array_filter(explode('?',  $request));
$tmp[0] = str_replace($ruta_real, "", $tmp[0]);
$tmp[0] = str_replace("//", "/", $tmp[0]);

$arr_url	= ""; 
$arr_query	= "";
if (isset( $tmp[0]) ) {  $arr_url	= array_filter(explode('/', $tmp[0] )) ; }
if (isset( $tmp[1]) ) {  $arr_query	= array_filter(explode('&', $tmp[1] )) ; }

if (is_array($arr_query) ) { 
	for($bucle_i = 0; $bucle_i < count($arr_query); ++$bucle_i) {
		if (isset( $arr_query[$bucle_i]) ) { 
			$aux	= array_filter(explode('=', $arr_query[$bucle_i] )) ; 
			if (isset( $aux[0]) ) { 
				if ($aux[0]!="") { eval("\$". $aux[0] ."='" . LimpiaParametros($aux[1]) ."';"); }
			}		
		}	
	}
}
if (is_array($arr_url) ) {
	for($bucle_i = 0; $bucle_i <= count($arr_url); ++$bucle_i) {
		if (isset( $arr_url[$bucle_i]) ) { 
			if ($arr_url[$bucle_i]!=""){
				$arr_url[$bucle_i] = LimpiaParametros($arr_url[$bucle_i]);
			}
		}
	}
}
//----------------------------------
$aux_arr_url = $arr_url;
unset($arr_url);
$contador_i = 0;
for($bucle_i = 0; $bucle_i <= count($aux_arr_url); ++$bucle_i) {

	if (isset($aux_arr_url[$bucle_i])) {
		if ($aux_arr_url[$bucle_i]!=""){
			$arr_url[$contador_i] = $aux_arr_url[$bucle_i];
			$contador_i++;
		}
	}
}
//----------------------------------

$i=-1;
$page_name = "";
$shortlink = "";
$n1="";
if (isset($arr_url)) {
    if (is_array($arr_url)) {
        $i = count($arr_url)-1;
        $page_name = (isset($arr_url[$i]) ? $arr_url[$i] : "");
    }
}

$action = LimpiaParametros((isset($_GET['c']) ? $_GET['c'] :""));
if ($action == "click") {
	$token = LimpiaParametros((isset($_GET['t']) ? $_GET['t'] :""));
	$id_notificacion = LimpiaParametros((isset($_GET['n']) ? $_GET['n'] :""));
	if (!is_numeric($id_notificacion)) { $id_notificacion=0; }

	$id_dispositivo = 0;
	$sql = "SELECT * FROM app_dispositivos WHERE id='$token'";
	$rs = $mysqli->query($sql);
    if ($rs->num_rows>0) {
		$fila = $rs->fetch_assoc();
        $id_dispositivo = $fila['id'];
    }

	$sql = "SELECT * FROM app_ca_envios WHERE id_notificacion=$id_notificacion AND id_dispositivo=$id_dispositivo";
	$rs = $mysqli->query($sql);
    if ($rs->num_rows>0) {
		$fila = $rs->fetch_assoc();
        $id_envio = $fila['id'];

		$fecha = date("Y-m-d H:i:s");
        $sql2 = "UPDATE app_ca_envios SET clic=1, fecha_clic='$fecha' WHERE id=$id_envio";
        $rs2 = $mysqli->query($sql2); 
    }	

}




//echo "<br>i: $i<br>";
//echo "page_name: $page_name<br>";

$archivo_include = "404.php";
$meta_title = "Altadis - Líder en la lucha contra el comercio ilícito de tabaco";
$meta_description = "Líder en la lucha contra el comercio ilícito de tabaco";	
$meta_image =  $dominio . "images/no_contrabando_rojo.png";



$categoria_id_select = 0;
$faqs_categorias_id_select = 0;
if ($i == -1)		{
	if ($p != 0) {
		$sql = "SELECT * FROM contenidos WHERE activo=1 AND tipo='post' AND id='$p'";
        $rs = $mysqli->query($sql);
        if ($rs->num_rows > 0) {
			$fila = $rs->fetch_assoc();
			$slug = $fila["post_name"];
			header("HTTP/1.1 301 Moved Permanently");
			header("Location: " . $dominio . $slug . "/");
			die();
		}
	}	
	else {
        $page_name = "home";
        $archivo_include = "home.php";
    }
}	
elseif ($i == 0)	{ 

	if ($page_name == "share") /* share ***/
	{
		$url = $dominio; 
		if(isset( $_SERVER["HTTP_REFERER"]) ) { $url = $_SERVER["HTTP_REFERER"]; }
		header("Location: " . $url . "/");
		die();

	} else {

		$debug = LimpiaParametros((isset($_GET['debug']) ? $_GET['debug'] :""));

		if (isset($_SESSION['sess_admin_login'])) {
			
			if ($_SESSION['sess_admin_login'] == "true" || $debug=="1") {
				$sql="SELECT * FROM contenidos WHERE post_name='$page_name'";
			} else {
				$sql="SELECT * FROM contenidos WHERE activo=1 AND post_name='$page_name'";
			}

		} else {

			// 2023-03: Cargar post - incluso cuando esté inactivo:
			$sql="SELECT * FROM contenidos WHERE activo <= 1 AND post_name='$page_name'";

		}

		$rs = $mysqli->query($sql);
		if ($rs->num_rows > 0) {
			$fila = $rs->fetch_assoc();

			// 2023-03: Si no está activo, mandar al home:
			if ($fila["activo"] != 1) {
				
				header("Location: " . $dominio);
				die();

			}
			
			$id_noticia = $fila['id'];		
			$fecha = ($fila['fecha']);			
			$titulo = $fila['titulo'];	
			$entradilla = $fila['entradilla'];	
			$texto = $fila['texto'];
			$texto = str_replace('src="uploads/', 'src="' . $dominio . 'uploads/', $texto);
			$texto = str_replace('../uploads/', $dominio . 'uploads/', $texto);

			$imagen = $fila['imagen'];	
			$imagen_pie = $fila['imagen_pie'];	
			$video = $fila['video'];
			$livechat_vimeo = 0;

			
			if ($imagen!="")	{  
				$meta_image =  $dominio . $imagen;
				$imagen = '<img src="' . $dominio . $imagen .'" class="img-fluid">';
				$destacado_img = $fila['destacado_img'];
				if ($destacado_img=="1") { $imagen = ""; }
			}
			if ($video!="")		{  
				$video_aux = $video;
				$pos = strpos($video, "vimeo");
				if ($pos !== false) {
					$livechat_vimeo = $fila['livechat_vimeo'];					
					$arr_video	= explode('/', $video ); 
					$codigo_video = $arr_video[count($arr_video)-1];
					$video_aux = str_replace('https://vimeo.com/', 'https://player.vimeo.com/video/', $video_aux);
					$video_aux = $video_aux . "?quality=720p";
				}
				$imagen = '<div class="embed-responsive embed-responsive-16by9"><iframe class="embed-responsive-item" src="' . $video_aux. '" allowfullscreen frameborder="0"></iframe></div>'; 
			}

			$tags = $fila["tags"];
			
			$meta_title = $fila['meta_title'];	
			$meta_description = $fila['meta_description'];	
			if ($meta_title=="") 		{ $meta_title = "$titulo - NO Contrabando | Altadis"; }
			if ($meta_description=="")	{ $meta_description = $entradilla; }

			if ($fila['tipo'] == "post") {
				$page_name = "post";
				$archivo_include = "post_detalle.php";
			} else {
				$archivo_include = "pagina_detalle.php";			
			}
			
		} 

		elseif ($page_name == "pregunta") 
		{
			header("HTTP/1.1 301 Moved Permanently");
			header("Location: " . $dominio . "preguntanos/");
			die();
		}
	}
}
elseif ($i == 1)	{ 

	if ($arr_url[$i-1] == "category") /* CATEGORY ***/
	{
		$sql = "SELECT * FROM categorias WHERE activo=1 AND slug='$page_name'";
		$rs = $mysqli->query($sql); 
        if ($rs->num_rows > 0) {
            $fila = $rs->fetch_assoc();
			$categoria_id_select = $fila['id'];	
			$categoria_nombre_select = $fila['nombre'];	
			$archivo_include = "categoria_listado.php";
			$meta_title = "$categoria_nombre_select - NO Contrabando | Altadis";
			$meta_description = "";	
			$n1="category";


			if ($categoria_id_select==13)  /* CONGRESOS */
			{
				$archivo_include = "congresos_home.php";
			}
        }
    }
	elseif ($arr_url[$i-1] == "tag") /* ETIQUETAS */
	{

		$sql = "SELECT * FROM tags WHERE slug='$page_name'";
		$rs = $mysqli->query($sql); 
        if ($rs->num_rows > 0) {
            $fila = $rs->fetch_assoc();
			$tag_name_select = ucfirst($fila['nombre']);
			$tag_name_slug = $fila['slug'];	

			$meta_title = "$tag_name_select - NO Contrabando | Altadis";
			$meta_description = "";	

			$archivo_include = "tags_listado.php";
        }	
    }	
	elseif ($arr_url[$i-1] == "pregunta") 
	{
		header("HTTP/1.1 301 Moved Permanently");
		header("Location: " . $dominio . "preguntanos/");
		die();
	}
	elseif ($arr_url[$i-1] == "preguntanos") 
	{
		$n1="preguntanos";
		$sql="SELECT * FROM contenidos WHERE activo=1 AND tipo='page' AND post_name='preguntanos'";  
		$rs = $mysqli->query($sql);
        if ($rs->num_rows > 0) {
            $fila = $rs->fetch_assoc();    
            $id_noticia = $fila['id'];
            $titulo = $fila['titulo'];
            $entradilla = $fila['entradilla'];
            $texto = $fila['texto'];
            $texto = str_replace('src="uploads/', 'src="' . $dominio . 'uploads/', $texto);
            $texto = str_replace('../uploads/', $dominio . 'uploads/', $texto);
                
            $meta_title = $fila['meta_title'];
            $meta_description = $fila['meta_description'];
            $archivo_include = "pagina_detalle.php";

			$sql = "SELECT * FROM faqs_categorias WHERE activo=1 AND slug='$page_name'";
			$rs = $mysqli->query($sql);
			if ($rs->num_rows > 0) {
				$fila = $rs->fetch_assoc();    
				$faqs_categorias_id_select = $fila['id'];
				$faqs_categorias_nombre = $fila['nombre'];
					
				if ($meta_title=="")		{ $meta_title = "Preguntas $faqs_categorias_nombre - NO Contrabando | Altadis"; }
				if ($meta_description=="")	{ $meta_description = ""; }

				$archivo_include = "pagina_detalle.php";
			}			
        }

    }	
}
elseif ($i == 2)	{ 
	if ($arr_url[$i-2] == "category" && $arr_url[$i-1] == "congresos") /* CATEGORY / CONGRESOS ***/
	{
		$sql = "SELECT * FROM congresos_categorias WHERE activo=1 AND slug='$page_name'";
		$rs = $mysqli->query($sql); 
        if ($rs->num_rows > 0) {
            $fila = $rs->fetch_assoc();
			$congresos_categoria_id_select = $fila['id'];	
			$congresos_categoria_nombre_select = $fila['nombre'];	

			$archivo_include = "congresos_categorias_listado.php";
			$meta_title = "$congresos_categoria_nombre_select - NO Contrabando | Altadis";

			$meta_description = "";	
			$n1="category";
        }
	}
}
if ($archivo_include == "404.php") {
	header("HTTP/1.0 404 Not Found");
}

$user_agent = $_SERVER['HTTP_USER_AGENT'];
$apple_touch_icon = "apple-touch-icon.png";
if( stripos( $user_agent, 'Android' ) !== false ) $apple_touch_icon = "android-touch-icon.png";

$pagina_nocontrabando = $pagina_actual;
$pagina_nocontrabando = str_replace ("appsnocontrabando.com", "nocontrabando.altadis.com", $pagina_nocontrabando);

$meta_description = rip_tags($meta_description);

?>
<!doctype html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<meta name="title" content="<?php echo ($meta_title); ?>" />
	<meta name="description" content="<?php echo $meta_description; ?>"/>

    <title><?php echo ($meta_title); ?></title>
	<link rel="canonical" href="<?php echo mb_strtolower($pagina_actual);?>" />

	<meta property="og:locale" content="es_ES" />
	<meta property="og:type" content="website" />
	<meta property="og:title" content="<?php echo $meta_title;?>" />
	<meta property="og:description" content="<?php echo $meta_description;?>" />
	<meta property="og:url" content="<?php echo $pagina_actual;?>" />
	<meta property="og:site_name" content="Altadis NO Contrabando" />
	<meta property="og:image" content="<?php echo $meta_image;?>" />
	<script type='application/ld+json'>{"@context":"https:\/\/schema.org","@type":"WebSite","url":"https:\/\/nocontrabando.altadis.com\/","name":"Altadis NO Contrabando","potentialAction":{"@type":"SearchAction","target":"https:\/\/nocontrabando.altadis.com\/?s={search_term}","query-input":"required name=search_term"}}</script>

    <meta name="twitter:card" content="summary">
    <meta name="twitter:title" content="<?php echo $meta_title;?>">
    <meta name="twitter:description" content="<?php echo $meta_description;?>">
    <meta name="twitter:image" content="<?php echo $meta_image;?>">

	
    <!-- Favicons -->
	<link rel="icon" href="<?php echo $dominio;?>favicon.ico">
	<meta name="theme-color" content="#771425">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="mobile-web-app-capable" content="yes">
	<link rel="apple-touch-icon" href="<?php echo $dominio . $apple_touch_icon;?>?<?php echo time();?>"/>

	<link rel="manifest" href="<?php echo $dominio; ?>manifest.json" crossorigin="use-credentials">

	<link href="<?php echo $dominio;?>css/custom.css?<?php echo time();?>" rel="stylesheet" type="text/css">
	<script>dominio = "<?php echo $dominio;?>"; </script>
	<script async src="<?php echo $dominio;?>js/analytics.js?<?php echo time();?>"></script>

	<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
	<script src="<?php echo $dominio;?>/js/jquery.scrollto.min.js"></script>
	<?php
	$cookie_statistics = "";
	if(isset($_COOKIE['cookiepolicy-statistics'])) {	$cookie_statistics = $_COOKIE['cookiepolicy-statistics']; }

	if ($_SERVER['SERVER_NAME'] == "nocontrabando.altadis.com") {
    	if ($cookie_statistics=="true") {
            ?>
			<script>
			(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
			(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
			m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
			})(window,document,'script','//www.google-analytics.com/analytics.js','ga');		  
			ga('create', 'UA-61264419-1', 'auto');
			ga('send', 'pageview');		  
		  </script>
		  <?php
        }
    } elseif ($_SERVER['SERVER_NAME'] == "appsnocontrabando.com") {
        if ($cookie_statistics=="true") {
            ?>
			<!-- Global site tag (gtag.js) - Google Analytics 
			<script async src="https://www.googletagmanager.com/gtag/js?id=G-M0GYG7E9YN"></script>
			<script>
			window.dataLayer = window.dataLayer || [];
			function gtag(){dataLayer.push(arguments);}
			gtag('js', new Date());
			gtag('config', 'G-M0GYG7E9YN');
			</script> -->

			<!-- Google tag (gtag.js) -->
			<script async src="https://www.googletagmanager.com/gtag/js?id=G-6KV9WVDR61"></script>
			<script>
			window.dataLayer = window.dataLayer || [];
			function gtag(){dataLayer.push(arguments);}
			gtag('js', new Date());

			gtag('config', 'G-6KV9WVDR61');
			</script>

			<?php
        }
	}
	?>
  </head>
<body id="<?php echo $page_name;?>">
	<?php include("includes/$archivo_include");?>
<?php
/*
	<script src="https://www.gstatic.com/firebasejs/8.6.2/firebase-app.js"></script>
	<script src="https://www.gstatic.com/firebasejs/8.6.2/firebase-messaging.js"></script>	
	<script src="<?php echo $dominio;?>/js/firebase-init.js?<?php echo time();?>"></script>
*/
?>
	<script src="<?php echo $dominio;?>js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>	
	<script src="<?php echo $dominio;?>js/jquery.cookie.js"></script>	
	<script src="<?php echo $dominio;?>js/jquery.easing.1.3.js"></script>	
	<script src="<?php echo $dominio;?>js/jquery.fancybox.min.js"></script>	

	<script src="<?php echo $dominio;?>js/comun.js?<?php echo time();?>"></script>

<?php
$pop_banner = LimpiaParametros((isset($_GET['pop']) ? $_GET['pop'] :""));
$pop_banner = htmlentities($pop_banner);
if ($pop_banner=="appNoContrabando") {

	$dispositivo = "Escritorio";
	if ($detect->isMobile()) {
		$dispositivo = "Mobile";
	} elseif ($detect->isTablet()) {
		$dispositivo = "Tablet";
	}

	$user_agent = $_SERVER['HTTP_USER_AGENT'];
	$plataforma = getPlatforma($user_agent);


	if ($dispositivo=="Escritorio") {

	} else {


		if ($plataforma=="Android") {
			$url_app = "https://play.google.com/store/apps/details?id=com.saysawa.nocontrabando";
		} else {
			$url_app = "https://apps.apple.com/gb/app/no-contrabando/id1568160667";
		}


		/*
		$ip = $_SERVER['REMOTE_ADDR'];
		if ($ip=="88.0.62.157")
		{
			echo "<pre>";

			echo "<br>dispositivo: $dispositivo<br>"; 	
			echo "<br>plataforma: $plataforma<br>"; 

			echo "<br>url_app: $url_app<br>"; 
			echo "</pre><br><br>";
		}
		*/

		//header("Location: $url_app"); 
		?><script>window.location.href = "<?php echo $url_app; ?>"</script><?php
		die();	
	}

?>	
<div id="bannerModal" class="animated-modal text-center p-0" style="display:none">
	<div class="container">
		<div class="row">		
			<img src="<?= $dominio;?>images/descargate-app.gif" class="img-responsive" alt="Descárgate la App NO CONTRABANDO">
		</div>
	</div>
</div>
<script>
$(document).ready(function () { 
	$.fancybox.open({
		src  : '#bannerModal',
		opts : {
			afterShow : function( instance, current ) {
				//setTimeout(function(){ $.fancybox.close(); }, 4000);								
			}
		}
	});	

});	
</script>
<?php } ?>

</body>
</html>