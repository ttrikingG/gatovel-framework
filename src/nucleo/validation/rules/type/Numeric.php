<?php

namespace nucleo\validation\rules\type;

use nucleo\validation\Rule;

class Numeric implements Rule
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

        return is_numeric($value);
    }

    public function message(
        string $field,
        array $parameters = []
    ): string {
        return sprintf(
            'The %s field must be numeric.',
            $field
        );
    }
}
