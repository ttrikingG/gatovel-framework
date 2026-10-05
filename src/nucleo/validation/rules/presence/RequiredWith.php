<?php

namespace nucleo\validation\rules\presence;

use nucleo\validation\Rule;

class RequiredWith implements Rule
{
    public function validate(
        string $field,
        mixed $value,
        array $data,
        array $parameters = [],
        array $context = []
    ): bool {
        if ($parameters === []) {
            return false;
        }

        $required = false;

        foreach ($parameters as $otherField) {
            if ($this->fieldHasValue($otherField, $data)) {
                $required = true;
                break;
            }
        }

        if (!$required) {
            return true;
        }

        return $this->fieldHasValue(
            $field,
            $data
        );
    }

    public function message(
        string $field,
        array $parameters = []
    ): string {
        return sprintf(
            'The %s field is required when any of these fields are present: %s.',
            $field,
            implode(', ', $parameters)
        );
    }

    private function fieldHasValue(
        string $field,
        array $data
    ): bool {
        if (!array_key_exists($field, $data)) {
            return false;
        }

        $value = $data[$field];

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
