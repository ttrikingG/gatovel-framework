<?php

namespace nucleo\validation\rules\selection;

use nucleo\validation\Rule;

class NotIn implements Rule
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

        foreach ($parameters as $disallowed) {
            if ((string) $value === $disallowed) {
                return false;
            }
        }

        return true;
    }

    public function message(
        string $field,
        array $parameters = []
    ): string {
        return sprintf(
            'The %s field must not be one of: %s.',
            $field,
            implode(', ', $parameters)
        );
    }
}
