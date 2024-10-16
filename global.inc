<?php

// Move initial variables to config file: 
require_once 'config/config.inc.php';

require_once('includes/mobile_detect.php');
$detect = new Mobile_Detect();
$es_movil = $detect->isMobile();

// Do not force HTTPS redirect on local machine:
$use_sts = !LOCAL;

if ($use_sts && isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') {

    header('Strict-Transport-Security: max-age=10886400; includeSubDomains; preload');
    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: SAMEORIGIN');
    header('X-XSS-Protection: 1; mode=block');

} elseif ($use_sts) {

  header('Location: https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'], true, 301);
  die();

}

?>