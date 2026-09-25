<?php

namespace app\models;

use Gatovel\Database\orm\ActiveRecord;

class OAuthAccount extends ActiveRecord
{
    protected static string $table = 'oauth_accounts';
}