<?php

namespace app\database\seeder;

use Gatovel\Database\Database;
use nucleo\auth\authentication\Password;

class UserSeeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Tom Garcia',
                'email' => 'tom@example.com',
                'password' => Password::hash('123456'),
            ],
            [
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'password' => Password::hash('123456'),
            ],
            [
                'name' => 'Jane Doe',
                'email' => 'jane@example.com',
                'password' => Password::hash('123456'),
            ],
        ];

        foreach ($users as $user) {
            $existing = Database::table('users')
                ->where('email', $user['email'])
                ->first();

            if ($existing !== null) {
                continue;
            }

            Database::table('users')->insert($user);
        }
    }
}
