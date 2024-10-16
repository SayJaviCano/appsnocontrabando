<?php
session_start();
$roles = array('0','1','2','3');
include('includes/segur.php');

$path_relative = "../";
include('../includes/conexion.php');


$fecha = date("Y-m-d H:i:s");

$id_notificacion = (isset($_GET['id_notificacion']) ? $_GET['id_notificacion'] : 0); if ($id_notificacion==0){  $id_notificacion = (isset($_POST['id_notificacion']) ? $_POST['id_notificacion'] : 0); }

$fcmUrl = 'https://fcm.googleapis.com/fcm/send';
$apiKey = 'AAAAXS_qUSg:APA91bHhlxxIT0A6MB7gKf5qWv2eA_H2EOwzrK6HyCM4uQebtHYfQLcMsgyd3SC8wqYHLhwj7fsxMqjrjlyZk0fumFzJh6BKIipz3mJdZuxWDjABOLjoF2RJNTBlENWHNQqybMCd0vqA';

function Send_Notificacion($tokenDispositivo, $notification, $extraNotificationData) {

	global $fcmUrl, $apiKey;

	$fcmNotification = [
        'to' => $tokenDispositivo, // Usar para un único dispositivo
        'notification' => $notification, 
        'data' => $extraNotificationData
    ];

	$headers = ['Authorization: key=' . $apiKey, 'Content-Type: application/json'];

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $fcmUrl);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmNotification));
	$result = curl_exec($ch);

	if (curl_errno($ch)) {
		$result = curl_error($ch);
	}
	curl_close($ch);


   //echo "<br>" . json_encode($fcmNotification) . "<br><br>";

	return $result;
}

?>
<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<link href="dist/css/style.css" rel="stylesheet">
<link href="css/custom.css" rel="stylesheet">

<script src="assets/libs/jquery/dist/jquery.min.js"></script>
<script src="assets/libs/popper.js/dist/umd/popper.min.js"></script>
<script src="assets/libs/bootstrap/dist/js/bootstrap.min.js"></script>

</head>
<body>

