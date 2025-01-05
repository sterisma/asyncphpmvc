<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\Core\JsonResponse;
use App\Exceptions\UserNotFound;
use App\Models\DTO\UserDTO;
use App\Models\UserModel;
use Psr\Http\Message\ServerRequestInterface;

class UsersController extends BaseController
{

    private $model;

    public function __construct(UserModel $model)
    {
        $this->model = $model;
    }

    public function create(ServerRequestInterface $request)
    {
        $email = $request->getParsedBody()['email'];
        $password = $request->getParsedBody()['password'];
        $password = password_hash($password, PASSWORD_DEFAULT);

        return $this->model
            ->create($email, $password)
            ->then(function (UserDTO $user) {
                return JsonResponse::created($user->toArray());
            })
            ->catch(function (UserNotFound $e) {
                return JsonResponse::notFound($e->getMessage());
            })
            ->catch(function (\Exception $e) {
                return JsonResponse::internalServerError($e->getMessage());
            });
    }

    public function all(ServerRequestInterface $request)
    {
        return $this->model
            ->getAll()
            ->then(function (array $users) {
                $data = array_map(function (UserDTO $user) {
                    return $user->toArray();
                }, $users);

                return JsonResponse::ok($data);
            })
            ->catch(function (UserNotFound $exc) {
                return JsonResponse::notFound($exc->getMessage());
            })
            ->catch(function (\Exception $e) {
                return JsonResponse::internalServerError($e->getMessage());
            });
    }

    public function one(ServerRequestInterface $request, string $email)
    {
        return $this->model
            ->getOne($email)
            ->then(function (UserDTO $user) {
                return JsonResponse::ok($user);
            })
            ->catch(function (UserNotFound $exc) {
                return JsonResponse::notFound($exc->getMessage());
            })
            ->catch(function (\Exception $e) {
                return JsonResponse::internalServerError($e->getMessage());
            });
    }

    public function remove(ServerRequestInterface $request, string $email)
    {
        return $this->model->delete($email)
            ->then(function (int $affectedRows) {
                if($affectedRows > 0)
                    return JsonResponse::noContent();
            })
            ->catch(function (UserNotFound $e) {
                return JsonResponse::notFound($e->getMessage());
            })
            ->catch(function (\Exception $e) {
                return JsonResponse::internalServerError($e->getMessage());
            });    
    }
}