<?php

namespace app\database\seeder;

use Gatovel\Database\Database;

class UserRolesSeeder
{
    public function run(): void
    {
        $database = Database::connection();

        $database->beginTransaction();

        try {
            $this->createRole('admin');
            $this->createRole('user');

            $this->createPermission('users.view');
            $this->createPermission('users.create');
            $this->createPermission('users.update');
            $this->createPermission('users.delete');

            $adminRole = $this->findRole('admin');
            $userRole = $this->findRole('user');

            $viewPermission = $this->findPermission('users.view');
            $createPermission = $this->findPermission('users.create');
            $updatePermission = $this->findPermission('users.update');
            $deletePermission = $this->findPermission('users.delete');

            $tom = $this->findUser('tom@example.com');
            $john = $this->findUser('john@example.com');
            $jane = $this->findUser('jane@example.com');

            if (
                $adminRole === null
                || $userRole === null
                || $viewPermission === null
                || $createPermission === null
                || $updatePermission === null
                || $deletePermission === null
                || $tom === null
                || $john === null
                || $jane === null
            ) {
                throw new \RuntimeException(
                    'Dados necessários para autorização não encontrados.'
                );
            }

            $this->assignRole(
                $tom['id'],
                $adminRole['id']
            );

            $this->assignRole(
                $john['id'],
                $userRole['id']
            );

            $this->assignRole(
                $jane['id'],
                $userRole['id']
            );

            $this->assignPermission(
                $viewPermission['id'],
                $adminRole['id']
            );

            $this->assignPermission(
                $createPermission['id'],
                $adminRole['id']
            );

            $this->assignPermission(
                $updatePermission['id'],
                $adminRole['id']
            );

            $this->assignPermission(
                $deletePermission['id'],
                $adminRole['id']
            );

            $this->assignPermission(
                $viewPermission['id'],
                $userRole['id']
            );

            $database->commit();
        } catch (\Throwable $exception) {
            $database->rollBack();

            throw $exception;
        }
    }

    private function createRole(string $name): void
    {
        $role = Database::table('roles')
            ->where('name', $name)
            ->first();

        if ($role !== null) {
            return;
        }

        Database::table('roles')->insert([
            'name' => $name,
        ]);
    }

    private function createPermission(string $name): void
    {
        $permission = Database::table('permissions')
            ->where('name', $name)
            ->first();

        if ($permission !== null) {
            return;
        }

        Database::table('permissions')->insert([
            'name' => $name,
        ]);
    }

    private function findRole(string $name): ?array
    {
        return Database::table('roles')
            ->where('name', $name)
            ->first();
    }

    private function findPermission(string $name): ?array
    {
        return Database::table('permissions')
            ->where('name', $name)
            ->first();
    }

    private function findUser(string $email): ?array
    {
        return Database::table('users')
            ->where('email', $email)
            ->first();
    }

    private function assignRole(
        int|string $userId,
        int|string $roleId
    ): void {
        $relation = Database::table('role_user')
            ->where('user_id', $userId)
            ->where('role_id', $roleId)
            ->first();

        if ($relation !== null) {
            return;
        }

        Database::table('role_user')->insert([
            'user_id' => $userId,
            'role_id' => $roleId,
        ]);
    }

    private function assignPermission(
        int|string $permissionId,
        int|string $roleId
    ): void {
        $relation = Database::table('permission_role')
            ->where('permission_id', $permissionId)
            ->where('role_id', $roleId)
            ->first();

        if ($relation !== null) {
            return;
        }

        Database::table('permission_role')->insert([
            'permission_id' => $permissionId,
            'role_id' => $roleId,
        ]);
    }
}
