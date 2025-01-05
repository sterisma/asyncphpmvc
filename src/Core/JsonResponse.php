<?php

namespace App\Core;

use React\Http\Message\Response;

final class JsonResponse
{

    private static function response(int $status_code, $data = null): Response
    {
        $data = ($data !== null)? json_encode($data) : null;
        if($status_code === 204)
            return new Response(204);
        return new Response($status_code, ["Content-Type" => "application/json"], $data);
    }

    public static function ok($data=null): Response
    {
        return self::response(200, $data);
    }

    public static function created($data=null): Response
    {
        return self::response(201, $data);
    }

    public static function internalServerError($reason): Response
    {
        return self::response(500, ['error' => $reason]);
    }

    public static function notFound($message=null): Response
    {
        return self::response(404, ['message' => $message]);
    }

    public static function noContent(): Response
    {
        return self::response(204);
    }

    public static function badRequest($reason): Response
    {
        return self::response(400, ['reason' => $reason]);
    }

    public static function methodNotAllowed(): Response
    {
        return self::response(405, ['error' => 'Method Not Allowed.']);
    }

    public static function unauthorized($reason): Response
    {
        return self::response(401, ['reason' => $reason]);
    }
}