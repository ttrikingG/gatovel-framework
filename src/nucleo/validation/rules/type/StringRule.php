<?php

namespace nucleo\validation\rules\type;

use nucleo\validation\Rule;

class StringRule implements Rule
{
    public function validate(
        string $field,
        mixed $value,
        array $data,
        array $parameters = [],
        array $context = []
    ): bool {
        if ($value === null) {
            return true;
        }

        return is_string($value);
    }

    public function message(
        string $field,
        array $parameters = []
    ): string {
        return sprintf(
            'The %s field must be a string.',
            $field
        );
    }
}
