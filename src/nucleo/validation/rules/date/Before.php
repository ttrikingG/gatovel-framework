<?php

namespace nucleo\validation\rules\date;

use DateTimeImmutable;
use Exception;
use nucleo\validation\Rule;

class Before implements Rule
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

        $target = $parameters[0];

        if (array_key_exists($target, $data)) {
            $target = $data[$target];
        }

        if (!is_string($target) || $target === '') {
            return false;
        }

        try {
            $date = new DateTimeImmutable($value);
            $targetDate = new DateTimeImmutable($target);

            return $date < $targetDate;
        } catch (Exception) {
            return false;
        }
    }

    public function message(
        string $field,
        array $parameters = []
    ): string {
        return sprintf(
            'The %s field must be before %s.',
            $field,
            $parameters[0] ?? '?'
        );
    }
}
