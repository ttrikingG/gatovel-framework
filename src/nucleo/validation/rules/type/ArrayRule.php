<?php

namespace nucleo\validation\rules\type;

use nucleo\validation\Rule;

class ArrayRule implements Rule
{
    public function validate(
        string $field,
        mixed $value,
        array $data,
        array $parameters = [],
        array $context = []
    ): bool {
        if ($value === null || $value === '') {
            return true;
        }

        return is_array($value);
    }

    public function message(
        string $field,
        array $parameters = []
    ): string {
        return sprintf(
            'The %s field must be an array.',
            $field
        );
    }
}
