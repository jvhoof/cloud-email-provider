<?php

require_once("vendor/autoload.php");

// Kickstart the framework
$f3 = Base::instance();

$f3->set('DEBUG',1);
if ((float)PCRE_VERSION<7.9)
	trigger_error('PCRE version is out of date');

// Load configuration
$f3->config('app/app.cfg');
$f3->config('app/routes.cfg');

$f3->run();
?>
