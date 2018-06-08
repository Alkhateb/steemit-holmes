<?php

echo "Welcome to STEEMIT Holmes Setup".PHP_EOL;

sleep(0.2);
echo "Now i will install all dependencies...".PHP_EOL;

sleep(0.2);
echo "Download Composer (PHP Package Manager)".PHP_EOL;

copy('https://getcomposer.org/installer', 'composer-setup.php');

$hash = '544e09ee996cdf60ece3804abc52599c22b1f40f4323403c44d44fdfdd586475ca9813a858088ffbc1f233e9b180f061';

if (hash_file('SHA384', 'composer-setup.php') === $hash) {
    echo 'Installer verified';
} else {
    echo 'Installer corrupt';
    unlink('composer-setup.php');
}

system('php composer-setup.php');
unlink('composer-setup.php');

echo PHP_EOL;


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
