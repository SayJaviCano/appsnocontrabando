<?php
	require($path_relative . "global.inc");
	date_default_timezone_set('Europe/Madrid');	

	$array_meses = array('enero','febrero','marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre');

/*
	ini_set('display_errors', '1');
    ini_set('upload_max_size' , '250M');
    ini_set('post_max_size', '200M');
	ini_set('max_execution_time', '600');
*/		

	global $mysqli;

	$mysqli = new mysqli($maquinaConexion, $usuarioConexion,  $claveConexion, $DB);
	if ($mysqli->connect_errno) {
		echo "Lo sentimos, este sitio web está experimentando problemas.";
		echo "Error: Fallo al conectarse a MySQL debido a: \n";
		echo "Errno: " . $mysqli->connect_errno . "\n";
		echo "Error: " . $mysqli->connect_error . "\n";
		exit;
	}

	if (!$mysqli->set_charset("utf8")) {
		printf("Error cargando el conjunto de caracteres utf8: %s\n", $mysqli->error);
		exit();
	} else {
		//printf("Conjunto de caracteres actual: %s\n", $mysqli->character_set_name());
	}	

	if(!function_exists("GetSQLValueString")) {
		function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") {
			$theValue = (!get_magic_quotes_gpc()) ? addslashes($theValue) : $theValue;
			switch ($theType) {
				case "text":
				$theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
				break;
				case "long":
				case "int":
				$theValue = ($theValue != "") ? intval($theValue) : "NULL";
				break;
				case "double":
				$theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
				break;
				case "date":
				$theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
				break;
				case "defined":
				$theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
				break;
			}
			return $theValue;
		}
	}


function strip_param_from_url( $url, $param )
{
	$base_url = strtok($url, '?');              // Get the base url
	$parsed_url = parse_url($url);              // Parse it 
	$query = "";
	if (isset($parsed_url['query'])) { $query = $parsed_url['query']; /* Get the query string */ } 
	
	parse_str( $query, $parameters );           // Convert Parameters into array
	unset( $parameters[$param] );               // Delete the one you want
	$new_query = http_build_query($parameters); // Rebuilt query string

	$new_url = $base_url;
	if ($new_query != "") { 
		$new_url .= '?'. $new_query;
	}
	return $new_url;            // Finally url is ready
}


function getPlatforma($user_agent) {
	$plataformas = array(
	   'Windows 10' => 'Windows NT 10.0+',
	   'Windows 8.1' => 'Windows NT 6.3+',
	   'Windows 8' => 'Windows NT 6.2+',
	   'Windows 7' => 'Windows NT 6.1+',
	   'Windows Vista' => 'Windows NT 6.0+',
	   'Windows XP' => 'Windows NT 5.1+',
	   'Windows 2003' => 'Windows NT 5.2+',
	   'Windows' => 'Windows otros',
	   'iPhone' => 'iPhone',
	   'iPad' => 'iPad',
	   'Mac OS X' => '(Mac OS X+)|(CFNetwork+)',
	   'Mac otros' => 'Macintosh',
	   'Android' => 'Android',
	   'BlackBerry' => 'BlackBerry',
	   'Linux' => 'Linux',
	);
	foreach($plataformas as $plataforma=>$pattern){
	   if (preg_match('/(?i)'.$pattern.'/', $user_agent))
		  return $plataforma;
	}
	return 'Otras';
}

function add_days($date, $days) {
    $timeStamp = strtotime(date('Y-m-d',$date));
    $timeStamp+= 24 * 60 * 60 * $days;

    // ...clock change....
    if (date("I",$timeStamp) != date("I",$date)) {
        if (date("I",$date)=="1") { 
            // summer to winter, add an hour
            $timeStamp+= 60 * 60; 
        } else {
            // summer to winter, deduct an hour
            $timeStamp-= 60 * 60;           
        } // if
    } // if
    $cur_dat = mktime(0, 0, 0, 
                      date("n", $timeStamp), 
                      date("j", $timeStamp), 
                      date("Y", $timeStamp)
                     ); 
    return $cur_dat;
}

function acentos($Str)
{
	$Str = utf8_decode($Str);
	$Str = str_replace ("á", "&aacute;", "$Str");
	$Str = str_replace ("é", "&eacute;", "$Str");
	$Str = str_replace ("í", "&iacute;", "$Str");
	$Str = str_replace ("ó", "&oacute;", "$Str");
	$Str = str_replace ("ú", "&uacute;", "$Str");

	return $Str;
}


