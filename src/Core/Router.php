<?php

namespace App\Core;

use FastRoute\RouteCollector; 
use FastRoute\Dispatcher; 
use FastRoute\Dispatcher\GroupCountBased;
use Psr\Http\Message\ServerRequestInterface;


class Router 
{ 
    private $dispatcher; 

    public function __construct(RouteCollector $routes) 
    { 
        $this->dispatcher = new GroupCountBased($routes->getData());
    } 
    
    public function __invoke(ServerRequestInterface $request) 
    { 
        $routeInfo = $this->dispatcher->dispatch(
            $request->getMethod(), $request->getUri()->getPath()
        );
        
        switch ($routeInfo[0]) 
        { 
            case Dispatcher::NOT_FOUND: 
                return JsonResponse::notFound('page you requested is not fpund.'); 
                
            case Dispatcher::METHOD_NOT_ALLOWED: 
                return JsonResponse::methodNotAllowed(); 
                
            case Dispatcher::FOUND: 
                [$controller, $method] = $routeInfo[1]; 
                $params = $routeInfo[2]; 
                $protected = isset($routeInfo[1][2])? $routeInfo[1][2] : null;

                if($protected){
                    $guard = new Guard();
                    return $guard->protect($controller, $method, $request, $params);
                }
                return call_user_func_array([$controller, $method], [$request, ...$params]);
                
            default:
                return JsonResponse::internalServerError('Server error.');
        }

        throw new \LogicException('Something went wrong with routing!');
    }
}