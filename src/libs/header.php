<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

$mainDir = dirname(dirname(dirname(__FILE__)));

require $mainDir.'/vendor/autoload.php';
require $mainDir.'/src/libs/error.php';

$config   = require $mainDir.'/src/libs/config.php';
$Database = require $mainDir.'/src/libs/database.php';
