<?php

namespace Sakydev\Boring\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Sakydev\Boring\Exceptions\NotFoundException;
use Sakydev\Boring\Exceptions\UnauthorisedException;
use Sakydev\Boring\Models\BoringUser;
use Sakydev\Boring\Repositories\BoringUserRepository;

class UserService
{
    public function __construct(readonly BoringUserRepository $userRepository) {}

    public function store(array $content): BoringUser {
        $content['password'] = Hash::make($content['password']);

        return $this->userRepository->store($content);
    }

    /**
     * @throws UnauthorisedException
     */
    public function login(array $credentials): BoringUser {
        if (!Auth::attempt($credentials)) {
            throw new UnauthorisedException('auth.error.invalidCredentials');
        }

        $token = auth()->user()->createToken('auth_token')->plainTextToken;
        $user = $this->userRepository->getByEmail($credentials['email']);
        $user->auth_token = $token;

        return $user;
    }

    /**
     * @throws NotFoundException
     */
    public function getById(int $userId): BoringUser {
        $user = $this->userRepository->getById($userId);
        if (!$user->id) {
            throw new NotFoundException('item.error.notFound');
        }

        return $user;
    }
}
