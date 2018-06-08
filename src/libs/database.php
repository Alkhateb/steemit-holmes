<?php

// type
$type = $config['database']['type'];

switch ($type) {
    // allowed
    case 'mysql':
        break;

    // if not allowed
    default:
        $type = 'mysql';
}

$host     = $config['database']['type'];
$database = $config['database']['type'];
$user     = $config['database']['type'];
$password = $config['database']['type'];

try {
    $Database = new PDO(
        $type.':host='.$host.';dbname='.$database,
        $user,
        $password
    );
} catch (\PDOException $Exception) {
    show_error(
        'DATABASE ERROR',
        $Exception->getMessage(),
        $Exception->getCode()
    );
}

return $Database;
