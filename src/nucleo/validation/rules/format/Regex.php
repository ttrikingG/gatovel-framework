<?php

namespace nucleo\validation\rules\format;

use nucleo\validation\Rule;

class Regex implements Rule
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
            !is_string($value)
            || !isset($parameters[0])
            || $parameters[0] === ''
        ) {
            return false;
        }

        $pattern = $parameters[0];

        $result = @preg_match(
            $pattern,
            $value
        );

        return $result === 1;
    }

    public function message(
        string $field,
        array $parameters = []
    ): string {
        return sprintf(
            'The %s field format is invalid.',
            $field
        );
    }
}
