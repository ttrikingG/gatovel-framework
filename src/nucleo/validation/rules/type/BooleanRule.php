<?php

namespace nucleo\validation\rules\type;

use nucleo\validation\Rule;

class BooleanRule implements Rule
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

        return in_array(
            $value,
            [
                true,
                false,
                1,
                0,
                '1',
                '0',
            ],
            true
        );
    }

    public function message(
        string $field,
        array $parameters = []
    ): string {
        return sprintf(
            'The %s field must be true or false.',
            $field
        );
    }
}
