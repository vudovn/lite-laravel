<?php

namespace Framework\Validation;

class Validator
{
    /**
     * The data under validation.
     *
     * @var array
     */
    protected $data = [];

    /**
     * The validation rules.
     *
     * @var array
     */
    protected $rules = [];

    /**
     * The validation messages.
     *
     * @var array
     */
    protected $messages = [];

    /**
     * The validation error messages.
     *
     * @var array
     */
    protected $errors = [];

    /**
     * The validation attributes.
     *
     * @var array
     */
    protected $attributes = [];

    /**
     * The validation extensions.
     *
     * @var array
     */
    protected static $extensions = [];

    /**
     * The validation rules that imply the field is required.
     *
     * @var array
     */
    protected $implicitRules = [
        'required',
        'filled',
        'required_with',
        'required_with_all',
        'required_without',
        'required_without_all',
        'required_if',
        'required_unless',
    ];

    /**
     * Create a new Validator instance.
     *
     * @param array $data
     * @param array $rules
     * @param array $messages
     * @param array $attributes
     * @return void
     */
    public function __construct(array $data, array $rules, array $messages = [], array $attributes = [])
    {
        $this->data = $data;
        $this->rules = $this->parseRules($rules);
        $this->messages = $messages;
        $this->attributes = $attributes;
    }

    /**
     * Parse the validation rules.
     *
     * @param array $rules
     * @return array
     */
    protected function parseRules(array $rules)
    {
        $parsed = [];

        foreach ($rules as $attribute => $rule) {
            if (is_string($rule)) {
                $rule = explode('|', $rule);
            }

            $parsed[$attribute] = is_array($rule) ? $rule : [$rule];
        }

        return $parsed;
    }

    /**
     * Validate the data against the rules.
     *
     * @return bool
     */
    public function validate()
    {
        $this->errors = [];

        foreach ($this->rules as $attribute => $rules) {
            foreach ($rules as $rule) {
                $this->validateAttribute($attribute, $rule);
            }
        }

        return empty($this->errors);
    }

