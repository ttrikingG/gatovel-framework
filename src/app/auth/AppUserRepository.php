<?php

namespace app\auth;

use app\models\User;
use nucleo\auth\contracts\Authenticatable;
use nucleo\auth\contracts\UserRepository;

class AppUserRepository implements UserRepository
{
    public function find(
        mixed $identifier
    ): ?Authenticatable {
        return User::find(
            $identifier
        );
    }

    public function findByEmail(
        string $email
    ): ?Authenticatable {
        $data = User::where(
            'email',
            $email
        )->first();

        if (
            !is_array($data)
            || $data === []
        ) {
            return null;
        }

        return new User(
            $data
        );
    }

    public function save(
        Authenticatable $user
    ): bool {
        if (!$user instanceof User) {
            return false;
        }

        return $user->save();
    }

    public function create(
        array $attributes
    ): ?Authenticatable {
        $user = new User(
            $attributes
        );

        if (!$user->save()) {
            return null;
        }

        return $user;
    }
}
