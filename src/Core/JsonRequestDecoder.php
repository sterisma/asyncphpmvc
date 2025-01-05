<?php

namespace App\Core;

use Psr\Http\Message\ServerRequestInterface;

final class JsonRequestDecoder
{
    public function __invoke(ServerRequestInterface $request, callable $next)
    {
        if($request->getHeaderLine('Content-Type') === 'application/json'){
            $request = $request->withParsedBody(
                json_decode($request->getBody()->getContents(), true)
            );
        }

        return $next($request);
    }
}