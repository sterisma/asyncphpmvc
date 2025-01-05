<?php

namespace App\Controllers;

use App\Core\JsonResponse;
use App\Exceptions\BadCredential;
use App\Exceptions\ConfirmPasswordNotMatched;
use App\Exceptions\UserAlreadyExists;
use App\Exceptions\UserNotFound;
use App\Models\AuthModel;
use Firebase\JWT\JWT;
use Psr\Http\Message\ServerRequestInterface;

class Authentication
{
    private $model;

    public function __construct(AuthModel $model)
    {
        $this->model = $model;
    }

    public function genKey(ServerRequestInterface $request){
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $rString = '';
        for($i=0; $i<128; $i++){
            $rString .= $chars[random_int(0, strlen($chars)-1)];
        }
        return JsonResponse::ok([
            'generated_random_key' => $rString
        ]);
    }

    public function SignUp(ServerRequestInterface $request)
    {
        $email = $request->getParsedBody()['email'];
        $password = $request->getParsedBody()['password'];
        $confirm = $request->getParsedBody()['confirm'];

        return $this->model->create($email, $password, $confirm)
            ->then(function ($msg) use ($password, $confirm){
                return JsonResponse::created($msg);
            })
            ->catch(function (ConfirmPasswordNotMatched $e) {
                return JsonResponse::badRequest("Confirm password must be same as password.");
            })
            ->catch(function (UserAlreadyExists $e) {
                return JsonResponse::badRequest("Email is already taken.");
            })
            ->catch(function (\Exception $e) {
                return JsonResponse::internalServerError($e->getMessage());
            });
    }

    public function SignIn(ServerRequestInterface $request)
    {
        $email = $request->getParsedBody()['email'];
        $password = $request->getParsedBody()['password'];

        return $this->model->authenticate($email, $password)
            ->then(function () use($email){
                $payload = [
                    'email' => $email,
                    'exp' => time() + 60 * 60,
                ];
                $jwt = JWT::encode($payload, $_ENV['JWT_KEY'], 'HS256');
                return JsonResponse::ok(['token' => $jwt]);
            })
            ->catch(function (UserNotFound $e) {
                return JsonResponse::unauthorized('email not exists.');
            })
            ->catch(function (BadCredential $e) {
                return JsonResponse::unauthorized('wrong password.');
            })
            ->catch(function (\Exception $e) {
                return JsonResponse::internalServerError($e->getMessage());
            });
    }
}