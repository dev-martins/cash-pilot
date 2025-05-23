<?php

namespace App\Repositories\Auth;

use App\Models\User;

class AuthRepository
{
    public function __construct(protected User $user) {}

    public function register(array $data)
    {
        return $this->user->register($data);
    }

    public function findUser(int $userId)
    {
        return $this->user->findUser($userId);
    }
}