function acentos_buscar($cadena)
{

	$cadena=strtolower($cadena);
	$nueva_cadena = "";
	for($i=0; $i < strlen($cadena); $i++) 
	{ 
		$ac = substr($cadena, $i, 1);

		if		($ac=="a" || $ac=="à" || $ac=="á")	{ $nueva_cadena = $nueva_cadena . "[aàá]"; }
		elseif	($ac=="e" || $ac=="é" || $ac=="è")	{ $nueva_cadena = $nueva_cadena . "[eéè]"; }
		elseif	($ac=="i" || $ac=="ì" || $ac=="í")	{ $nueva_cadena = $nueva_cadena . "[iìí]"; }
		elseif	($ac=="o" || $ac=="ò" || $ac=="ó")	{ $nueva_cadena = $nueva_cadena . "[oòó]"; }
		elseif	($ac=="u" || $ac=="ù" || $ac=="ú")	{ $nueva_cadena = $nueva_cadena . "[uùú]"; }
		elseif	($ac=="n" || $ac=="ñ")				{ $nueva_cadena = $nueva_cadena . "[nñÑ]"; }
		else										{ $nueva_cadena = $nueva_cadena . $ac;     }

	} 
	return $nueva_cadena;
}

function sin_acentos($cadena)
{
	$cadena = ($cadena);
	$cadena=str_replace("á", "a",$cadena);
	$cadena=str_replace("é", "e",$cadena);
	$cadena=str_replace("í", "i",$cadena);
	$cadena=str_replace("ó", "o",$cadena);		
	$cadena=str_replace("ú", "u",$cadena);

	$cadena=str_replace("Á", "a",$cadena);
	$cadena=str_replace("É", "e",$cadena);
	$cadena=str_replace("Í", "i",$cadena);
	$cadena=str_replace("Ó", "o",$cadena);		
	$cadena=str_replace("Ú", "u",$cadena);

	return $cadena;
}


function LimpiaParametros($campo)
{
	$Elcampo= $campo;
	$copia_campo = "";
	if(!empty($campo))
	{
		$Elcampo = trim (str_replace ("'", "''", "$campo"));
		$Elcampo = trim (str_replace ("--", "", "$Elcampo"));
		$copia_campo = ($Elcampo);

		$ArrVocablos=array("WHERE ","FROM ","SELECT ","UPDATE ","DELETE ","DROP ","FORMAT ","LIKE ","ISNULL ");

		for($i=0;$i<count($ArrVocablos);$i++)
		{	$pos=null;
			$aux = strtoupper($Elcampo);
			$sujeto = strtoupper($Elcampo);
			$patron = '/' . $ArrVocablos[$i]. '/';
			$valor = preg_match($patron, $sujeto, $coincidencias, PREG_OFFSET_CAPTURE);
			if($valor==1) //  found...
			{
				$copia_campo="";
				break;
			}
		}			
	}

	$copia_campo = trim (str_replace ("\\", "", "$copia_campo"));
	return ($copia_campo);
}

function limpia_cadena($cadena)
{
	$cadena=str_replace("á", "a",$cadena);
	$cadena=str_replace("é", "e",$cadena);
	$cadena=str_replace("í", "i",$cadena);
	$cadena=str_replace("ó", "o",$cadena);		
	$cadena=str_replace("ú", "u",$cadena);

	$cadena=str_replace("Á", "a",$cadena);
	$cadena=str_replace("É", "e",$cadena);
	$cadena=str_replace("Í", "i",$cadena);
	$cadena=str_replace("Ó", "o",$cadena);		
	$cadena=str_replace("Ú", "u",$cadena);

	$cadena=str_replace("ñ", "n",$cadena);
	$cadena=str_replace(" ", "-",$cadena);		
	$cadena=str_replace(":", "-",$cadena);	
	$cadena=str_replace("!", "",$cadena);		
	$cadena=str_replace("'", "",$cadena);		
	$cadena=str_replace("\"", "",$cadena);		
	$cadena=str_replace("\\", "",$cadena);		
	$cadena=str_replace("·", "",$cadena);		
	$cadena=str_replace(",", "",$cadena);		
	$cadena=str_replace("$", "",$cadena);		
	$cadena=str_replace("%", "",$cadena);		
	$cadena=str_replace("&", "",$cadena);		
	$cadena=str_replace("/", "",$cadena);		
	$cadena=str_replace("(", "",$cadena);		
	$cadena=str_replace(")", "",$cadena);
	$cadena=str_replace("_", "-",$cadena);
	$cadena=str_replace("..", "",$cadena);
	$cadena=str_replace("?", "",$cadena);		
	$cadena=str_replace("¿", "",$cadena);		

	$cadena=str_replace("«", "",$cadena);	
	$cadena=str_replace("»", "",$cadena);
	$cadena=str_replace("“", "",$cadena);	
	$cadena=str_replace("”", "",$cadena);
	$cadena=str_replace("‘", "",$cadena);	
	$cadena=str_replace("’", "",$cadena);
	$cadena=str_replace("„", "",$cadena);	
	$cadena=str_replace("“", "",$cadena);	
	$cadena=str_replace("´", "",$cadena);	

	$cadena=str_replace("â", "",$cadena);
	$cadena=str_replace("ã", "",$cadena);
	$cadena=str_replace("ä", "",$cadena);
	$cadena=str_replace("å", "",$cadena);
	$cadena=str_replace("æ", "",$cadena);
	$cadena=str_replace("ç", "",$cadena);

	$cadena=str_replace("©", "",$cadena);
	$cadena=str_replace("®", "",$cadena);

	$cadena=str_replace("@", "",$cadena);

	$cadena=str_replace("ü", "u",$cadena);
	$cadena=str_replace("º", "um",$cadena);	

	$cadena=str_replace("---", "-",$cadena);
	$cadena=str_replace("--", "-",$cadena);	

	$cadena=strtolower($cadena);
	
	return $cadena;
}

