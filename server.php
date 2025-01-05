<?php
require 'vendor/autoload.php';

use React\Http\HttpServer; 
use React\Socket\SocketServer; 

use App\Config\Routes;
use App\Core\ErrorHandler;
use App\Core\JsonRequestDecoder;
use App\Core\Router; 

$loop = React\EventLoop\Loop::get(); 

$env = \Dotenv\Dotenv::createImmutable(__DIR__);
$env->safeLoad();

$sqlite = new \Clue\React\SQLite\Factory($loop);
$connection = $sqlite->openLazy(__DIR__. "/". $_ENV['DB_NAME']);

$routes = new Routes($connection);
$server = new HttpServer(
    new ErrorHandler(), 
    new JsonRequestDecoder(), 
    new Router($routes->load())
); 

$socket = new SocketServer('127.0.0.1:8080', array(), $loop); 
$server->listen($socket); 

$server->on('error', function (\Exception $error) {
    echo "Error: ". $error->getMessage() .PHP_EOL;
});

echo "Server running at http://127.0.0.1:8080\n"; 
$loop->run();