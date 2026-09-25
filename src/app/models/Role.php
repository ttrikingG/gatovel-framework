<?php

namespace app\models;

use Gatovel\Database\orm\ActiveRecord;

class Role extends ActiveRecord
{
    protected static string $table = 'roles';
}
