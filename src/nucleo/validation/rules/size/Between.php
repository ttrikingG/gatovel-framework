<?php

namespace nucleo\validation\rules\size;

use nucleo\validation\Rule;

class Between implements Rule
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
            !isset($parameters[0], $parameters[1])
            || !is_numeric($parameters[0])
            || !is_numeric($parameters[1])
        ) {
            return false;
        }

        $minimum = (float) $parameters[0];
        $maximum = (float) $parameters[1];

        if ($minimum > $maximum) {
            return false;
        }

        if (
            in_array('integer', $context, true)
            || in_array('numeric', $context, true)
        ) {
            if (!is_numeric($value)) {
                return false;
            }

            $number = (float) $value;

            return $number >= $minimum
                && $number <= $maximum;
        }

        if (in_array('array', $context, true)) {
            if (!is_array($value)) {
                return false;
            }

            $length = count($value);

            return $length >= $minimum
                && $length <= $maximum;
        }

        if (in_array('string', $context, true)) {
            if (!is_string($value)) {
                return false;
            }

            $length = mb_strlen($value);

            return $length >= $minimum
                && $length <= $maximum;
        }

        if (is_array($value)) {
            $length = count($value);

            return $length >= $minimum
                && $length <= $maximum;
        }

        if (is_string($value)) {
            $length = mb_strlen($value);

            return $length >= $minimum
                && $length <= $maximum;
        }

        if (is_numeric($value)) {
            $number = (float) $value;

            return $number >= $minimum
                && $number <= $maximum;
        }

        return false;
    }

    public function message(
        string $field,
        array $parameters = []
    ): string {
        return sprintf(
            'The %s field must be between %s and %s.',
            $field,
            $parameters[0] ?? '?',
            $parameters[1] ?? '?'
        );
    }
}
