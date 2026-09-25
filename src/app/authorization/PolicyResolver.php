<?php

namespace app\authorization;

use app\models\User;
use app\authorization\policies\UserPolicy;

class PolicyResolver
{
    public static function resolve(
        mixed $target
    ): object {
        return match (true) {
            $target instanceof User => new UserPolicy(),

            default => throw new \RuntimeException(
                'Nenhuma Policy encontrada para o recurso informado.'
            ),
        };
    }
}
