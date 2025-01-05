<?php

namespace App\Core;

use Twig\Environment; 
use Twig\Loader\FilesystemLoader; 
use React\Http\Message\Response;

class ViewRenderer { 
    private static $twig; 

    public static function init() 
    { 
        $loader = new FilesystemLoader(__DIR__ . '/../Views'); 
        self::$twig = new Environment($loader); 
    } 
    
    public static function render($view, $data = []) 
    { 
        self::init(); 
        return new Response(
            200, 
            ['Content-Type' => 'text/html'], 
            self::$twig->render("{$view}.twig", $data)
        );
    }
}