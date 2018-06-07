<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once dirname(__FILE__).'/vendor/autoload.php';

$Cache    = require dirname(__FILE__).'/etc/cache.php';
$Database = require dirname(__FILE__).'/etc/database.php';

