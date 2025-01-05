<?php

namespace App\Controllers;

use App\Core\BaseController;
use Psr\Http\Message\ServerRequestInterface;

class HomeController extends BaseController 
{ 
    public function index(ServerRequestInterface $request) 
    { 
        $hello = 'Hello world from ReactMVC, woy woy woy'; 
        return $this->render('home', ['hello' => $hello]);
    } 
}