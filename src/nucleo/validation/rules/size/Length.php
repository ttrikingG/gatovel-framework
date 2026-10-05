<?php

namespace nucleo\validation\rules\size;

use nucleo\validation\Rule;

class Length implements Rule
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

        if (
            !isset($parameters[0])
            || !is_numeric($parameters[0])
        ) {
            return false;
        }

        $expected = (int) $parameters[0];

        if ($expected < 0) {
            return false;
        }

        if (is_string($value)) {
            return mb_strlen($value) === $expected;
        }

        if (is_array($value)) {
            return count($value) === $expected;
        }

        return false;
    }

    public function message(
        string $field,
        array $parameters = []
    ): string {
        return sprintf(
            'The %s field must have a length of %s.',
            $field,
            $parameters[0] ?? '?'
        );
    }
}
