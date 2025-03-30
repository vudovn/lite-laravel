<?php

namespace Framework;

class Validation
{
    protected $data = [];
    protected $rules = [];
    protected $errors = [];
    protected $messages = [];
    protected $customMessages = [];

    /**
     * Create a new validation instance
     * 
     * @param array $data
     * @param array $rules
     * @param array $messages
     */
    public function __construct(array $data = [], array $rules = [], array $messages = [])
    {
        $this->data = $data;
        $this->rules = $rules;
        $this->customMessages = $messages;
    }

    /**
     * Set the data to validate
     * 
     * @param array $data
     * @return $this
     */
    public function setData(array $data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * Set the validation rules
     * 
     * @param array $rules
     * @return $this
     */
    public function setRules(array $rules)
    {
        $this->rules = $rules;
        return $this;
    }

    /**
     * Set custom error messages
     * 
     * @param array $messages
     * @return $this
     */
    public function setMessages(array $messages)
    {
        $this->customMessages = $messages;
        return $this;
    }

    /**
     * Validate the data against the rules
     * 
     * @return bool
     */
    public function validate()
    {
        $this->errors = [];

        foreach ($this->rules as $field => $ruleString) {
            $rules = explode('|', $ruleString);
            $value = $this->getValue($field);

            foreach ($rules as $rule) {
                $parameters = [];

                if (strpos($rule, ':') !== false) {
                    list($rule, $parameterString) = explode(':', $rule, 2);
                    $parameters = explode(',', $parameterString);
                }

                $method = 'validate' . ucfirst($rule);

                if (method_exists($this, $method)) {
                    $valid = $this->$method($field, $value, $parameters);

                    if (!$valid) {
                        $this->addError($field, $rule, $parameters);
                    }
                }
            }
        }

        return empty($this->errors);
    }

    /**
     * Get a specific value from the data
     * 
     * @param string $field
     * @return mixed
     */
    protected function getValue($field)
    {
        return $this->data[$field] ?? null;
    }

    /**
     * Add an error for a field
     * 
     * @param string $field
     * @param string $rule
     * @param array $parameters
     * @return void
     */
    protected function addError($field, $rule, $parameters = [])
    {
        // Check for custom message
        $message = $this->customMessages[$field . '.' . $rule] ??
            $this->customMessages[$rule] ??
            $this->getDefaultMessage($field, $rule, $parameters);

        // Replace placeholders in message
        $message = str_replace(':attribute', $field, $message);

        foreach ($parameters as $i => $parameter) {
            $message = str_replace(':param' . ($i + 1), $parameter, $message);
        }

        $this->errors[$field] = $message;
    }

    /**
     * Get the default error message for a rule
     * 
     * @param string $field
     * @param string $rule
     * @param array $parameters
     * @return string
     */
    protected function getDefaultMessage($field, $rule, $parameters)
    {
        $messages = [
            'required' => 'The :attribute field is required.',
            'email' => 'The :attribute must be a valid email address.',
            'min' => 'The :attribute must be at least :param1 characters.',
            'max' => 'The :attribute may not be greater than :param1 characters.',
            'between' => 'The :attribute must be between :param1 and :param2 characters.',
            'same' => 'The :attribute and :param1 must match.',
            'unique' => 'The :attribute has already been taken.',
            'numeric' => 'The :attribute must be a number.',
            'integer' => 'The :attribute must be an integer.',
            'url' => 'The :attribute must be a valid URL.',
            'confirmed' => 'The :attribute confirmation does not match.',
            'date' => 'The :attribute is not a valid date.',
            'alpha' => 'The :attribute may only contain letters.',
            'alpha_num' => 'The :attribute may only contain letters and numbers.',
            'alpha_dash' => 'The :attribute may only contain letters, numbers, dashes and underscores.',
        ];

        return $messages[$rule] ?? "The {$field} field is invalid.";
    }

    /**
     * Get all validation errors
     * 
     * @return array
     */
    public function errors()
    {
        return $this->errors;
    }

    /**
     * Get a specific error
     * 
     * @param string $field
     * @return string|null
     */
    public function error($field)
    {
        return $this->errors[$field] ?? null;
    }

    /**
     * Check if validation failed
     * 
     * @return bool
     */
    public function fails()
    {
        return !empty($this->errors);
    }

    /**
     * Check if validation passed
     * 
     * @return bool
     */
    public function passes()
    {
        return empty($this->errors);
    }

    /**
     * Get validated data
     * 
     * @return array
     */
    public function validated()
    {
        $validated = [];

        foreach ($this->rules as $field => $rule) {
            if (isset($this->data[$field]) && !isset($this->errors[$field])) {
                $validated[$field] = $this->data[$field];
            }
        }

        return $validated;
    }

    // Validation rules methods

    /**
     * Validate that a field is required
     * 
     * @param string $field
     * @param mixed $value
     * @param array $parameters
     * @return bool
     */
    protected function validateRequired($field, $value, $parameters)
    {
        if (is_null($value)) {
            return false;
        } elseif (is_string($value) && trim($value) === '') {
            return false;
        } elseif (is_array($value) && count($value) < 1) {
            return false;
        }

        return true;
    }

    /**
     * Validate that a field is a valid email
     * 
     * @param string $field
     * @param mixed $value
     * @param array $parameters
     * @return bool
     */
    protected function validateEmail($field, $value, $parameters)
    {
        if (empty($value)) {
            return true;
        }

        return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * Validate that a field has a minimum length
     * 
     * @param string $field
     * @param mixed $value
     * @param array $parameters
     * @return bool
     */
    protected function validateMin($field, $value, $parameters)
    {
        if (empty($value)) {
            return true;
        }

        $length = $parameters[0] ?? 0;

        if (is_numeric($value)) {
            return $value >= $length;
        }

        return mb_strlen($value) >= $length;
    }

    /**
     * Validate that a field has a maximum length
     * 
     * @param string $field
     * @param mixed $value
     * @param array $parameters
     * @return bool
     */
    protected function validateMax($field, $value, $parameters)
    {
        if (empty($value)) {
            return true;
        }

        $length = $parameters[0] ?? 0;

        if (is_numeric($value)) {
            return $value <= $length;
        }

        return mb_strlen($value) <= $length;
    }

    /**
     * Validate that a field is unique in the database
     * 
     * @param string $field
     * @param mixed $value
     * @param array $parameters
     * @return bool
     */
    protected function validateUnique($field, $value, $parameters)
    {
        if (empty($value)) {
            return true;
        }

        $table = $parameters[0] ?? null;
        $column = $parameters[1] ?? $field;
        $exceptId = $parameters[2] ?? null;

        if (!$table) {
            return false;
        }

        $connection = app('db')->connection();

        $sql = "SELECT COUNT(*) FROM {$table} WHERE {$column} = ?";
        $params = [$value];

        // Add exception for updates
        if ($exceptId) {
            $idColumn = $parameters[3] ?? 'id';
            $sql .= " AND {$idColumn} != ?";
            $params[] = $exceptId;
        }

        $statement = $connection->prepare($sql);
        $statement->execute($params);
        $count = (int) $statement->fetchColumn();

        return $count === 0;
    }

    /**
     * Validate that a field matches another field
     * 
     * @param string $field
     * @param mixed $value
     * @param array $parameters
     * @return bool
     */
    protected function validateSame($field, $value, $parameters)
    {
        $otherField = $parameters[0] ?? null;

        if (!$otherField) {
            return false;
        }

        $otherValue = $this->data[$otherField] ?? null;

        return $value === $otherValue;
    }

    /**
     * Validate confirmation field
     * 
     * @param string $field
     * @param mixed $value
     * @param array $parameters
     * @return bool
     */
    protected function validateConfirmed($field, $value, $parameters)
    {
        return $this->validateSame($field, $value, [$field . '_confirmation']);
    }

    /**
     * Validate that a field is numeric
     * 
     * @param string $field
     * @param mixed $value
     * @param array $parameters
     * @return bool
     */
    protected function validateNumeric($field, $value, $parameters)
    {
        if (empty($value)) {
            return true;
        }

        return is_numeric($value);
    }

    /**
     * Validate that a field is an integer
     * 
     * @param string $field
     * @param mixed $value
     * @param array $parameters
     * @return bool
     */
    protected function validateInteger($field, $value, $parameters)
    {
        if (empty($value)) {
            return true;
        }

        return filter_var($value, FILTER_VALIDATE_INT) !== false;
    }

    /**
     * Validate that a field is a valid URL
     * 
     * @param string $field
     * @param mixed $value
     * @param array $parameters
     * @return bool
     */
    protected function validateUrl($field, $value, $parameters)
    {
        if (empty($value)) {
            return true;
        }

        return filter_var($value, FILTER_VALIDATE_URL) !== false;
    }

    /**
     * Validate that a field only contains alphabetic characters
     * 
     * @param string $field
     * @param mixed $value
     * @param array $parameters
     * @return bool
     */
    protected function validateAlpha($field, $value, $parameters)
    {
        if (empty($value)) {
            return true;
        }

        return preg_match('/^[\pL\pM]+$/u', $value);
    }

    /**
     * Validate that a field only contains alpha-numeric characters
     * 
     * @param string $field
     * @param mixed $value
     * @param array $parameters
     * @return bool
     */
    protected function validateAlphaNum($field, $value, $parameters)
    {
        if (empty($value)) {
            return true;
        }

        return preg_match('/^[\pL\pM\pN]+$/u', $value);
    }

    /**
     * Validate that a field only contains alpha-numeric characters, dashes, and underscores
     * 
     * @param string $field
     * @param mixed $value
     * @param array $parameters
     * @return bool
     */
    protected function validateAlphaDash($field, $value, $parameters)
    {
        if (empty($value)) {
            return true;
        }

        return preg_match('/^[\pL\pM\pN_-]+$/u', $value);
    }

    /**
     * Validate that a field is a valid date
     * 
     * @param string $field
     * @param mixed $value
     * @param array $parameters
     * @return bool
     */
    protected function validateDate($field, $value, $parameters)
    {
        if (empty($value)) {
            return true;
        }

        if ($value instanceof \DateTime) {
            return true;
        }

        if (strtotime($value) === false) {
            return false;
        }

        $date = date_parse($value);

        return checkdate($date['month'], $date['day'], $date['year']);
    }
}