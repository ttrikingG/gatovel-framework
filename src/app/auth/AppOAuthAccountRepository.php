<?php

namespace app\auth;

use app\models\OAuthAccount;
use nucleo\auth\contracts\OAuthAccountRepository;

class AppOAuthAccountRepository
    implements OAuthAccountRepository
{
    public function findUserId(
        string $provider,
        string $providerUserId
    ): ?int {
        $data = OAuthAccount::where(
            'provider',
            $provider
        )
        ->where(
            'provider_user_id',
            $providerUserId
        )
        ->first();

        if (
            !is_array($data)
            || $data === []
        ) {
            return null;
        }

        $userId = $data['user_id'] ?? null;

        if (
            !is_numeric($userId)
        ) {
            return null;
        }

        return (int) $userId;
    }

    public function create(
        int $userId,
        string $provider,
        string $providerUserId
    ): bool {
        $account = new OAuthAccount([
            'user_id' => $userId,
            'provider' => $provider,
            'provider_user_id' => $providerUserId,
        ]);

        return $account->save();
    }
}
