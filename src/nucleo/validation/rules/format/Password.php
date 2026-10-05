<?php

namespace nucleo\validation\rules\format;

use nucleo\validation\Rule;

class Password implements Rule
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

        $hasUppercase = preg_match(
            '/[A-Z]/',
            $value
        ) === 1;

        $hasLowercase = preg_match(
            '/[a-z]/',
            $value
        ) === 1;

        $hasNumber = preg_match(
            '/[0-9]/',
            $value
        ) === 1;

        $hasSpecialCharacter = preg_match(
            '/[^A-Za-z0-9]/',
            $value
        ) === 1;

        return $hasUppercase
            && $hasLowercase
            && $hasNumber
            && $hasSpecialCharacter;
    }

    public function message(
        string $field,
        array $parameters = []
    ): string {
        return sprintf(
            'The %s field must contain uppercase, lowercase, number and special characters.',
            $field
        );
    }
}
