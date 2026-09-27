<?php

namespace app\auth;

use app\models\User;
use nucleo\auth\contracts\Authenticatable;
use nucleo\auth\contracts\EmailVerificationService;
use nucleo\auth\verification\EmailVerification;

class AppEmailVerificationService
    implements EmailVerificationService
{
    public function isVerified(
        Authenticatable $user
    ): bool {
        $userModel = User::find(
            $user->getAuthIdentifier()
        );

        if ($userModel === null) {
            return false;
        }

        return EmailVerification::isVerified(
            (int) $userModel->id
        );
    }
}
