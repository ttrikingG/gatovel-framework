<?php

namespace nucleo\validation\rules\date;

use DateTimeImmutable;
use Exception;
use nucleo\validation\Rule;

class Date implements Rule
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

        if (!is_string($value)) {
            return false;
        }

        try {
            new DateTimeImmutable($value);

            return true;
        } catch (Exception) {
            return false;
        }
    }

    public function message(
        string $field,
        array $parameters = []
    ): string {
        return sprintf(
            'The %s field must be a valid date.',
            $field
        );
    }
}
