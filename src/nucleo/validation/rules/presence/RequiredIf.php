<?php

namespace nucleo\validation\rules\presence;

use nucleo\validation\Rule;

class RequiredIf implements Rule
{
    public function validate(
        string $field,
        mixed $value,
        array $data,
        array $parameters = [],
        array $context = []
    ): bool {
        if (!isset($parameters[0], $parameters[1])) {
            return false;
        }

        $otherField = $parameters[0];
        $expectedValues = array_slice($parameters, 1);

        $otherValue = $data[$otherField] ?? null;

        $required = false;

        foreach ($expectedValues as $expectedValue) {
            if ((string) $otherValue === $expectedValue) {
                $required = true;
                break;
            }
        }

        if (!$required) {
            return true;
        }

        return $this->hasValue($field, $value, $data);
    }

    public function message(
        string $field,
        array $parameters = []
    ): string {
        return sprintf(
            'The %s field is required when %s is one of: %s.',
            $field,
            $parameters[0] ?? '?',
            implode(', ', array_slice($parameters, 1))
        );
    }

    private function hasValue(
        string $field,
        mixed $value,
        array $data
    ): bool {
        if (!array_key_exists($field, $data)) {
            return false;
        }

        if ($value === null) {
            return false;
        }

        if (is_string($value)) {
            return trim($value) !== '';
        }

        if (is_array($value)) {
            return $value !== [];
        }

        return true;
    }
}