<?php
	$msg = "";

	$sql = "SELECT * FROM app_ca_campaign WHERE enviado=0 AND id=$id_notificacion";
	$rs = $mysqli->query($sql); 
	if ($rs->num_rows > 0) {
		$fila = $rs->fetch_assoc();
		$nombre = $fila['nombre'];
		$titulo = $fila['titulo'];
		$texto = $fila['texto'];
		$enlace = $fila['enlace'];
        $id_noticia = $fila['id_noticia'];
		$grupo_envio = $fila['grupo_envio'];	// 0=Todos, 1=IOS, 2=Android, 3=Web        

        $image_comunicado = ""; //$dominio_apps ."images/icono-app-no-contrabando.png";
        if ($id_noticia!=0)
        {
            $sql2 = "SELECT * FROM contenidos WHERE activo=1 AND tipo='post' AND (imagen<>'' OR video<>'') AND id=$id_noticia";	
            $rs2 = $mysqli->query($sql2);
            if ($rs2->num_rows > 0) {
                $fila2 = $rs2->fetch_assoc();


                $imagen = $fila2['imagen'];	
                $video  = $fila2['video'];

                if ($imagen!="") { $image_comunicado = $dominio_apps . $imagen; }		
                elseif ($video!="") { 

                    $pos = strpos($video, "youtube");
                    if ($pos !== false) { 
                        $video = str_replace ("watch?v=", "", $video);
                        $arr_video	= explode('/', $video ); 
                        $codigo_video = $arr_video[count($arr_video)-1];
                        $url = "https://i.ytimg.com/vi/$codigo_video/hq720.jpg";
                        if (url_exists($url))
                        {
                            $image_comunicado = 'https://i.ytimg.com/vi/' . $codigo_video . '/hq720.jpg';
                        } else {
                            $image_comunicado = 'https://i.ytimg.com/vi/' . $codigo_video . '/hqdefault.jpg';
                        }
                    } else{
                        $pos = strpos($video, "vimeo");
                        if ($pos !== false) {
                            $arr_video	= explode('/', $video ); 
                            $codigo_video = $arr_video[count($arr_video)-1];
                            $image_comunicado = getVimeoThumb($codigo_video);	
                        }					
                    }                   
				
                }
            }            
        }

        $notification = ['title' => $titulo, 'body' => $texto, 'image' => $image_comunicado];

        $pendientes_enviar = 0;
        $sql2 = "SELECT * FROM app_ca_envios WHERE id_notificacion=$id_notificacion AND enviado=0";
        $rs2 = $mysqli->query($sql2);
        if ($rs2) {
            $pendientes_enviar = $rs2->num_rows;
        }

        $sql2 = "SELECT * FROM app_ca_envios WHERE id_notificacion=$id_notificacion AND enviado=0 LIMIT 0 , 50";
        $rs2 = $mysqli->query($sql2);
        $num_registros2 = 0;
        if ($rs2) { $num_registros2 =  $rs2->num_rows; }
        if ($num_registros2 > 0) { ?>
            <h3>A enviar: <?php echo $num_registros2; ?></h3>
            <table class="table table-striped">
                <thead class="grey lighten-2">
                    <tr>
                        <td width="30" align="right"></td>
                        <td width="30" align="right">Id</td>
                        <td width="100">Tipo</td>
                        <td>Dispositivo</td>
                    </tr>
                </thead>            
        <?php 
        
                $contador_envios = 0;
                $contador_errores = 0;  
                while ($fila2 = $rs2->fetch_array(MYSQLI_ASSOC)) {
                    $id_envio = $fila2['id'];
                    $id_dispositivo = $fila2['id_dispositivo'];

                    $sql3 = "SELECT * FROM app_dispositivos WHERE id=$id_dispositivo";
                    if ($grupo_envio==1) { $sql3 = "SELECT * FROM app_dispositivos WHERE id=$id_dispositivo AND tipo=1"; }
                    if ($grupo_envio==2) { $sql3 = "SELECT * FROM app_dispositivos WHERE id=$id_dispositivo AND tipo=2"; }
                    if ($grupo_envio==3) { $sql3 = "SELECT * FROM app_dispositivos WHERE id=$id_dispositivo AND tipo=3"; }
                    $rs3 = $mysqli->query($sql3);
                    if ($rs3) {
                        $num_total_registros3 = $rs3->num_rows;
                        if ($num_total_registros3>0) {
                            $fila3 = $rs3->fetch_assoc();
                            $token = trim($fila3['token']);
                            $tipo = trim($fila3['tipo']);
                            $dispositivo = trim($fila3['dispositivo']);
                            
                            if ($tipo == 1) 	{ $tipo_dispositivo = "IOS"; 	}
                            elseif ($tipo == 2)	{ $tipo_dispositivo = "Android";}
                            elseif ($tipo == 3)	{ $tipo_dispositivo = "Web";}

                            $url = $enlace; // . "?c=click&t=$id_dispositivo&n=$id_notificacion";                           
                            $extraNotificationData = ["message" => $notification, "notificacion_id" => $id_notificacion, 'click_action' => 'FLUTTER_NOTIFICATION_CLICK', 'url' => $url, 'token' => $token];

							$json = Send_Notificacion($token, $notification, $extraNotificationData);
                            // echo "<br>$json<br>";
                            //die();

                            $obj = json_decode($json, true);
                            $success = $obj['success'];
                            $fecha_envio = date("Y-m-d H:i:s");

                            if ($success=="1") {
                                $contador_envios++;           
                                
                                $firebase_id = $obj['multicast_id'];

                                $sql4 = "UPDATE app_ca_envios SET fecha_envio='$fecha_envio', enviado=1, firebase_notificacion_id='$firebase_id' WHERE id_notificacion=$id_notificacion AND  id_dispositivo=$id_dispositivo";
                                $rs4 = $mysqli->query($sql4); 
            
                                if ($tipo==1) { $sql4 = "UPDATE app_ca_campaign SET enviados_ios=enviados_ios+1 WHERE id=$id_notificacion"; }
                                if ($tipo==2) { $sql4 = "UPDATE app_ca_campaign SET enviados_android=enviados_android+1 WHERE id=$id_notificacion"; }      
                                if ($tipo==3) { $sql4 = "UPDATE app_ca_campaign SET enviados_web=enviados_web+1 WHERE id=$id_notificacion"; }      
                                $rs4 = $mysqli->query($sql4);
                                 ?>
									  <tr>
										<td align="right"><?php echo $contador_envios; ?></td>
										<td align="right"><?php echo $id_dispositivo; ?></td>
                                        <td><?php echo $tipo_dispositivo; ?></td>
										<td style="word-break: break-all;"><?php echo($dispositivo); ?></td>
									  </tr>
								<?php
                                flush();
                            } else {

                                $contador_errores ++;

                                $sql4 = "UPDATE app_ca_envios SET fecha_envio='$fecha_envio', enviado=2 WHERE id_notificacion=$id_notificacion AND  id_dispositivo=$id_dispositivo"; // Enviado=2 Error en el envio
                                $rs4 = $mysqli->query($sql4); 

                                $sql4 = "SELECT * FROM app_ca_envios WHERE enviado=2 AND id=$id_dispositivo";
                                $rs4 = $mysqli->query($sql4);
                                $num_errores = $rs4->num_rows;
                                if ($num_errores > 2) {
                                    $sql5 = "DELETE FROM app_ca_envios WHERE enviado=2 AND id=" . $id_dispositivo;
                                    $rs5 = $mysqli->query($sql5); 

                                    $sql5 = "DELETE FROM app_dispositivos WHERE id=" . $id_dispositivo;
                                    $rs5 = $mysqli->query($sql5); 
                                }

                                ?>
                                <!--
                                <tr>
                                  <td colspan="4"><?php echo $json; ?></td>
                                </tr>
                                -->
                                <tr>
                                  <td align="right" class="td_error"><?php echo $contador_errores; ?></td>
                                  <td align="right" class="td_error"><?php echo $id_dispositivo; ?></td>
                                  <td class="td_error"><?php echo $tipo_dispositivo; ?></td>
                                  <td style="word-break: break-all;" class="td_error"><?php echo($dispositivo); ?></td>
                                </tr>
                          <?php
                            }

                        }
                    }
                }
                $contador_mostrar = ($pendientes_enviar - ($contador_envios+$contador_errores));
                if (is_numeric($contador_mostrar)) {
                    $contador_mostrar = number_format($contador_mostrar, 0, ',', '.');
                }
                ?>
					<tr valign="top">
						<td colspan="4"> Restantes: <?php echo $contador_mostrar; ?></td>
					</tr>
				</table>   
            <?php             
    
        } else {
            $fecha_envio = date("Y-m-d H:i:s");
            $sql3 = "UPDATE app_ca_campaign SET fecha_envio='$fecha_envio', enviado=1 WHERE id=$id_notificacion";
            $rs3 = $mysqli->query($sql3);
            $sql3 = "SELECT * FROM app_ca_campaign WHERE id=$id_notificacion";
            $rs3 = $mysqli->query($sql3);
            $num_total_registros3 = $rs3->num_rows;
            if ($num_total_registros3 > 0) {
                $fila3 = $rs3->fetch_assoc();
                $enviados_ios = $fila3['enviados_ios'];
                $enviados_android = $fila3['enviados_android'];
                $enviados_web = $fila3['enviados_web'];
                $total_envios = $enviados_ios +  $enviados_android + $enviados_web;
            }
            $msg = "<h4>Envío realizado, numero de envios: $total_envios</h4>";
        }

	} else {
		$msg = "<h4>No hay registros pendientes para enviar</h4>";
	}
            
	
	if ($msg != "") { 
		echo "$msg";
	} else { 
	  ?><script>setTimeout("window.location='apps_notificaciones_enviar.php?id_notificacion=<?php echo $id_notificacion; ?>'",400);</script><?php
	} 
?>
</body>
</html>