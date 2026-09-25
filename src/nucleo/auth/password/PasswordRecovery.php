<?php

namespace nucleo\auth\password;

use app\models\User;
use nucleo\auth\authentication\Password;

class PasswordRecovery
{
    public static function create(
        string $email
    ): ?string {
        $email = trim(
            strtolower($email)
        );

        if ($email === '') {
            return null;
        }

        $userData = User::where(
            'email',
            $email
        )->first();

        if ($userData === null) {
            return null;
        }

        $user = User::find(
            $userData['id']
        );

        if ($user === null) {
            return null;
        }

        return PasswordReset::create(
            (int) $user->id
        );
    }

    public static function buildUrl(
        string $token
    ): string {
        return '/reset-password/'
            . urlencode($token);
    }

    public static function resetTokenIsValid(
        string $token
    ): bool {
        return PasswordReset::findUserId(
            $token
        ) !== null;
    }

    public static function reset(
        string $token,
        string $password
    ): bool {
        $userId = PasswordReset::findUserId(
            $token
        );

        if ($userId === null) {
            return false;
        }

        if (strlen($password) < 8) {
            return false;
        }

        $user = User::find(
            $userId
        );

        if ($user === null) {
            return false;
        }

        $user->password = Password::hash(
            $password
        );

        if (!$user->save()) {
            return false;
        }

        PasswordReset::invalidateForUser(
            $userId
        );

        return true;
    }
}