function cambiaf_a_normal($fecha){ 
	$arry_fecha = explode(" ", $fecha);	
	$mifecha = explode("-", $arry_fecha[0]);	
    $lafecha=$mifecha[2]."/".$mifecha[1]."/".$mifecha[0]; 
    return $lafecha; 
} 

function cambiaf_a_normal_hora($fecha_hora){ 
	$arry_fecha = explode(" ", $fecha_hora);	
	$mifecha = explode("-", $arry_fecha[0]);	
    $lafecha=$mifecha[2]."/".$mifecha[1]."/".$mifecha[0] . " " . $arry_fecha[1] ; 
    return $lafecha; 
} 

function cambia_fecha_a_mysql($fecha){ 
	$mifecha = explode("/", $fecha);	
    $lafecha = $mifecha[2]."-".$mifecha[1]."-".$mifecha[0]; 
    return $lafecha; 
} 

//Import the PHPMailer class into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
require $path_relative . 'includes/src/Exception.php';
require $path_relative . 'includes/src/PHPMailer.php';
require $path_relative . 'includes/src/SMTP.php';

function SendEmail($from, $fromname, $to, $toname, $subject, $html, $plain, $tobcc, $tobccname, $adjunto, $adjuntotmp) {


	global $mail_host, $mail_username, $mail_password;

	$mail = new PHPMailer();

	//$mail->isSMTP();
	$mail->CharSet = 'UTF-8';
	$mail->SMTPAuth = true;
	$mail->SMTPAutoTLS = false;
	$mail->SMTPDebug = (DEBUG ? 2 : 0);

	$mail->Host     = $mail_host;
	$mail->Username = $mail_username;
	$mail->Password = $mail_password;

	$mail->setFrom($from, $fromname);
	$mail->addReplyTo($from, $fromname);

	if ($to!="") { 
		if (is_array($to)) {
			$arr_rows=count($to);			
			for ($i=0; $i<$arr_rows; $i++) {			
				$mail->AddAddress($to[$i],$toname[$i]);
			}
		} else {
			$mail->AddAddress($to,$toname);
		}
	}	

	if ($tobcc!="") { 
		if (is_array($tobcc)) {
			$arr_rows=count($tobcc);			
			for ($i=0; $i<$arr_rows; $i++) {			
				$mail->AddBCC($tobcc[$i],$tobccname[$i]);
			}
		} else {
			$mail->AddBCC($tobcc,$tobccname); 
		}
	}
	if ($adjunto != "") {
		$mail->AddAttachment($adjuntotmp, $adjunto);
	}

	//$mail->WordWrap = 50;
	if ($html!="") {                             
		$mail->IsHTML(true);                         
		$mail->Body     =  $html;
		$mail->AltBody  =  $plain;
	} else {
		$mail->IsHTML(false);                         
		$mail->Body     =  $plain;	
	}
	$mail->Subject  =  $subject;
	

	if(!$mail->Send()) {return $mail->ErrorInfo; }
}

