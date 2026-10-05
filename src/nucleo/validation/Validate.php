<?php

namespace nucleo\validation;

use InvalidArgumentException;

use nucleo\validation\rules\comparison\Confirmed;
use nucleo\validation\rules\comparison\Different;
use nucleo\validation\rules\comparison\Same;

use nucleo\validation\rules\date\After;
use nucleo\validation\rules\date\Before;
use nucleo\validation\rules\date\Date;

use nucleo\validation\rules\format\Email;
use nucleo\validation\rules\format\Password;
use nucleo\validation\rules\format\Regex;
use nucleo\validation\rules\format\Url;

use nucleo\validation\rules\presence\Filled;
use nucleo\validation\rules\presence\Nullable;
use nucleo\validation\rules\presence\Present;
use nucleo\validation\rules\presence\Required;
use nucleo\validation\rules\presence\RequiredIf;
use nucleo\validation\rules\presence\RequiredUnless;
use nucleo\validation\rules\presence\RequiredWith;
use nucleo\validation\rules\presence\RequiredWithout;

use nucleo\validation\rules\selection\Accepted;
use nucleo\validation\rules\selection\In;
use nucleo\validation\rules\selection\NotIn;

use nucleo\validation\rules\size\Between;
use nucleo\validation\rules\size\Length;
use nucleo\validation\rules\size\Max;
use nucleo\validation\rules\size\Min;

use nucleo\validation\rules\type\ArrayRule;
use nucleo\validation\rules\type\BooleanRule;
use nucleo\validation\rules\type\IntegerRule;
use nucleo\validation\rules\type\Numeric;
use nucleo\validation\rules\type\StringRule;

class Validate
{
    private array $data;

    private array $errors = [];

    private array $validated = [];

    private bool $executed = false;

    /**
     * Rules that must be allowed to execute even when
     * the field is not present in the input data.
     */
    private const PRESENCE_RULES = [
        'required',
        'required_if',
        'required_with',
        'required_without',
        'required_unless',
        'present',
    ];

    /**
     * @var array<string, Rule>
     */
    private array $rules = [];

    public function __construct(
        array $data = []
    ) {
        $this->data = $data;

        $this->registerDefaultRules();
    }

    public function data(): array
    {
        return $this->data;
    }

    public function setData(
        array $data
    ): static {
        $this->data = $data;

        $this->reset();

        return $this;
    }

    public function addRule(
        string $name,
        Rule $rule
    ): static {
        $name = trim(
            strtolower($name)
        );

        if ($name === '') {
            throw new InvalidArgumentException(
                'Validation rule name cannot be empty.'
            );
        }

        $this->rules[$name] = $rule;

        return $this;
    }

    public function hasRule(
        string $name
    ): bool {
        return array_key_exists(
            strtolower($name),
            $this->rules
        );
    }

    /**
     * Validation rules defined by specialized application validators.
     *
     * Child classes may override this method to provide their own
     * validation rules.
     */
    public function rules(): array
    {
        return [];
    }

    /**
     * Execute the rules defined by the specialized validator.
     */
    public function run(): static
    {
        return $this->validate(
            $this->rules()
        );
    }

    public function validate(
        array $rules
    ): static {
        $this->reset();

        foreach ($rules as $field => $fieldRules) {
            if (!is_string($field)) {
                continue;
            }

            $normalizedRules = $this->normalizeRules(
                $fieldRules
            );

            $parsedRules = [];

            foreach ($normalizedRules as $ruleDefinition) {
                $parsedRules[] = $this->parseRule(
                    $ruleDefinition
                );
            }

            $context = $this->buildContext(
                $parsedRules
            );

            $exists = array_key_exists(
                $field,
                $this->data
            );

            $value = $exists
                ? $this->data[$field]
                : null;

            $nullable = in_array(
                'nullable',
                $context,
                true
            );

            $hasPresenceRule = $this->hasPresenceRule(
                $context
            );

            /*
             * A completely optional field that was not supplied
             * does not need validation.
             *
             * Presence rules are the exception because their job
             * may specifically be to require an absent field.
             */
            if (!$exists && !$hasPresenceRule) {
                continue;
            }

            /*
             * An explicitly null nullable field is valid unless
             * one of the presence rules requires it.
             *
             * Presence rules still execute below so conditional
             * requirements can decide whether null is acceptable.
             */
            if (
                $exists
                && $value === null
                && $nullable
                && !$hasPresenceRule
            ) {
                $this->validated[$field] = null;

                continue;
            }

            foreach ($parsedRules as [
                $ruleName,
                $parameters,
            ]) {
                if (!$this->hasRule($ruleName)) {
                    throw new InvalidArgumentException(
                        sprintf(
                            'Validation rule "%s" is not registered.',
                            $ruleName
                        )
                    );
                }

                /*
                 * When the field does not exist, only presence
                 * rules need to execute.
                 *
                 * Rules such as string, email, min and date validate
                 * values, so there is no value for them to inspect.
                 */
                if (
                    !$exists
                    && !$this->isPresenceRule($ruleName)
                ) {
                    continue;
                }

                /*
                 * Nullable fields containing null skip normal value
                 * rules, but presence rules still execute.
                 */
                if (
                    $exists
                    && $value === null
                    && $nullable
                    && !$this->isPresenceRule($ruleName)
                ) {
                    continue;
                }

                $rule = $this->rules[$ruleName];

                if (
                    !$rule->validate(
                        $field,
                        $value,
                        $this->data,
                        $parameters,
                        $context
                    )
                ) {
                    $this->errors[$field][] = $rule->message(
                        $field,
                        $parameters
                    );
                }
            }

            if (
                !array_key_exists(
                    $field,
                    $this->errors
                )
                && $exists
            ) {
                $this->validated[$field] = $value;
            }
        }

        $this->executed = true;

        return $this;
    }

