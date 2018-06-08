<?php

echo "Welcome to STEEMIT Holmes Setup".PHP_EOL;

sleep(0.2);
echo "Now i will install all dependencies...".PHP_EOL;

sleep(0.2);
echo "Download Composer (PHP Package Manager)".PHP_EOL;


echo PHP_EOL;
echo PHP_EOL;
echo "Installing all dependencies";

system('php composer.phar update');


echo "Create configuration files";
mkdir(dirname(__FILE__).'/etc');

$file = dirname(__FILE__).'/etc/conf.ini.php';

$config = '
#<?php exit; ?>

[database]
type=""
host=""
database=""
user=""
password=""
';

file_put_contents($file, $config);

echo PHP_EOL;
echo 'Please fill out the configuration file in -- '.$file.' --'.PHP_EOL;
echo 'Thanks for installing me';
echo PHP_EOL;
