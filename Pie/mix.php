<?php

// Mix basically kicks everything off.

$site_path = $_SERVER["DOCUMENT_ROOT"];
define ('__SITE_PATH', $site_path);

//die($site_path);

session_start();

// Include the auto loader
require_once(__SITE_PATH.'/Pie/autobake.php');

$loader = new Services\Routes\Loader;

if ($loader->isTest())
{
	require_once('conf.test.php');	
}
else
{
	require_once('recipe.php');
}

// Include the main route controller
require_once(__SITE_PATH.'/Pie/Jelly/jelly.php');

//die('Hello World!! 2');

$pie = new Jelly();

// Kick everything off...
$pie->Bake();

?>