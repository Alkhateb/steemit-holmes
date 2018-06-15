<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

$mainDir = dirname(dirname(dirname(__FILE__)));

require $mainDir.'/vendor/autoload.php';
require $mainDir.'/src/libs/error.php';

$config = require $mainDir.'/src/libs/config.php';

$Cache    = require $mainDir.'/src/libs/cache.php';
$Database = require $mainDir.'/src/libs/database.php';

// main vars
// @todo outsource to a class
$user     = '';
$category = '';

if (!empty($_REQUEST['user'])) {
    $user = filter_input(INPUT_GET, 'user');
    $user = urldecode($_REQUEST['user']);
    $user = str_replace('@', '', $user);
    $user = trim($user);
}

if (!isset($_REQUEST['c'])) {
    $_REQUEST['c'] = 'general';
}

switch ($_REQUEST['c']) {
    case 'bots':
    case 'general':
    case 'transfers':
        $category = $_REQUEST['c'];
        break;

    default:
        $category = 'general';
}
