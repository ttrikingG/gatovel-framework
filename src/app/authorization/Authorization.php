<?php

namespace app\authorization;

use Gatovel\Database\Database;
use nucleo\auth\authentication\Auth;
use nucleo\auth\contracts\Authenticatable;

class Authorization
{
    public static function can(
        string $permission
    ): bool {
        $user = Auth::user();

        if ($user === null) {
            return false;
        }

        return self::userCan(
            $user,
            $permission
        );
    }

    public static function hasRole(
        string $role
    ): bool {
        $user = Auth::user();

        if ($user === null) {
            return false;
        }

        return self::userHasRole(
            $user,
            $role
        );
    }

    public static function allows(
        string $ability,
        mixed $target = null,
        ?string $resource = null
    ): bool {
        $user = Auth::user();

        if ($user === null) {
            return false;
        }

        if ($target === null) {

            if (
                !is_string($resource)
                || $resource === ''
            ) {
                throw new \RuntimeException(
                    'O recurso de autorização não foi definido.'
                );
            }

            $permission = $resource . '.' . $ability;

            return self::userCan(
                $user,
                $permission
            );
        }

        $policy = PolicyResolver::resolve(
            $target
        );

        if (!method_exists($policy, $ability)) {
            throw new \RuntimeException(
                sprintf(
                    'A Policy %s não possui a habilidade "%s".',
                    $policy::class,
                    $ability
                )
            );
        }

        $permission = self::resolvePermission(
            $ability,
            $policy
        );

        if (!self::userCan(
            $user,
            $permission
        )) {
            return false;
        }

        return $policy->{$ability}(
            $user,
            $target
        );
    }

    public static function userCan(
        Authenticatable $user,
        string $permission
    ): bool {
        $sql = '
            SELECT 1
            FROM role_user
            INNER JOIN permission_role
                ON permission_role.role_id = role_user.role_id
            INNER JOIN permissions
                ON permissions.id = permission_role.permission_id
            WHERE role_user.user_id = ?
              AND permissions.name = ?
            LIMIT 1
        ';

        $statement = Database::connection()->prepare(
            $sql
        );

        $statement->execute([
            $user->getAuthIdentifier(),
            $permission,
        ]);

        return $statement->fetchColumn() !== false;
    }

    public static function userHasRole(
        Authenticatable $user,
        string $role
    ): bool {
        $sql = '
            SELECT 1
            FROM role_user
            INNER JOIN roles
                ON roles.id = role_user.role_id
            WHERE role_user.user_id = ?
              AND roles.name = ?
            LIMIT 1
        ';

        $statement = Database::connection()->prepare(
            $sql
        );

        $statement->execute([
            $user->getAuthIdentifier(),
            $role,
        ]);

        return $statement->fetchColumn() !== false;
    }

    private static function resolvePermission(
        string $ability,
        object $policy
    ): string {
        $resource = match ($policy::class) {
            \app\authorization\policies\UserPolicy::class
                => 'users',

            default => throw new \RuntimeException(
                sprintf(
                    'Não foi possível determinar o recurso da Policy %s.',
                    $policy::class
                )
            ),
        };

        return $resource . '.' . $ability;
    }
}