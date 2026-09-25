<?php

namespace Gatovel\Database\migration;

use Gatovel\Database\Database;
use Gatovel\Database\migration\Migration;

class CreatePermissionRoleTable extends Migration
{
    public function up(): void
    {
        Database::schema()->create(
            'permission_role',
            [
                'permission_id' => 'INT NOT NULL',
                'role_id' => 'INT NOT NULL',
                'PRIMARY KEY' => '(permission_id, role_id)',
            ]
        );
    }

    public function down(): void
    {
        Database::schema()->drop('permission_role');
    }
}
