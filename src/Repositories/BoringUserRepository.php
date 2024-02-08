<?php

namespace Sakydev\Boring\Repositories;

use Sakydev\Boring\Models\BoringUser;

class BoringUserRepository
{
    public function getById(int $userId): ?BoringUser {
        return (new BoringUser())->find($userId);
    }

    public function getByEmail(string $email): ?BoringUser {
        return (new BoringUser())->where('email', $email)->get();
    }

    public function store(array $userData): BoringUser {
        $user = new BoringUser();

        $user->fill($userData);
        $user->save();
        $user->auth_token = $user->createToken('auth_token')->plainTextToken;

        return $user;
    }
}
