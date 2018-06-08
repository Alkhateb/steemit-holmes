<?php

$configFile = dirname(dirname(dirname(__FILE__))).'/etc/config.ini.php';
$data       = parse_ini_file($configFile, true);

return $data;
