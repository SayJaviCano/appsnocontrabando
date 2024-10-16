<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//ini_set('max_execution_time', '3600');
ini_set('max_execution_time', '0'); // for infinite time of execution 
set_time_limit(3600);

session_start();

{
	// ****************  BBDD  **************************** //
	$maquinaConexion = "localhost"; 
	$DB = "altadis_nocontrabando";	
	$usuarioConexion = "nocontrabando_bd";
	$claveConexion = "76Gimz7#";


	// ****************  LOCAL  **************************** //
	//$usuarioConexion = "root";
	//$claveConexion = "";
}


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
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title></title>
</head>

<body>

<?php

	$fechaInicio	= "17-06-2021";	
	$fechaFin		= date("d-m-Y", strtotime(date("d-m-Y")."- 1 days")); 

	$server_name = "nocontrabando.altadis.com"; 		
	//$server_name = "appsnocontrabando.com";
	//$fechaInicio	= "13-09-2022";	


	$str_fechaInicio= strtotime($fechaInicio);
	$str_fechaFin	= strtotime($fechaFin);

	function strip_param_from_url( $url, $param )
	{
		$base_url = strtok($url, '?');              // Get the base url
		$parsed_url = parse_url($url);              // Parse it 
		$query = $parsed_url['query'];              // Get the query string
		parse_str( $query, $parameters );           // Convert Parameters into array
		unset( $parameters[$param] );               // Delete the one you want
		$new_query = http_build_query($parameters); // Rebuilt query string

		$new_url = $base_url;
		if ($new_query != "") { 
			$new_url .= '?'. $new_query;
		}
		return $new_url;            // Finally url is ready
	}

	$sql = "SELECT * FROM analytics WHERE url like '%fbclid%'";
	$rs = $mysqli->query($sql); 
	while ($fila = $rs->fetch_array(MYSQLI_ASSOC)){		
		$id = $fila['id'];
		$url = strip_param_from_url($fila['url'], 'fbclid');

		$sql2 = "UPDATE analytics SET url='$url' WHERE id=$id";
		$rs2 = $mysqli->query($sql2);	
		echo "fbclid: $sql2<br>";
	}


	/********** AJUSTES DE CAMPAÑA *******************/
	{
		/*
		$sql3 = "DELETE FROM analytics WHERE url LIKE'%PERDEMOS TODOS%' OR url LIKE'%ad_origin%'";
		$rs3 = $mysqli->query($sql3);
		*/

		/*
		$sql = "SELECT * FROM analytics WHERE url like '%ad_campaign%'";
		$rs = $mysqli->query($sql); 
		while ($fila = $rs->fetch_array(MYSQLI_ASSOC)){		
			$id = $fila['id'];
			$url = $fila['url'];

			$url = str_replace ("ad_campaign=web", "utm_campaign=perdemosTodos", $url);
			$url = str_replace ("bannerpregunta", "webNoContrabando", $url);

			$url = str_replace ("ad_campaign=perdemostodos", "utm_campaign=perdemosTodos", $url);
			$url = str_replace ("ad_source=", "utm_medium=publicacion&utm_source=", $url);

			$url = str_replace ("utm_medium=publicacion&utm_source=webNoContrabando", "utm_medium=banner&utm_source=webNoContrabando", $url);			

			$sql2 = "UPDATE analytics SET url='$url' WHERE id=$id";
			$rs2 = $mysqli->query($sql2);	
			echo "ad_campaign: $sql2<br>";
		}
		*/
	}



	for($i=$str_fechaInicio; $i<=$str_fechaFin; $i+=86400){
		$f = date("Y-m-d", $i);
		$paginas_vistas = 0;

		{
			/*
			$sql3 = "DELETE FROM analytics_compact WHERE DATE(fecha)='$f' AND server_name='$server_name'";		
			$rs3 = $mysqli->query($sql3); 

			$sql3 = "DELETE FROM analytics_paginas_vistas WHERE DATE(fecha)='$f' AND server_name='$server_name'";
			$rs3 = $mysqli->query($sql3); 

			$sql3 = "DELETE FROM analytics_plataformas WHERE DATE(fecha)='$f' AND server_name='$server_name'";
			$rs3 = $mysqli->query($sql3); 

			$sql3 = "DELETE FROM analytics_navegadores WHERE DATE(fecha)='$f' AND server_name='$server_name'";
			$rs3 = $mysqli->query($sql3); 

			$sql3 = "DELETE FROM analytics_referral WHERE DATE(fecha)='$f' AND server_name='$server_name'";
			$rs3 = $mysqli->query($sql3); 

			$sql3 = "DELETE FROM analytics_adquisicion_social WHERE DATE(fecha)='$f' AND server_name='$server_name'";
			$rs3 = $mysqli->query($sql3); 

			$sql3 = "DELETE FROM analytics_ccaa WHERE DATE(fecha)='$f'";
			$rs3 = $mysqli->query($sql3); 

			$sql3 = "DELETE FROM analytics_campaign WHERE DATE(fecha)='$f' AND server_name='$server_name'";
			$rs3 = $mysqli->query($sql3); 
			*/
		}


		$sql = "SELECT * FROM analytics_compact WHERE DATE(fecha)='$f' AND server_name='$server_name'";
		$rs = $mysqli->query($sql);
		$cuantos = $rs->num_rows;
		$cuantos = 0;
		if ($cuantos == 0)
		{
			analytics_compact($f, $server_name);
		}
		
	}

