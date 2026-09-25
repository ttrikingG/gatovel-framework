<?php

namespace nucleo\auth\oauth;

use app\models\OAuthAccount;
use app\models\User;
use nucleo\auth\authentication\Password;
use nucleo\auth\oauth\exceptions\OAuthException;

class OAuthAccountService
{
    public static function findUser(
        string $provider,
        string $providerUserId
    ): ?User {

        if (
            trim($provider) === ''
            || trim($providerUserId) === ''
        ) {
            return null;
        }

        $account = OAuthAccount::where(
            'provider',
            $provider
        )->where(
            'provider_user_id',
            $providerUserId
        )->first();

        if ($account === null) {
            return null;
        }

        return User::find(
            $account['user_id']
        );
    }

    public static function findOrCreateUser(
        string $provider,
        array $oauthUser
    ): User {

        $providerUserId = $oauthUser['sub'] ?? null;

        if (
            !is_string($providerUserId)
            || trim($providerUserId) === ''
        ) {
            throw new OAuthException(
                'O provedor OAuth não retornou um identificador válido.'
            );
        }

        $existingUser = self::findUser(
            $provider,
            $providerUserId
        );

        if ($existingUser !== null) {
            return $existingUser;
        }

        $email = $oauthUser['email'] ?? null;

        if (
            !is_string($email)
            || trim($email) === ''
        ) {
            throw new OAuthException(
                'O provedor OAuth não retornou um e-mail válido.'
            );
        }

        $emailVerified = $oauthUser['email_verified'] ?? false;

        if ($emailVerified !== true) {
            throw new OAuthException(
                'O e-mail da conta OAuth não foi verificado.'
            );
        }

        $existingUserData = User::where(
            'email',
            $email
        )->first();

        if ($existingUserData !== null) {
            $user = User::find(
                $existingUserData['id']
            );

            if ($user === null) {
                throw new OAuthException(
                    'O usuário encontrado não pôde ser carregado.'
                );
            }
        } else {
            $user = new User();

            $user->name = $oauthUser['name']
                ?? $email;

            $user->email = $email;

            $user->password = null;

            if (!$user->save()) {
                throw new OAuthException(
                    'Não foi possível criar o usuário OAuth.'
                );
            }
        }

        $account = new OAuthAccount();

        $account->user_id = $user->id;
        $account->provider = $provider;
        $account->provider_user_id = $providerUserId;

        if (!$account->save()) {
            throw new OAuthException(
                'Não foi possível vincular a conta OAuth.'
            );
        }

        return $user;
    }
}