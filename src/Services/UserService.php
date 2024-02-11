<?php

namespace Sakydev\Boring\Services;

use App\Models\User;
use Sakydev\Boring\Exceptions\NotFoundException;
use Sakydev\Boring\Models\BoringUser;
use Sakydev\Boring\Repositories\BoringUserRepository;

class UserService
{
    public function __construct(readonly BoringUserRepository $userRepository) {}

    public function register() {

    }

    public function login() {

    }

    public function getById(int $userId): BoringUser {
        $user = $this->userRepository->getById($userId);
        if (!$user->id) {
            throw new NotFoundException('item.error.notFound');
        }

        return $user;
    }
}