    /**
     * Validate a specific attribute against a rule.
     *
     * @param string $attribute
     * @param string $rule
     * @return void
     */
    protected function validateAttribute($attribute, $rule)
    {
        // Skip rule if the field is not required and empty
        if (!$this->isRequired($attribute) && !$this->hasData($attribute)) {
            return;
        }

        [$rule, $parameters] = $this->parseRule($rule);

        if ($rule == '') {
            return;
        }

        // First check for a custom validator
        $method = 'validate' . str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $rule)));

        if (method_exists($this, $method)) {
            $this->$method($attribute, $this->getValue($attribute), $parameters);
            return;
        }

        // Then check for extension
        if (isset(static::$extensions[$rule])) {
            $this->callExtension($rule, $attribute, $this->getValue($attribute), $parameters);
            return;
        }

        // Rule doesn't exist - add an error
        $this->addError($attribute, $rule, $parameters);
    }

    /**
     * Call a custom validator extension.
     *
     * @param string $rule
     * @param string $attribute
     * @param mixed $value
     * @param array $parameters
     * @return bool
     */
    protected function callExtension($rule, $attribute, $value, $parameters)
    {
        $callback = static::$extensions[$rule];

        if ($callback instanceof \Closure) {
            if (!$callback($attribute, $value, $parameters, $this)) {
                $this->addError($attribute, $rule, $parameters);
                return false;
            }
            return true;
        }

        return false;
    }

    /**
     * Determine if an attribute has a value.
     *
     * @param string $attribute
     * @return bool
     */
    protected function hasData($attribute)
    {
        return array_key_exists($attribute, $this->data);
    }

    /**
     * Get the value of an attribute.
     *
     * @param string $attribute
     * @return mixed
     */
    protected function getValue($attribute)
    {
        return $this->data[$attribute] ?? null;
    }

    /**
     * Parse the rule into rule name and parameters.
     *
     * @param string|array $rule
     * @return array
     */
    protected function parseRule($rule)
    {
        if (is_string($rule)) {
            $parameters = [];

            if (strpos($rule, ':') !== false) {
                [$rule, $parameter] = explode(':', $rule, 2);
                $parameters = $this->parseParameters($rule, $parameter);
            }

            return [$rule, $parameters];
        }

        return $rule;
    }

    /**
     * Parse a parameter list.
     *
     * @param string $rule
     * @param string $parameter
     * @return array
     */
    protected function parseParameters($rule, $parameter)
    {
        return str_getcsv($parameter);
    }

    /**
     * Determine if the attribute is being validated as required.
     *
     * @param string $attribute
     * @return bool
     */
    protected function isRequired($attribute)
    {
        foreach ($this->rules[$attribute] as $rule) {
            $parsed = $this->parseRule($rule);

            if (in_array($parsed[0], $this->implicitRules)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Add an error message to the validator's collection of errors.
     *
     * @param string $attribute
     * @param string $rule
     * @param array $parameters
     * @return void
     */
    protected function addError($attribute, $rule, $parameters = [])
    {
        $message = $this->getMessage($attribute, $rule);

        $message = $this->replacePlaceholdersInMessage($message, $attribute, $rule, $parameters);

        $this->errors[$attribute][] = $message;
    }

    /**
     * Get the error message for an attribute and rule.
     *
     * @param string $attribute
     * @param string $rule
     * @return string
     */
    protected function getMessage($attribute, $rule)
    {
        // Check for attribute.rule specific message
        if (isset($this->messages["{$attribute}.{$rule}"])) {
            return $this->messages["{$attribute}.{$rule}"];
        }

        // Check for rule specific message
        if (isset($this->messages[$rule])) {
            return $this->messages[$rule];
        }

        // Get default message
        return $this->getDefaultMessage($rule);
    }

    /**
     * Get the default error message for a rule.
     *
     * @param string $rule
     * @return string
     */
    protected function getDefaultMessage($rule)
    {
        switch ($rule) {
            case 'required':
                return 'The :attribute field is required.';
            case 'email':
                return 'The :attribute must be a valid email address.';
            case 'min':
                return 'The :attribute must be at least :min.';
            case 'max':
                return 'The :attribute may not be greater than :max.';
            case 'numeric':
                return 'The :attribute must be a number.';
            case 'string':
                return 'The :attribute must be a string.';
            case 'confirmed':
                return 'The :attribute confirmation does not match.';
            case 'unique':
                return 'The :attribute has already been taken.';
            case 'in':
                return 'The selected :attribute is invalid.';
            case 'not_in':
                return 'The selected :attribute is invalid.';
            case 'date':
                return 'The :attribute is not a valid date.';
            case 'url':
                return 'The :attribute format is invalid.';
            default:
                return "The :attribute is invalid.";
        }
    }

    /**
     * Replace placeholders in messages.
     *
     * @param string $message
     * @param string $attribute
     * @param string $rule
     * @param array $parameters
     * @return string
     */
    protected function replacePlaceholdersInMessage($message, $attribute, $rule, $parameters)
    {
        $message = str_replace(':attribute', $this->getDisplayableAttribute($attribute), $message);

        if ($rule === 'min') {
            $message = str_replace(':min', $parameters[0], $message);
        }

        if ($rule === 'max') {
            $message = str_replace(':max', $parameters[0], $message);
        }

        if ($rule === 'in') {
            $message = str_replace(':values', implode(', ', $parameters), $message);
        }

        if ($rule === 'not_in') {
            $message = str_replace(':values', implode(', ', $parameters), $message);
        }

        return $message;
    }

    /**
     * Get the displayable name of the attribute.
     *
     * @param string $attribute
     * @return string
     */
    protected function getDisplayableAttribute($attribute)
    {
        return isset($this->attributes[$attribute])
            ? $this->attributes[$attribute]
            : str_replace('_', ' ', $attribute);
    }

    /**
     * Add a custom validation rule.
     *
     * @param string $rule
     * @param \Closure|string $callback
     * @return void
     */
    public static function extend($rule, $callback)
    {
        static::$extensions[$rule] = $callback;
    }

    /**
     * Validate that an attribute is required.
     *
     * @param string $attribute
     * @param mixed $value
     * @param array $parameters
     * @return void
     */
    protected function validateRequired($attribute, $value, $parameters)
    {
        if (is_null($value) || (is_string($value) && trim($value) === '') || (is_array($value) && count($value) === 0)) {
            $this->addError($attribute, 'required');
        }
    }

    /**
     * Validate that an attribute is a valid e-mail address.
     *
     * @param string $attribute
     * @param mixed $value
     * @param array $parameters
     * @return void
     */
    protected function validateEmail($attribute, $value, $parameters)
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->addError($attribute, 'email');
        }
    }

    /**
     * Validate that an attribute is a valid URL.
     *
     * @param string $attribute
     * @param mixed $value
     * @param array $parameters
     * @return void
     */
    protected function validateUrl($attribute, $value, $parameters)
    {
        if (!filter_var($value, FILTER_VALIDATE_URL)) {
            $this->addError($attribute, 'url');
        }
    }

    /**
     * Validate that an attribute has a minimum value.
     *
     * @param string $attribute
     * @param mixed $value
     * @param array $parameters
     * @return void
     */
    protected function validateMin($attribute, $value, $parameters)
    {
        $min = $parameters[0];

        if (is_string($value) && mb_strlen($value) < $min) {
            $this->addError($attribute, 'min', $parameters);
        } elseif (is_numeric($value) && $value < $min) {
            $this->addError($attribute, 'min', $parameters);
        } elseif (is_array($value) && count($value) < $min) {
            $this->addError($attribute, 'min', $parameters);
        }
    }

    /**
     * Validate that an attribute has a maximum value.
     *
     * @param string $attribute
     * @param mixed $value
     * @param array $parameters
     * @return void
     */
    protected function validateMax($attribute, $value, $parameters)
    {
        $max = $parameters[0];

        if (is_string($value) && mb_strlen($value) > $max) {
            $this->addError($attribute, 'max', $parameters);
        } elseif (is_numeric($value) && $value > $max) {
            $this->addError($attribute, 'max', $parameters);
        } elseif (is_array($value) && count($value) > $max) {
            $this->addError($attribute, 'max', $parameters);
        }
    }

    /**
     * Validate that an attribute is a numeric value.
     *
     * @param string $attribute
     * @param mixed $value
     * @param array $parameters
     * @return void
     */
    protected function validateNumeric($attribute, $value, $parameters)
    {
        if (!is_numeric($value)) {
            $this->addError($attribute, 'numeric');
        }
    }

    /**
     * Validate that an attribute is a string.
     *
     * @param string $attribute
     * @param mixed $value
     * @param array $parameters
     * @return void
     */
    protected function validateString($attribute, $value, $parameters)
    {
        if (!is_string($value)) {
            $this->addError($attribute, 'string');
        }
    }

    /**
     * Validate that an attribute has a matching confirmation.
     *
     * @param string $attribute
     * @param mixed $value
     * @param array $parameters
     * @return void
     */
    protected function validateConfirmed($attribute, $value, $parameters)
    {
        $confirmedAttribute = $attribute . '_confirmation';

        if (!isset($this->data[$confirmedAttribute]) || $value !== $this->data[$confirmedAttribute]) {
            $this->addError($attribute, 'confirmed');
        }
    }

    /**
     * Validate that an attribute is a valid date.
     *
     * @param string $attribute
     * @param mixed $value
     * @param array $parameters
     * @return void
     */
    protected function validateDate($attribute, $value, $parameters)
    {
        if (!$value instanceof \DateTime && strtotime($value) === false) {
            $this->addError($attribute, 'date');
        }
    }

    /**
     * Validate that an attribute is in a list of values.
     *
     * @param string $attribute
     * @param mixed $value
     * @param array $parameters
     * @return void
     */
    protected function validateIn($attribute, $value, $parameters)
    {
        if (!in_array($value, $parameters)) {
            $this->addError($attribute, 'in', $parameters);
        }
    }

    /**
     * Validate that an attribute is not in a list of values.
     *
     * @param string $attribute
     * @param mixed $value
     * @param array $parameters
     * @return void
     */
    protected function validateNotIn($attribute, $value, $parameters)
    {
        if (in_array($value, $parameters)) {
            $this->addError($attribute, 'not_in', $parameters);
        }
    }

    /**
     * Get the validated data.
     *
     * @return array
     */
    public function validated()
    {
        if (!$this->validate()) {
            throw new \Exception('The given data failed to pass validation.');
        }

        $validated = [];

        foreach (array_keys($this->rules) as $attribute) {
            if (array_key_exists($attribute, $this->data)) {
                $validated[$attribute] = $this->data[$attribute];
            }
        }

        return $validated;
    }

    /**
     * Get the validation errors.
     *
     * @return array
     */
    public function errors()
    {
        return $this->errors;
    }

    /**
     * Get the first error message for each attribute.
     *
     * @return array
     */
    public function firstErrors()
    {
        $errors = [];

        foreach ($this->errors as $attribute => $messages) {
            $errors[$attribute] = reset($messages);
        }

        return $errors;
    }

    /**
     * Get all error messages as a flat array.
     *
     * @return array
     */
    public function getMessages()
    {
        $messages = [];

        foreach ($this->errors as $attribute => $attributeErrors) {
            $messages = array_merge($messages, $attributeErrors);
        }

        return $messages;
    }

    /**
     * Check if attribute has any error messages.
     *
     * @param string $attribute
     * @return bool
     */
    public function hasError($attribute)
    {
        return isset($this->errors[$attribute]);
    }

    /**
     * Get the first error message for an attribute.
     *
     * @param string $attribute
     * @return string|null
     */
    public function firstError($attribute)
    {
        return $this->hasError($attribute) ? reset($this->errors[$attribute]) : null;
    }
}