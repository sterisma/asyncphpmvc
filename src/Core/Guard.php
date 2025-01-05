<?php

namespace App\Core;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Psr\Http\Message\ServerRequestInterface;

final class Guard
{
    public function protect(
        BaseController $controller, 
        string $method, 
        ServerRequestInterface $request, 
        $args=null
    )
    {
        if($this->authorize($request))
            return call_user_func_array([$controller, $method], [$request, ...$args]);
        return JsonResponse::unauthorized('');
    }

    private function authorize(ServerRequestInterface $request): bool
    {
        $header = $request->getHeaderLine('Authorization');
        $token = str_replace('Bearer ', '', $header);
        if(empty($token))
            return false;
        return JWT::decode($token, new Key($_ENV['JWT_KEY'], 'HS256')) !== null;
    }
}