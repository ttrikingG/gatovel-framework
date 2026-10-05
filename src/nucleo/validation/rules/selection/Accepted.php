<?php

namespace nucleo\validation\rules\selection;

use nucleo\validation\Rule;

class Accepted implements Rule
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
                1,
                '1',
                'yes',
                'on',
                'true',
            ],
            true
        );
    }

    public function message(
        string $field,
        array $parameters = []
    ): string {
        return sprintf(
            'The %s field must be accepted.',
            $field
        );
    }
}
