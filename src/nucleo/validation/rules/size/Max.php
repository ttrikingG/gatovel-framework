<?php

namespace nucleo\validation\rules\size;

use nucleo\validation\Rule;

class Max implements Rule
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

        $maximum = (float) $parameters[0];

        if (
            in_array('integer', $context, true)
            || in_array('numeric', $context, true)
        ) {
            return is_numeric($value)
                && (float) $value <= $maximum;
        }

        if (in_array('array', $context, true)) {
            return is_array($value)
                && count($value) <= $maximum;
        }

        if (in_array('string', $context, true)) {
            return is_string($value)
                && mb_strlen($value) <= $maximum;
        }

        if (is_array($value)) {
            return count($value) <= $maximum;
        }

        if (is_string($value)) {
            return mb_strlen($value) <= $maximum;
        }

        if (is_numeric($value)) {
            return (float) $value <= $maximum;
        }

        return false;
    }

    public function message(
        string $field,
        array $parameters = []
    ): string {
        return sprintf(
            'The %s field must have a maximum value or length of %s.',
            $field,
            $parameters[0] ?? '?'
        );
    }
}
