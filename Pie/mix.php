<?php

// Mix basically kicks everything off.

require_once('recipe.php');

//die($site_path);

session_start();

// Include the auto loader
require_once(__SITE_PATH.'/Pie/autobake.php');

// Include the main route controller
require_once(__SITE_PATH.'/Pie/Jelly/jelly.php');

//die('Hello World!! 2');

$pie = new Jelly();

// Kick everything off...
$pie->Bake();

?>