function limpia_post_name($cadena)
{
	
	$cadena=str_replace(".", "-",$cadena);

	$cadena=str_replace("á", "a",$cadena);
	$cadena=str_replace("é", "e",$cadena);
	$cadena=str_replace("í", "i",$cadena);
	$cadena=str_replace("ó", "o",$cadena);		
	$cadena=str_replace("ú", "u",$cadena);

	$cadena=str_replace("Á", "a",$cadena);
	$cadena=str_replace("É", "e",$cadena);
	$cadena=str_replace("Í", "i",$cadena);
	$cadena=str_replace("Ó", "o",$cadena);		
	$cadena=str_replace("Ú", "u",$cadena);
	$cadena=str_replace("Ñ", "n",$cadena);

	$cadena=str_replace("ñ", "n",$cadena);
	$cadena=str_replace(" ", "-",$cadena);		
	$cadena=str_replace(":", "-",$cadena);	
	$cadena=str_replace("!", "",$cadena);		
	$cadena=str_replace("'", "",$cadena);		
	$cadena=str_replace("\"", "",$cadena);		
	$cadena=str_replace("\\", "",$cadena);		
	$cadena=str_replace("·", "",$cadena);		
	$cadena=str_replace(",", "",$cadena);		
	$cadena=str_replace("$", "",$cadena);		
	$cadena=str_replace("%", "",$cadena);		
	$cadena=str_replace("&", "",$cadena);		
	$cadena=str_replace("/", "",$cadena);		
	$cadena=str_replace("(", "",$cadena);		
	$cadena=str_replace(")", "",$cadena);
	$cadena=str_replace("_", "-",$cadena);
	$cadena=str_replace("..", "",$cadena);
	$cadena=str_replace("?", "",$cadena);		
	$cadena=str_replace("¿", "",$cadena);	
			
	$cadena=str_replace("´", "",$cadena);	

	$cadena=str_replace("«", "",$cadena);	
	$cadena=str_replace("»", "",$cadena);
	$cadena=str_replace("“", "",$cadena);	
	$cadena=str_replace("”", "",$cadena);
	$cadena=str_replace("‘", "",$cadena);	
	$cadena=str_replace("’", "",$cadena);
	$cadena=str_replace("„", "",$cadena);	
	$cadena=str_replace("“", "",$cadena);


	$cadena=str_replace("â", "",$cadena);
	$cadena=str_replace("ã", "",$cadena);
	$cadena=str_replace("ä", "",$cadena);
	$cadena=str_replace("å", "",$cadena);
	$cadena=str_replace("æ", "",$cadena);
	$cadena=str_replace("ç", "",$cadena);

	$cadena=str_replace("©", "",$cadena);
	$cadena=str_replace("®", "",$cadena);
	$cadena=str_replace("#", "",$cadena);
	$cadena=str_replace("<", "",$cadena);	
	$cadena=str_replace(">", "",$cadena);	

	$cadena=str_replace("@", "",$cadena);

	$cadena=str_replace("ü", "u",$cadena);

	$cadena=str_replace("--", "-", $cadena);

	$cadena = strtolower($cadena);

	return $cadena;
}
function inStr ($needle, $haystack) 
{ 
  $needlechars = strlen($needle); //gets the number of characters in our needle 
  $i = 0; 
  for($i=0; $i < strlen($haystack); $i++) //creates a loop for the number of characters in our haystack 
  { 
    if(substr($haystack, $i, $needlechars) == $needle) //checks to see if the needle is in this segment of the haystack 
    { 
      return TRUE; //if it is return true 
    } 
  } 
  return FALSE; //if not, return false 
} 


function limpiarHTML($cadena,$opcion) {
	return strip_tags($cadena);
}

function rip_tags($string) {
   
    $string = preg_replace ('/<[^>]*>/', ' ', $string);   
    // ----- remove control characters -----
    $string = str_replace("\r", '', $string);    // --- replace with empty space
    $string = str_replace("\n", ' ', $string);   // --- replace with space
    $string = str_replace("\t", ' ', $string);   // --- replace with space   
    // ----- remove multiple spaces -----
    $string = trim(preg_replace('/ {2,}/', ' ', $string));   
    return $string;
}

function tx_corto($texto, $num_caracteres)
{
	$resumen = $texto;
	if ($num_caracteres < strlen($resumen) )
	{
		$posicion = strpos($resumen, " ", $num_caracteres);
	
		if ($posicion === false) {
		   //echo "No se encontro '$caracter' en la cadena '$mi_cadena'";
		} else {
			$resumen = substr($resumen, 0, ($posicion)); 
		}
	
	}
	//$resumen = str_replace ("\n", "<br>", "$resumen");
	return $resumen;
}

function right($value, $count){
    $value = substr($value, (strlen($value) - $count), strlen($value));
    return $value;
}

