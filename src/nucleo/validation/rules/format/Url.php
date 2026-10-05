<?php

namespace nucleo\validation\rules\format;

use nucleo\validation\Rule;

class Url implements Rule
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

        if (!is_string($value)) {
            return false;
        }

        return filter_var(
            $value,
            FILTER_VALIDATE_URL
        ) !== false;
    }

    public function message(
        string $field,
        array $parameters = []
    ): string {
        return sprintf(
            'The %s field must be a valid URL.',
            $field
        );
    }
}
