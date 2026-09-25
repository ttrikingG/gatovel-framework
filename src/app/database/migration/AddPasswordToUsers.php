<?php

namespace Gatovel\Database\migration;

use Gatovel\Database\Database;
use Gatovel\Database\migration\Migration;

class AddPasswordToUsers extends Migration
{
    public function up(): void
    {
        Database::schema()->addColumn(
            'users',
            'password',
            'VARCHAR(255)'
        );
    }

    public function down(): void
    {
        //
    }
}