function encrypt($string, $key) {
   $result = '';
   for($i=0; $i<strlen($string); $i++) {
      $char = substr($string, $i, 1);
      $keychar = substr($key, ($i % strlen($key))-1, 1);
      $char = chr(ord($char)+ord($keychar));
      $result.=$char;
   }
   return base64url_encode($result);
}
function decrypt($string, $key) {
   $result = '';
   $string = base64url_decode($string);
   for($i=0; $i<strlen($string); $i++) {
      $char = substr($string, $i, 1);
      $keychar = substr($key, ($i % strlen($key))-1, 1);
      $char = chr(ord($char)-ord($keychar));
      $result.=$char;
   }   
   return $result;
}

function base64url_encode( $data ){
	return rtrim( strtr( base64_encode( $data ), '+/', '-_'), '=');
  }
  
  function base64url_decode( $data ){
	return base64_decode( strtr( $data, '-_', '+/') . str_repeat('=', 3 - ( 3 + strlen( $data )) % 4 ));
  }


function getYouTubeIdFromURL($url)
{
  $url_string = parse_url($url, PHP_URL_QUERY);
  parse_str($url_string, $args);
  return isset($args['v']) ? $args['v'] : false;
}

function f_optim_img($carpeta, $nombre_archivo) 
{

	$ext = array("jpg"); // EXTENSION DE LOS ARCHIVOS DE IMAGENES 
	$ancho_nuevo = 1200; // ANCHO NUEVO DEL ARCHIVO 

	$archivo = pathinfo($carpeta.$nombre_archivo); 
	if (in_array(strtolower($archivo['extension']),$ext)) 
	{ 

		if(strtolower($archivo['extension'])=="gif")		{ $img = imagecreatefromgif($carpeta.$nombre_archivo); }
		else if(strtolower($archivo['extension'])=="jpg")	{ $img = imagecreatefromjpeg($carpeta.$nombre_archivo);}
		else if(strtolower($archivo['extension'])=="png")	{ $img = imagecreatefrompng($carpeta.$nombre_archivo); } 

		$ancho = imagesx($img); 
		$altura = imagesy($img); 

		if ($ancho > $ancho_nuevo)	{ $altura_nueva = floor($altura*($ancho_nuevo/$ancho)); }
		else						{ $altura_nueva = $altura; $ancho_nuevo = $ancho; }

		$tmp_img = imagecreatetruecolor($ancho_nuevo,$altura_nueva); 
		imagecopyresized($tmp_img,$img,0,0,0,0,$ancho_nuevo,$altura_nueva,$ancho,$altura); 
		if(strtolower($archivo['extension'])=="gif")		{ imagegif	($tmp_img,$carpeta.$nombre_archivo);		}
		else if(strtolower($archivo['extension'])=="jpg")	{ imagejpeg	($tmp_img,$carpeta.$nombre_archivo, 95);	}
		else if(strtolower($archivo['extension'])=="png")	{ imagepng	($tmp_img,$carpeta.$nombre_archivo);		} 


	} 

}
function time_diff($dt1,$dt2){
    $y1 = substr($dt1,0,4);
    $m1 = substr($dt1,5,2);
    $d1 = substr($dt1,8,2);
    $h1 = substr($dt1,11,2);
    $i1 = substr($dt1,14,2);
    $s1 = substr($dt1,17,2);   

    $y2 = substr($dt2,0,4);
    $m2 = substr($dt2,5,2);
    $d2 = substr($dt2,8,2);
    $h2 = substr($dt2,11,2);
    $i2 = substr($dt2,14,2);
    $s2 = substr($dt2,17,2);   

    $r1=date('U',mktime($h1,$i1,$s1,$m1,$d1,$y1));
    $r2=date('U',mktime($h2,$i2,$s2,$m2,$d2,$y2));
    return ($r1-$r2);

} 
function generar_clave(){

	$password = "";

	$str = "abcdefghijklmnopqrstuvwxyz";
	$password .= substr($str,rand(0,25),1);

	$str = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
	for($i=0;$i<2;$i++) {
		$password .= substr($str,rand(0,25),1);
	}
	$str = "1234567890";
	for($i=0;$i<2;$i++) {
		$password .= substr($str,rand(0,9),1);
	}	
	$str = "abcdefghijklmnopqrstuvwxyz";
	$password .= substr($str,rand(0,25),1);	

	$str = "$%&@#";
	$password .= substr($str,rand(0,4),1);

	$str = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890$%&@#";
	for($i=0;$i<3;$i++) {
		$password .= substr($str,rand(0,66),1);
	}

    return ($password);
} 

