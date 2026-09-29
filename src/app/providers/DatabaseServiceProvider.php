<?php

namespace app\providers;

use Gatovel\Database\Database;
use nucleo\config\Config;

class DatabaseServiceProvider
{
    public static function boot(): void
    {
        Database::connect(
            Config::get('database')
        );
    }
}
