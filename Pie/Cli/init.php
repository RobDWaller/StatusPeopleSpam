<?php

if (!defined('__SITE_PATH')) {
        define('__SITE_PATH', '/var/spam/html/');
}

echo 'Starting!!'.PHP_EOL;
ini_set('display_errors', 1);

require_once(__SITE_PATH.'/Pie/recipe.php');
require_once(__SITE_PATH.'/Pie/autobake.php');
require_once(__SITE_PATH.'/Pie/Cli/commands.php');

$cli = new Commands($argv);

$result = $cli->make();

var_dump($result).PHP_EOL;

echo 'Finished!!'.PHP_EOL;