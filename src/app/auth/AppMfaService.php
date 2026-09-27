<?php

namespace app\auth;

use app\models\User;
use nucleo\auth\contracts\Authenticatable;
use nucleo\auth\contracts\MfaService;
use nucleo\auth\mfa\Mfa;

class AppMfaService implements MfaService
{
    public function isEnabled(
        Authenticatable $user
    ): bool {
        $userModel = User::find(
            $user->getAuthIdentifier()
        );

        if ($userModel === null) {
            return false;
        }

        return Mfa::isEnabled(
            $userModel
        );
    }

    public function verify(
        Authenticatable $user,
        string $code
    ): bool {
        $userModel = User::find(
            $user->getAuthIdentifier()
        );

        if ($userModel === null) {
            return false;
        }

        return Mfa::verify(
            $userModel,
            $code
        );
    }
}
