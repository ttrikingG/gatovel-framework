<?php

namespace app\authorization\policies;

use app\authorization\Authorization;
use app\models\User;

class UserPolicy
{
    public function view(
        User $user
    ): bool {
        return Authorization::userCan(
            $user,
            'users.view'
        );
    }

    public function create(
        User $user
    ): bool {
        return Authorization::userCan(
            $user,
            'users.create'
        );
    }

    public function update(
        User $user,
        User $target
    ): bool {
        if (
            !Authorization::userCan(
                $user,
                'users.update'
            )
        ) {
            return false;
        }

        return true;
    }

    public function delete(
        User $user,
        User $target
    ): bool {
        if (
            !Authorization::userCan(
                $user,
                'users.delete'
            )
        ) {
            return false;
        }

        return $user->id !== $target->id;
    }
}
