<?php

namespace App\Models;

use Clue\React\SQLite\DatabaseInterface;
use Clue\React\SQLite\Result;
use React\Promise\PromiseInterface;
use App\Exceptions\UserNotFound;
use App\Models\DTO\UserDTO;

class UserModel
{
    private $connection;

    public function __construct(DatabaseInterface $connection)
    {
        $this->connection = $connection;
    }

    private function map(array $row)
    {
        return new UserDTO($row['email'], $row['password']);
    }

    public function create(string $email, string $password): PromiseInterface
    {
        $sql = "INSERT INTO users(email, password) VALUES (?,?)";
        return $this->connection
            ->query($sql, [$email, $password])
            ->then(function (Result $result) use ($email, $password) {
                if($result->insertId !== 0){
                    return new UserDTO($email, $password);
                }

                throw new UserNotFound('User not found.');
            }
        );
    }

    public function getAll(): PromiseInterface
    {
        return $this->connection
            ->query("SELECT email, password FROM users")
            ->then(function (Result $result) {
                if(empty($result->rows)) 
                    throw new UserNotFound('sorry, but user you are searching for is not found');

                return array_map(
                    function (array $row) {
                        return $this->map($row);
                    }, $result->rows
                );
            });
    }

    public function getOne(string $email): PromiseInterface
    {
        return $this->connection
            ->query("SELECT email, password FROM users WHERE email = ?", [$email])
            ->then(function (Result $result) {
                if(empty($result->rows))
                    throw new UserNotFound('User not found.');

                return $this->map($result->rows[0]);
            });
    }

    public function delete(string $email): PromiseInterface
    {
        return $this->connection
            ->query("DELETE FROM users WHERE email = ?", [$email])
            ->then(function (Result $result) {
                if($result->changed === 0)
                    throw new UserNotFound('User not found.');
                return $result->changed;
            });
    }
}