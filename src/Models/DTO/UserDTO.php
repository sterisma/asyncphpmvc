<?php

namespace App\Models\DTO;

class UserDTO
{
    public $email;
    public $password;

    public function __construct(string $email, string $password)
    {
        $this->email = $email;
        $this->password = $password;
    }

    public function toArray()
    {
        return [
            'email' => $this->email, 
            'password' => $this->password,
        ];
    }
}