<?php
ini_set('memory_limit', '-1');


//print_r ($_GET['url']);
//die("1");

$_SESSION["HEADER"] = apache_request_headers();

define('CONTROLLERS', 'app/controllers/');
define('VIEWS', 'app/views/');
define('MODELS', 'app/models/');
define('HELPERS', 'system/helpers/');

require_once('system/system.php');
require_once('system/controller.php');
require_once('system/model.php');


function __autoload ( $file )
{
	if(file_exists(MODELS.$file.'.php')) {
	  require_once(MODELS.$file.'.php');
	} else if (file_exists(HELPERS.$file.'.php')){
	  	  require_once(HELPERS.$file.'.php');
	} else {
  	  require_once('404.html');
  	  //die("<center><br>Helper ou Model não existe!</b></center>");
  	}
}

$start = new System;
$start->run();
?>
