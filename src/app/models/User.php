<?php

namespace app\models;

use Gatovel\Database\orm\ActiveRecord;
use nucleo\auth\contracts\Authenticatable;

class User extends ActiveRecord implements Authenticatable
{
    protected static string $table = 'users';

    public function getAuthIdentifier(): mixed
    {
        return $this->id;
    }

    public function getAuthPassword(): string
    {
        return $this->password;
    }
}