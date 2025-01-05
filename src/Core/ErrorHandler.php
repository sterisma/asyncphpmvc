<?php

namespace App\Core;

use Psr\Http\Message\ServerRequestInterface;
use App\Core\JsonResponse;

final class ErrorHandler
{
    public function __invoke(ServerRequestInterface $request, callable $next)
    {
        try {
            return $next($request);
        } catch (\Throwable $th) {
            return JsonResponse::internalServerError($th->getMessage());
        }
    }
}