<?php

namespace nucleo\validation\rules\comparison;

use nucleo\validation\Rule;

class Confirmed implements Rule
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

        $confirmationField = $field . '_confirmation';

        if (!array_key_exists($confirmationField, $data)) {
            return false;
        }

        return $value === $data[$confirmationField];
    }

    public function message(
        string $field,
        array $parameters = []
    ): string {
        return sprintf(
            'The %s field confirmation does not match.',
            $field
        );
    }
}
