<?php

require __DIR__. "/vendor/autoload.php";

use Doctrine\DBAL\DriverManager;
use Dotenv\Dotenv;

$env = Dotenv::createImmutable(__DIR__);
$env->safeLoad();

return DriverManager::getConnection([
    "driver"    => $_ENV['DB_DRIVER'],
    'path'      => __DIR__.'/'.$_ENV['DB_NAME'],
]);