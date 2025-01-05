<?php

namespace App\Models;

use App\Exceptions\BadCredential;
use App\Exceptions\ConfirmPasswordNotMatched;
use Clue\React\SQLite\DatabaseInterface;
use React\Promise\PromiseInterface;
use Clue\React\SQLite\Result;
use function React\Promise\reject;
use function React\Promise\resolve;
use App\Exceptions\UserAlreadyExists;
use App\Exceptions\UserNotFound;
use App\Models\DTO\UserDTO;
use Exception;
use React\Promise\Promise;

class AuthModel
{
    private $connection;

    public function __construct(DatabaseInterface $connection)
    {
        $this->connection = $connection;
    }

    private function emailIsNotTaken(string $email): PromiseInterface
    {
        return $this->connection
            ->query("SELECT 1 FROM users WHERE email = ?", [$email])
            ->then(function (Result $result) {
                return empty($result->rows)? resolve(true) : reject(new UserAlreadyExists());
            });
    }

    private function matchingConfirmPassword(string $password, string $confirm): PromiseInterface
    {
        return new Promise(function ($resolve, $reject) use($password, $confirm) {
            if($confirm === $password)
                return $resolve(true);
            return $reject(new ConfirmPasswordNotMatched());
        });
    }

    public function create(string $email, string $password, string $confirm): PromiseInterface
    {
        return $this->matchingConfirmPassword($password, $confirm)
            ->then(function () use ($email, $password) {
                return $this->emailIsNotTaken($email)
                    ->then(function () use ($email, $password) {
                        $sql = "INSERT INTO users(email, password) VALUES (?,?)";
                        return $this->connection
                            ->query($sql, [$email, password_hash($password, PASSWORD_BCRYPT)])
                            ->then(function (Result $result) {
                                return ($result->insertId > 0)? 
                                    resolve(['message' => 'user is created.']) : 
                                    reject(new UserAlreadyExists()); 
                            }
                        );
                    });
            });
    }

    public function findByEmail(string $email): PromiseInterface
    {
        return $this->connection
            ->query("SELECT email, password FROM users WHERE email = ?", [$email])
            ->then(function (Result $result) use($email) {
                if(empty($result->rows))
                    return reject(new UserNotFound());
                return resolve(new UserDTO($email, $result->rows[0]['password']));
            });
    }

    public function authenticate(string $email, string $password): PromiseInterface
    {
        return $this->findByEmail($email)
            ->then(function (UserDTO $user) use($password) {
                if(password_verify($password, $user->password))
                    return resolve(true);
                return reject(new BadCredential());
            });
    }
}