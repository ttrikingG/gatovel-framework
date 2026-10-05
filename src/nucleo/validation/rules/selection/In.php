<?php

namespace nucleo\validation\rules\selection;

use nucleo\validation\Rule;

class In implements Rule
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

        if ($parameters === []) {
            return false;
        }

        foreach ($parameters as $allowed) {
            if ((string) $value === $allowed) {
                return true;
            }
        }

        return false;
    }

    public function message(
        string $field,
        array $parameters = []
    ): string {
        return sprintf(
            'The %s field must be one of: %s.',
            $field,
            implode(', ', $parameters)
        );
    }
}
