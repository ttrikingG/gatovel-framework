<?php

namespace nucleo\validation\rules\presence;

use nucleo\validation\Rule;

class Nullable implements Rule
{
    public function validate(
        string $field,
        mixed $value,
        array $data,
        array $parameters = [],
        array $context = []
    ): bool {
        return true;
    }

    public function message(
        string $field,
        array $parameters = []
    ): string {
        return '';
    }
}