function zero_fill ($valor, $long = 0)
{
    return str_pad($valor, $long, '0', STR_PAD_LEFT);
}


/**
 * Increases or decreases the brightness of a color by a percentage of the current brightness.
 *
 * @param   string  $hexCode        Supported formats: `#FFF`, `#FFFFFF`, `FFF`, `FFFFFF`
 * @param   float   $adjustPercent  A number between -1 and 1. E.g. 0.3 = 30% lighter; -0.4 = 40% darker.
 *
 * @return  string
 */
function adjustBrightness($hexCode, $adjustPercent) {
    $hexCode = ltrim($hexCode, '#');

    if (strlen($hexCode) == 3) {
        $hexCode = $hexCode[0] . $hexCode[0] . $hexCode[1] . $hexCode[1] . $hexCode[2] . $hexCode[2];
    }

    $hexCode = array_map('hexdec', str_split($hexCode, 2));

    foreach ($hexCode as & $color) {
        $adjustableLimit = $adjustPercent < 0 ? $color : 255 - $color;
        $adjustAmount = ceil($adjustableLimit * $adjustPercent);

        $color = str_pad(dechex($color + $adjustAmount), 2, '0', STR_PAD_LEFT);
    }

    return '#' . implode($hexCode);
}

function getSiteMap()
{
	global $ruta_real;

   $datos = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n" .
			"<?xml-stylesheet type=\"text/xsl\" href=\"$dominio" . "main-sitemap.xsl\"?>\n" .
			"<urlset xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xmlns:image=\"http://www.google.com/schemas/sitemap-image/1.1\" xsi:schemaLocation=\"http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd\" xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";

	$archivo = $_SERVER['DOCUMENT_ROOT'] . $ruta_real . "/sitemap.xml";
	$ar = fopen($archivo,"w") or die("Problemas en la creacion");
	fputs($ar, $datos);

	fputs($ar,  getSiteMapItems());     

	fputs($ar, "</urlset>\n");

	fclose($ar);
}


function makeIso8601TimeStamp ($dateTime) {
    if (!$dateTime) {
        $dateTime = date('Y-m-d H:i:s');
    }
    if (is_numeric(substr($dateTime, 11, 1))) {
        $isoTS = substr($dateTime, 0, 10) ."T"
                 .substr($dateTime, 11, 8) ."+00:00";
    }
    else {
        $isoTS = substr($dateTime, 0, 10);
    }
    return $isoTS;
}
 

