<?php

namespace nucleo\validation\rules\comparison;

use nucleo\validation\Rule;

class Same implements Rule
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

        if (!isset($parameters[0])) {
            return false;
        }

        $otherField = $parameters[0];

        if (!array_key_exists($otherField, $data)) {
            return false;
        }

        return $value === $data[$otherField];
    }

    public function message(
        string $field,
        array $parameters = []
    ): string {
        return sprintf(
            'The %s field must match the %s field.',
            $field,
            $parameters[0] ?? '?'
        );
    }
}
