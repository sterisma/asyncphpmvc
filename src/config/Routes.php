<?php

namespace App\Config;

use App\Controllers\Authentication;
use FastRoute\RouteCollector;
use FastRoute\RouteParser\Std;
use FastRoute\DataGenerator\GroupCountBased;
use Clue\React\SQLite\DatabaseInterface;

use App\Controllers\HomeController;
use App\Controllers\UsersController;
use App\Models\AuthModel;
use App\Models\UserModel;

class Routes 
{
    private $routes;
    private $db;
    
    public function __construct(DatabaseInterface $dbConnection)
    {
        $this->routes = new RouteCollector(new Std(), new GroupCountBased());
        $this->db = $dbConnection;
    }

    /**
     * here you can define your routes
    */
    public function load(): RouteCollector
    {
        $protected = true;
        $this->routes->get('/', [new HomeController(), 'index']);

        ## routes for users
        $userModel = new UserModel($this->db);
        $this->routes->get('/users', [new UsersController($userModel), 'all']);
        $this->routes->get('/users/{email}', [new UsersController($userModel), 'one']);
        $this->routes->post('/users', [new UsersController($userModel), 'create']);
        $this->routes->delete('/users/{email}', [new UsersController($userModel), 'remove', true]);

        ## routes for auth
        $authModel = new AuthModel($this->db);
        $this->routes->post('/auth/signup', [new Authentication($authModel), 'SignUp']);
        $this->routes->post('/auth/signin', [new Authentication($authModel), 'SignIn']);
        $this->routes->get('/auth/genkey', [new Authentication($authModel), 'genKey']);

        return $this->routes;
    }
}