<?php

namespace nucleo\validation\rules\presence;

use nucleo\validation\Rule;

class Present implements Rule
{
    public function validate(
        string $field,
        mixed $value,
        array $data,
        array $parameters = [],
        array $context = []
    ): bool {
        return array_key_exists(
            $field,
            $data
        );
    }

    public function message(
        string $field,
        array $parameters = []
    ): string {
        return sprintf(
            'The %s field must be present.',
            $field
        );
    }
}
