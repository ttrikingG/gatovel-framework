<?php

namespace nucleo\validation\rules\type;

use nucleo\validation\Rule;

class IntegerRule implements Rule
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

        if (is_int($value)) {
            return true;
        }

        if (!is_string($value)) {
            return false;
        }

        return filter_var(
            $value,
            FILTER_VALIDATE_INT
        ) !== false;
    }

    public function message(
        string $field,
        array $parameters = []
    ): string {
        return sprintf(
            'The %s field must be an integer.',
            $field
        );
    }
}