function analytics_compact($f, $server_name) 
{
	global $mysqli;
	
	//echo "Fecha: $f -  $server_name<br>";


	/*********** FASE 1 : table analytics_compact ************************/
	/*
	{
		$paginas_vistas=0; $usuarios=0; $sesiones=0; $rebotes=0;

		$sql = "SELECT id FROM analytics WHERE DATE(fecha)='$f' AND server_name='$server_name'";
		$rs = $mysqli->query($sql);
		$paginas_vistas = $rs->num_rows;

		$sql = "SELECT COUNT(usuario) as cuantas FROM analytics WHERE DATE(fecha)='$f' AND server_name='$server_name' GROUP BY usuario";
		$rs = $mysqli->query($sql);
		$usuarios = $rs->num_rows;

		$sql = "SELECT COUNT(session_id) as cuantas FROM analytics WHERE DATE(fecha)='$f' AND server_name='$server_name' GROUP BY session_id";
		$rs = $mysqli->query($sql);
		$sesiones = $rs->num_rows;

		$sql = "SELECT COUNT(url) as cuantas, session_id FROM analytics WHERE DATE(fecha)='$f' AND server_name='$server_name' GROUP BY session_id HAVING cuantas=1";
		$rs = $mysqli->query($sql);
		$rebotes = $rs->num_rows;

		$array_dispositivos = array("Escritorio"=>0, "Mobile"=>0, "Tablet"=>0);
		$sql = "SELECT COUNT(dispositivo) as cuantos, dispositivo FROM analytics WHERE DATE(fecha)='$f' AND server_name='$server_name' GROUP BY dispositivo";
		$rs = $mysqli->query($sql); 
		while ($fila = $rs->fetch_array(MYSQLI_ASSOC)) {
			$cuantos = $fila['cuantos'];	
			$dispositivo = $fila['dispositivo'];
			$array_dispositivos[$dispositivo] = $cuantos;
		}

		$array_adquisicion = array("Direct"=>0, "Newsletter"=>0, "Organic"=>0,  "Referral"=>0, "Social"=>0);
		$sql = "SELECT COUNT(fuente) as cuantos, fuente FROM analytics WHERE fuente<>'' AND DATE(fecha)='$f' AND server_name='$server_name' GROUP BY fuente";
		$rs = $mysqli->query($sql); 
		while ($fila = $rs->fetch_array(MYSQLI_ASSOC)) {
			$cuantos = $fila['cuantos'];
			$fuente = trim($fila['fuente']);
			$array_adquisicion[$fuente] = $cuantos;
		}

		$sql2 = "INSERT INTO analytics_compact (fecha, server_name, paginas_vistas, usuarios, sesiones, rebotes, disp_escritorio, disp_mobile, disp_tablet, adq_direct, adq_newsletter, adq_referral, adq_organic, adq_social) VALUES ";
		$sql2 = $sql2 . "('$f', '$server_name', $paginas_vistas, $usuarios, $sesiones, $rebotes, ".  $array_dispositivos["Escritorio"]. ", ".  $array_dispositivos["Mobile"] . ", ".  $array_dispositivos["Tablet"] . ", ".  $array_adquisicion["Direct"] . ", ".  $array_adquisicion["Newsletter"] . ", ".  $array_adquisicion["Referral"] . ", ".  $array_adquisicion["Organic"] . ", ".  $array_adquisicion["Social"] . ")"; 
		$rs2 = $mysqli->query($sql2);
	}
	*/

	/******************** Páginas vistas *********************************/
	/*
	{
		$sql = "SELECT COUNT(title) as visitas, id, fecha, title, url FROM analytics WHERE DATE(fecha)='$f' AND server_name='$server_name' GROUP BY title ORDER by visitas DESC LIMIT 30";
		$rs = $mysqli->query($sql); 
		while ($fila = $rs->fetch_array(MYSQLI_ASSOC)){		
			$title = trim($fila['title']);
			$visitas = $fila['visitas'];
			$url = $fila['url'];

			$sql2 = "SELECT * FROM analytics_paginas_vistas WHERE DATE(fecha)='$f' AND server_name='$server_name' AND title='$title'";
			$rs2 = $mysqli->query($sql2);	
			if ($rs2->num_rows == 0) {
				$sql3 = "INSERT INTO analytics_paginas_vistas (fecha, server_name, title, url, visitas) VALUES ";
				$sql3.= "('$f', '$server_name', '$title', '$url', $visitas)"; 
				$rs3 = $mysqli->query($sql3);
			}	
		}
	}
	*/

	/******************** Plataformas *********************************/
	/*
	{
		$sql = "SELECT COUNT(plataforma) as cuantos, plataforma FROM analytics WHERE DATE(fecha)='$f' AND server_name='$server_name' GROUP BY plataforma";
		$rs = $mysqli->query($sql); 		
		while ($fila = $rs->fetch_array(MYSQLI_ASSOC)){		
			$cuantos = $fila['cuantos'];	
			$plataforma = trim($fila['plataforma']);

			$sql2 = "SELECT * FROM analytics_plataformas WHERE DATE(fecha)='$f' AND server_name='$server_name' AND plataforma='$plataforma'";
			$rs2 = $mysqli->query($sql2);	
			if ($rs2->num_rows == 0) {
				$sql3 = "INSERT INTO analytics_plataformas (fecha, server_name, plataforma, cuantos) VALUES ('$f', '$server_name', '$plataforma', $cuantos)"; 
				$rs3 = $mysqli->query($sql3);
			}	
		}
	}
	*/

	/******************** Navegadores *********************************/
	/*
	{
		$sql = "SELECT COUNT(navegador) as cuantos, navegador FROM analytics WHERE DATE(fecha)='$f' AND server_name='$server_name' GROUP BY navegador";
		$rs = $mysqli->query($sql); 		
		while ($fila = $rs->fetch_array(MYSQLI_ASSOC)){		
			$cuantos = $fila['cuantos'];	
			$navegador = trim($fila['navegador']);

			$sql2 = "SELECT * FROM analytics_navegadores WHERE DATE(fecha)='$f' AND server_name='$server_name' AND navegador='$navegador'";
			$rs2 = $mysqli->query($sql2);	
			if ($rs2->num_rows == 0) {
				$sql3 = "INSERT INTO analytics_navegadores (fecha, server_name, navegador, cuantos) VALUES ('$f', '$server_name', '$navegador', $cuantos)"; 
				$rs3 = $mysqli->query($sql3);
			}	
		}
	}
	*/

	/******************** Referral *******************************************/
	/*
	{
		$sql = "SELECT referrer, COUNT(*) as cuantos FROM analytics WHERE fuente='Referral' AND DATE(fecha)='$f' AND server_name='$server_name' GROUP BY referrer ORDER by 2 DESC LIMIT 30";
		$rs = $mysqli->query($sql); 
		while ($fila = $rs->fetch_array(MYSQLI_ASSOC)) {		

			$referrer = trim($fila['referrer']);
			$cuantos = $fila['cuantos'];

			$sql2 = "SELECT * FROM analytics_referral WHERE DATE(fecha)='$f' AND server_name='$server_name' AND referrer='$referrer'";
			$rs2 = $mysqli->query($sql2);	
			if ($rs2->num_rows == 0) {
				$sql3 = "INSERT INTO analytics_referral (fecha, server_name, referrer, cuantos) VALUES ('$f', '$server_name', '$referrer', $cuantos)"; 
				$rs3 = $mysqli->query($sql3);
			}	
		}
	}
	*/

	/******************** Adquisición Social *********************************/
	/*
	{
		$sql = "SELECT COUNT(tipo_fuente) as cuantos, tipo_fuente FROM analytics WHERE fuente='Social' AND DATE(fecha)='$f' AND server_name='$server_name' GROUP BY tipo_fuente";
		$rs = $mysqli->query($sql); 		
		while ($fila = $rs->fetch_array(MYSQLI_ASSOC)){		
			$cuantos = $fila['cuantos'];	
			$tipo_fuente = trim($fila['tipo_fuente']);

			$sql2 = "SELECT * FROM analytics_adquisicion_social WHERE DATE(fecha)='$f' AND server_name='$server_name' AND tipo_fuente='$tipo_fuente'";
			$rs2 = $mysqli->query($sql2);	
			if ($rs2->num_rows == 0) {
				$sql3 = "INSERT INTO analytics_adquisicion_social (fecha, server_name, tipo_fuente, cuantos) VALUES ('$f', '$server_name', '$tipo_fuente', $cuantos)"; 
				$rs3 = $mysqli->query($sql3);
			}	
		}
	}
	*/

	/******************** Denuncias por CCAA *********************************/
	/*
	{
		$sql = "SELECT COUNT(id_comunidad_autonoma) as cuantos, id_comunidad_autonoma FROM punto_venta_denunciado WHERE DATE(fecha_alta)='$f' GROUP BY id_comunidad_autonoma ORDER BY 1 DESC";
		$rs = $mysqli->query($sql); 
        while ($fila = $rs->fetch_array(MYSQLI_ASSOC)) {
			$denuncias = $fila['cuantos'];
			$id_comunidad_autonoma = $fila['id_comunidad_autonoma'];	

			$ccaa = "";
			$sql2 = "SELECT * FROM comunidades WHERE id=$id_comunidad_autonoma"; 
			$rs2 = $mysqli->query($sql2); 
			if ($rs2->num_rows > 0) {
				$fila2 = $rs2->fetch_assoc();
				$ccaa = $fila2['nombre'];
			}		

			$sql2 = "SELECT * FROM analytics_ccaa WHERE DATE(fecha)='$f' AND ccaa='$ccaa'";
			$rs2 = $mysqli->query($sql2);	
			if ($rs2->num_rows == 0) {
				$sql3 = "INSERT INTO analytics_ccaa (fecha, ccaa, denuncias) VALUES ('$f', '$ccaa', $denuncias)"; 
				$rs3 = $mysqli->query($sql3);
			}	
		}
	}
	*/

	/******************** Campañas *********************************/
	/*
	{
		
		$sql = "SELECT COUNT(url) as visitas, id, fecha, title, url FROM analytics WHERE DATE(fecha)='$f' AND server_name='$server_name' AND url LIKE '%utm_campaign%'";
		$sql.= " AND (fuente='Social' OR referrer LIKE 'https://nocontrabando.altadis.com/%')";
		$sql.= " GROUP BY url ORDER by visitas DESC";
		$rs = $mysqli->query($sql); 
		//echo "<br><b>Campañas</b><br>";
		//echo "$sql<br><br>";
		while ($fila = $rs->fetch_array(MYSQLI_ASSOC)){		
			$title = trim($fila['title']);
			$visitas = $fila['visitas'];
			$url = $fila['url'];

			$parsed_url = parse_url($url);              // Parse it 
			$query = $parsed_url['query'];              // Get the query string
			parse_str($query, $params);

			$utm_source		= trim($params['utm_source'] ?? '');
			$utm_medium		= trim($params['utm_medium'] ?? '');
			$utm_campaign	= trim($params['utm_campaign'] ?? '');

			$sql2 = "SELECT * FROM analytics_campaign WHERE DATE(fecha)='$f' AND server_name='$server_name' AND url='$url'";
			$rs2 = $mysqli->query($sql2);	
			if ($rs2->num_rows == 0) {
				$sql3 = "INSERT INTO analytics_campaign (fecha, server_name, title, url, visitas, utm_source, utm_medium, utm_campaign) VALUES ";
				$sql3.= "('$f', '$server_name', '$title', '$url', $visitas, '$utm_source', '$utm_medium', '$utm_campaign')"; 
				$rs3 = $mysqli->query($sql3);

				//echo "$sql3<br>";
			}	
		}
	}
	*/
	/******************** Campañas Direct *********************************/
	{
		
		$sql = "SELECT COUNT(url) as visitas, id, fecha, title, url FROM analytics WHERE DATE(fecha)='$f' AND server_name='$server_name' AND url LIKE '%utm_campaign%'";
		$sql.= " AND (fuente='Direct')";
		$sql.= " GROUP BY url ORDER by visitas DESC";
		$rs = $mysqli->query($sql); 
		//echo "<br><b>Campañas</b><br>";
		//echo "$sql<br><br>";
		while ($fila = $rs->fetch_array(MYSQLI_ASSOC)){		
			$title = trim($fila['title']);
			$visitas = $fila['visitas'];
			$url = $fila['url'];

			$parsed_url = parse_url($url);              // Parse it 
			$query = $parsed_url['query'];              // Get the query string
			parse_str($query, $params);

			$utm_source		= trim($params['utm_source'] ?? '');
			$utm_medium		= trim($params['utm_medium'] ?? '');
			$utm_campaign	= trim($params['utm_campaign'] ?? '');

			$sql2 = "SELECT * FROM analytics_campaign_direct WHERE DATE(fecha)='$f' AND server_name='$server_name' AND url='$url'";
			$rs2 = $mysqli->query($sql2);	
			if ($rs2->num_rows == 0) {
				$sql3 = "INSERT INTO analytics_campaign_direct (fecha, server_name, title, url, visitas, utm_source, utm_medium, utm_campaign) VALUES ";
				$sql3.= "('$f', '$server_name', '$title', '$url', $visitas, '$utm_source', '$utm_medium', '$utm_campaign')"; 
				$rs3 = $mysqli->query($sql3);

				echo "$sql3<br>";
			}	
		}
	}
		
}


?>
</body>

</html>