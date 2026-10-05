<?php

namespace nucleo\validation\rules\presence;

use nucleo\validation\Rule;

class Required implements Rule
{
    public function validate(
        string $field,
        mixed $value,
        array $data,
        array $parameters = [],
        array $context = []
    ): bool {
        if (!array_key_exists($field, $data)) {
            return false;
        }

        if ($value === null) {
            return false;
        }

        if (is_string($value)) {
            return trim($value) !== '';
        }

        if (is_array($value)) {
            return $value !== [];
        }

        return true;
    }

    public function message(
        string $field,
        array $parameters = []
    ): string {
        return sprintf(
            'The %s field is required.',
            $field
        );
    }
}