function getSiteMapItems()
{
	global $dominio;
	global $mysqli;

	$site = $dominio;

	$changefreqs = array ("a" => "always", "h" => "hourly", "d" => "daily", "w" => "weekly", "m" => "monthly", "y" => "yearly", "n" => "never");
	$cadena = "";

	$site = $dominio;
	$lastmod = makeIso8601TimeStamp('');
	$cadena .= toUrl( $site ."", "daily", '0.4', $lastmod );


	// *********************** CATEGORIAS / NOTICIAS ****************************************************
	$sql = "SELECT * FROM categorias WHERE activo=1 ORDER BY orden, nombre";
	$rs = $mysqli->query($sql); 
	while ($fila = $rs->fetch_array(MYSQLI_ASSOC)) {
		$categoria_id = $fila['id'];
        $categoria_slug = $fila['slug'];
		$categoria_url = $dominio . "category/". "$categoria_slug/";	
		$lastmod = makeIso8601TimeStamp('');
		$cadena .= toUrl( $categoria_url, "weekly", "0.6", $lastmod); 

		if ($categoria_id==13)  // Congresos
		{
			$sql2 = "SELECT * FROM congresos_categorias WHERE activo=1 ORDER BY orden, nombre";
			$rs2 = $mysqli->query($sql2); 
			while ($fila2 = $rs2->fetch_array(MYSQLI_ASSOC)){
				$congresos_categorias_id = $fila2['id'];
				$slug = $fila2['slug'];
				$congresos_categorias_link = $dominio . "category/congresos/$slug/";				
				$lastmod = makeIso8601TimeStamp('');
				$cadena .= toUrl($congresos_categorias_link, "daily", "0.5", $lastmod); 


				$sql3 = "SELECT * FROM contenidos WHERE activo=1 AND tipo='post' AND id_congresos_categoria=$congresos_categorias_id";	
				$sql3.= " ORDER BY fecha DESC, id DESC";
				$rs3 = $mysqli->query($sql3); 
				while ($fila3 = $rs3->fetch_array(MYSQLI_ASSOC)) {
					$id = $fila3['id'];		
					$fecha = $fila3['fecha'];
					$slug = $fila3["post_name"];
					$noticia_link = $dominio . $slug . "/";

					$lastmod = makeIso8601TimeStamp($fecha);
					$cadena .= toUrl($noticia_link, "daily", "0.5", $lastmod); 
				}

			}


		} else {
			$sql2 = "SELECT contenidos.id, contenidos.post_name, contenidos.fecha, contenidos.titulo, contenidos.entradilla, contenidos.texto, contenidos.imagen, contenidos.imagen_pie, contenidos.video, contenidos.tags, contenidos_categorias.id_categoria FROM contenidos";		
			$sql2.= " INNER JOIN contenidos_categorias ON contenidos_categorias.id_contenido = contenidos.id";		
			$sql2.= " WHERE contenidos.activo=1 AND contenidos.tipo='post'";		
			$sql2.= " AND contenidos_categorias.id_categoria=$categoria_id";	
			$sql2.= " GROUP BY contenidos.id";		
			$sql2.= " ORDER BY contenidos.fecha DESC, contenidos.id DESC";

			$rs2 = $mysqli->query($sql2); 
			while ($fila2 = $rs2->fetch_array(MYSQLI_ASSOC)) {
				$id = $fila2['id'];		
				$fecha = $fila2['fecha'];
				$slug = $fila2["post_name"];
				$noticia_link = $dominio . $slug . "/";

				$lastmod = makeIso8601TimeStamp($fecha);
				$cadena .= toUrl($noticia_link, "daily", "0.5", $lastmod); 
			}
		}
	}


	// *********************** PAGINAS ****************************************************
	$sql2 = "SELECT * FROM contenidos WHERE activo=1 AND tipo='page' AND titulo<>''";
	$sql2.= " ORDER BY titulo";
	$rs2 = $mysqli->query($sql2); 
	while ($fila2 = $rs2->fetch_array(MYSQLI_ASSOC)) {
		$id = $fila2['id'];		
		$slug = $fila2["post_name"];
		$link = $dominio . $slug . "/";

		$lastmod = makeIso8601TimeStamp('');
		$cadena .= toUrl($link, "daily", "0.6", $lastmod); 
	}
	return $cadena;
}

function toUrl($href, $changefreq, $priority, $lastmod)
{
	if ($lastmod=="")	{	$lastmod= makeIso8601TimeStamp('');	}
	$datos =	"<url>\n" .
			"   <loc>$href</loc>\n".
			"   <lastmod>$lastmod</lastmod>\n".
			"   <changefreq>$changefreq</changefreq>\n" .
			"   <priority>$priority</priority>\n".
			"</url>\n";
	return $datos;
} 

