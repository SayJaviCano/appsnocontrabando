<?php

/** 
 * Add config file so that we can work locally and on the server
 * and use git without having to push the config file on every commit
 * 
 * a copy of the config file is added to the project. This file is ignored by git
 * 
 * @version 1.1 2024-10-16
 * 
 */

 define ("LOCAL", false);
 define ("DEBUG", false);

 // To toggle whether pages show APP sections or not.
 define ("APP_ACCESS", true);


if (DEBUG) {

  error_reporting(E_ALL);
  ini_set('display_errors', 1);

} else {

  // supress PHP warnings: 
  error_reporting(E_ERROR | E_PARSE);

}


if (LOCAL) {

  $dominio      = "http://localhost/saysawa/nocontrabando/";
  $dominio_apps = "http://localhost/saysawa/app-nocontrabando/";

  $dominio_web = $dominio_apps;
  $ruta_real = "/saysawa/app-nocontrabando/";

  // DB Connection:
  $maquinaConexion = "127.0.0.1";
  $usuarioConexion = "root";
  $claveConexion = "hogfather";
  $DB = "saysawa_nocontrabando";

} else {

  /* Set base links to APP or Website */
  $http_host = $_SERVER['HTTP_HOST'];

  $dominio_web  = "https://nocontrabando.saysawa.com/";
  $dominio_apps = "https://appsnocontrabando.com/";
  
  if ($http_host=="appsnocontrabando.com") {
    $dominio = $dominio_apps;
  } else {
    $dominio = $dominio_web;
  }

  $ruta_real = "";

  // DB Connection:
  $maquinaConexion = "localhost";
  $DB = "nocontrabando_2024";
  $usuarioConexion = "nocontra_db_2024";
  $claveConexion = "24sL+Ss+nn_63";

}


// Mail Configuration and Password SALT: 

$mail_host	    = "smtp.acens.com";
$mail_username	= "clientes.saysawa.com";
$mail_password  = "Clientes2015";

$de             = "ilicito@es.imptob.com"; //"nocontrabando.altadis@gmail.com";
$deNombre       = "No Contrabando";

$TAMANO_PAGINA  = 10;

$clave_crip     = "KEY-NOContrabando#04|21";