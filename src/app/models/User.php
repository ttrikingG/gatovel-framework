<?php

namespace app\models;

use Gatovel\Database\orm\ActiveRecord;
use nucleo\auth\contracts\MfaAuthenticatable;

class User extends ActiveRecord implements MfaAuthenticatable
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

    public function getAuthEmail(): string
    {
        return (string) $this->email;
    }
}