function is_bot(){
 
	$USER_AGENT = $_SERVER['HTTP_USER_AGENT'];
    $bots = array(
		'ABCdatos BotLink',
		'Acme.Spider',
		'Ahoy! The Homepage Finder',
		'AhrefsBot', 
		'Alkaline',
		'Anthill',
		'Walhello appie',
		'Arachnophilia',
		'Arale',
		'Araneo',
		'AraybOt',
		'ArchitextSpider',
		'Aretha',
		'ARIADNE',
		'arks',
		'ASpider (Associative Spider)',
		'ATN Worldwide',
		'Atomz.com Search Robot',
		'AURESYS',
		'BackRub',
		'Baiduspider',
		'bayspider',
		'BBot',
		'Big Brother',
		'bingbot', 
		'Bjaaland',
		'BlackWidow',
		'Die Blinde Kuh',
		'Bloodhound',
		'bot', 
		'Borg-Bot',
		'BoxSeaBot',
		'bright.net caching robot',
		'BSpider',
		'Butterfly', 
		'CACTVS Chemistry Spider',
		'Calif',
		'Cassandra',
		'Crawler', 
		'Digimarc Marcspider/CGI',
		'Deeper',
		'DotBot', 
		'Checkbot',
		'ChristCrawler.com',
		'churl',
		'cIeNcIaFiCcIoN.nEt',
		'CMC/0.01',
		'Collective',
		'Combine System',
		'Conceptbot',
		'ConfuzzledBot',
		'CoolBot',
		'Web Core / Roots',
		'XYLEME Robot',
		'Internet Cruiser Robot',
		'Cusco',
		'CyberSpyder Link Test',
		'CydralSpider',
		'Desert Realm Spider',
		'DeWeb(c) Katalog/Index',
		'DienstSpider',
		'Digger',
		'Digital Integrity Robot',
		'Direct Hit Grabber',
		'DNAbot',
		'DownLoad Express',
		'DragonBot',
		'e-collector',
		'EbiNess',
		'EIT Link Verifier Robot',
		'ELFINBOT',
		'Emacs-w3 Search Engine',
		'ananzi',
		'esculapio',
		'Esther',
		'Evliya Celebi',
		'nzexplorer',
		'FastCrawler',
		'Fluid Dynamics Search Engine robot',
		'Felix IDE',
		'Wild Ferret Web Hopper #1, #2, #3',
		'FetchRover',
		'fido',
		'Hämähäkki',
		'KIT-Fireball',
		'Fish search',
		'Fouineur',
		'Robot Francoroute',
		'Freecrawl',
		'FunnelWeb',
		'gammaSpider',
		'gazz',
		'GetBot',
		'GetURL',
		'Golem',
        'Googlebot', 
		'Grapnel/0.01 Experiment',
		'Griffon',
		'Gromit',
		'Northern Light Gulliver',
		'Gulper Bot',
		'HamBot',
		'Harvest',
		'havIndex',
		'HI (HTML Index) Search',
		'Hometown Spider Pro',
		'Wired Digital',
		'ht://Dig',
		'HTMLgobble',
		'Hyper-Decontextualizer',
		'iajaBot',
		'IBM_Planetwide',
		'Popular Iconoclast',
		'Ingrid',
		'Imagelock',
		'IncyWincy',
		'Informant',
		'InfoSeek Robot 1.0',
		'Infoseek Sidewinder',
		'InfoSpiders',
		'Inspector Web',
		'IntelliAgent',
		'I, Robot',
		'Iron33',
		'Israeli-search',
		'JavaBee',
		'JBot Java Web Robot',
		'JCrawler',
		'AskJeeves',
		'JoBo Java Web Robot',
		'Jobot',
		'JoeBot',
		'The Jubii Indexing Robot',
		'JumpStation',
		'image.kapsi.net',
		'Katipo',
		'KDD-Explorer',
		'Kilroy',
		'KO_Yappo_Robot',
		'LabelGrabber',
		'larbin',
		'legs',
		'Link Validator',
		'LinkScan',
		'LinkWalker',
		'Lockon',		 
		'ia_archiver',
        'R6_FeedFetcher', 
		'NetcraftSurveyAgent', 
		'Sogou web spider',        
		'Yahoo! Slurp', 
		'facebookexternalhit', 
		'PrintfulBot',
        'msnbot', 
		'Twitterbot', 
		'UnwindFetchor',
        'urlresolver', 		
		'TweetmemeBot', 
		'Embedly', 		
		'Linkdexbot', 
		'YandexBot', 
		'SpiderLing', 
		'MetaCommentBot', 
		'istellabot', 
		'FlipboardProxy', 
		'CommonCrawler', 
		'Java/', 
		'omgili', 		
		'Spider', 		
		'Feedfetcher', 
		'Python', 
		'Moreover', 
		'Nuzzel', 		
		'MetaURI', 
		'scanner', 
		'SkypeUriPreview', 
		'MySyndicaat', 
		'tweetedtimes'
	);

    foreach($bots as $b){
        if( stripos( $USER_AGENT, $b ) !== false ) return true; 
    }

	$ip = $_SERVER['REMOTE_ADDR'];
    $ip_bloqueadas = array('62.0.1.90', '38.108.108.185', '1.237.177.12');
    foreach($ip_bloqueadas as $b){
        if( stripos( $ip, $b ) !== false ) return true; 
    }
    return false;
}

function url_exists( $url = NULL ) {

    if( empty( $url ) ){
        return false;
    }
    stream_context_set_default(
        array(
            'http' => array(
                'method' => 'HEAD'
             )
        )
    );
    $headers = @get_headers( $url );
    sscanf( $headers[0], 'HTTP/%*d.%*d %d', $httpcode );

    $accepted_response = array( 200, 301, 302 );
    if( in_array( $httpcode, $accepted_response ) ) {
        return true;
    } else {
        return false;
    }
}

function getVimeoThumb($id) {
    $data = file_get_contents("https://vimeo.com/api/v2/video/$id.json");
    $data = json_decode($data);
	//var_dump ($data);
	$caratula = $data[0]->thumbnail_large;
	//$caratula = str_replace ("http://i.vimeocdn.com/video/", "https//i.vimeocdn.com/video/", $caratula);
	$caratula = str_replace ("_640", "?mw=1280&mh=720", $caratula);
    return $caratula;
}
?>