    public function passes(): bool
    {
        return $this->executed
            && $this->errors === [];
    }

    public function fails(): bool
    {
        return $this->executed
            && $this->errors !== [];
    }

    public function errors(): array
    {
        return $this->errors;
    }

    public function first(
        ?string $field = null
    ): ?string {
        if ($field !== null) {
            $errors = $this->errors[$field]
                ?? [];

            return $errors[0]
                ?? null;
        }

        foreach ($this->errors as $errors) {
            if (isset($errors[0])) {
                return $errors[0];
            }
        }

        return null;
    }

    public function validated(): array
    {
        if (!$this->passes()) {
            return [];
        }

        return $this->validated;
    }

    private function registerDefaultRules(): void
    {
        /*
         * Presence
         */
        $this->addRule(
            'required',
            new Required()
        );

        $this->addRule(
            'required_if',
            new RequiredIf()
        );

        $this->addRule(
            'required_with',
            new RequiredWith()
        );

        $this->addRule(
            'required_without',
            new RequiredWithout()
        );

        $this->addRule(
            'required_unless',
            new RequiredUnless()
        );

        $this->addRule(
            'present',
            new Present()
        );

        $this->addRule(
            'filled',
            new Filled()
        );

        $this->addRule(
            'nullable',
            new Nullable()
        );

        /*
         * Types
         */
        $this->addRule(
            'string',
            new StringRule()
        );

        $this->addRule(
            'integer',
            new IntegerRule()
        );

        $this->addRule(
            'numeric',
            new Numeric()
        );

        $this->addRule(
            'boolean',
            new BooleanRule()
        );

        $this->addRule(
            'array',
            new ArrayRule()
        );

        /*
         * Format
         */
        $this->addRule(
            'email',
            new Email()
        );

        $this->addRule(
            'url',
            new Url()
        );

        $this->addRule(
            'regex',
            new Regex()
        );

        $this->addRule(
            'password',
            new Password()
        );

        /*
         * Size
         */
        $this->addRule(
            'min',
            new Min()
        );

        $this->addRule(
            'max',
            new Max()
        );

        $this->addRule(
            'between',
            new Between()
        );

        $this->addRule(
            'length',
            new Length()
        );

        /*
         * Comparison
         */
        $this->addRule(
            'same',
            new Same()
        );

        $this->addRule(
            'different',
            new Different()
        );

        $this->addRule(
            'confirmed',
            new Confirmed()
        );

        /*
         * Selection
         */
        $this->addRule(
            'in',
            new In()
        );

        $this->addRule(
            'not_in',
            new NotIn()
        );

        $this->addRule(
            'accepted',
            new Accepted()
        );

        /*
         * Date
         */
        $this->addRule(
            'date',
            new Date()
        );

        $this->addRule(
            'before',
            new Before()
        );

        $this->addRule(
            'after',
            new After()
        );
    }

    private function hasPresenceRule(
        array $context
    ): bool {
        foreach ($context as $ruleName) {
            if ($this->isPresenceRule($ruleName)) {
                return true;
            }
        }

        return false;
    }

    private function isPresenceRule(
        string $ruleName
    ): bool {
        return in_array(
            $ruleName,
            self::PRESENCE_RULES,
            true
        );
    }

    private function buildContext(
        array $rules
    ): array {
        $context = [];

        foreach ($rules as [$ruleName]) {
            $context[] = $ruleName;
        }

        return array_values(
            array_unique($context)
        );
    }

    private function reset(): void
    {
        $this->errors = [];
        $this->validated = [];
        $this->executed = false;
    }

    private function normalizeRules(
        mixed $rules
    ): array {
        if (is_string($rules)) {
            return array_values(
                array_filter(
                    array_map(
                        'trim',
                        explode('|', $rules)
                    ),
                    static fn (string $rule): bool => $rule !== ''
                )
            );
        }

        if (!is_array($rules)) {
            throw new InvalidArgumentException(
                'Validation rules must be a string or array.'
            );
        }

        return $rules;
    }

    private function parseRule(
        mixed $rule
    ): array {
        if (!is_string($rule)) {
            throw new InvalidArgumentException(
                'Validation rule definitions must be strings.'
            );
        }

        $parts = explode(
            ':',
            $rule,
            2
        );

        $name = strtolower(
            trim($parts[0])
        );

        if ($name === '') {
            throw new InvalidArgumentException(
                'Validation rule name cannot be empty.'
            );
        }

        if (!isset($parts[1])) {
            return [
                $name,
                [],
            ];
        }

        $parameters = array_map(
            'trim',
            explode(',', $parts[1])
        );

        return [
            $name,
            $parameters,
        ];
    }
}
