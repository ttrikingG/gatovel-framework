<?php

namespace app\models;

use Gatovel\Database\orm\ActiveRecord;

class Permission extends ActiveRecord
{
    protected static string $table = 'permissions';
}
