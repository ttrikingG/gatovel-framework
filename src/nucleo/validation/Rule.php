<?php

namespace nucleo\validation;

interface Rule
{
    public function validate(
        string $field,
        mixed $value,
        array $data,
        array $parameters = [],
        array $context = []
    ): bool;

    public function message(
        string $field,
        array $parameters = []
    ): string;
}
