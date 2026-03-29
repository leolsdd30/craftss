<?php
namespace App\Services;

class Validator
{
    protected array $errors = [];

    /**
     * @param array $data Data to validate (e.g., $_POST)
     * @param array $rules Rules array (e.g., ['title' => 'required|min:5|max:255'])
     */
    public function validate(array $data, array $rules): bool
    {
        $this->errors = [];
        
        foreach ($rules as $field => $ruleString) {
            $value = $data[$field] ?? null;
            $fieldRules = explode('|', $ruleString);

            foreach ($fieldRules as $rule) {
                // Determine base rule strategy and extract parameters (e.g., min:5)
                $param = null;
                $ruleName = $rule;
                if (strpos($rule, ':') !== false) {
                    list($ruleName, $param) = explode(':', $rule, 2);
                }

                $valueStr = trim((string)$value);

                switch ($ruleName) {
                    case 'required':
                        if ($value === null || $valueStr === '') {
                            $this->addError($field, "The " . ucfirst($field) . " field is required.");
                        }
                        break;
                    case 'min':
                        if ($valueStr !== '' && strlen($valueStr) < (int)$param) {
                            $this->addError($field, "The " . ucfirst($field) . " must be at least {$param} characters.");
                        }
                        break;
                    case 'max':
                        if ($valueStr !== '' && strlen($valueStr) > (int)$param) {
                            $this->addError($field, "The " . ucfirst($field) . " must not exceed {$param} characters.");
                        }
                        break;
                    case 'email':
                        if ($valueStr !== '' && !filter_var($valueStr, FILTER_VALIDATE_EMAIL)) {
                            $this->addError($field, "The " . ucfirst($field) . " must be a valid email address.");
                        }
                        break;
                    case 'numeric':
                        if ($valueStr !== '' && !is_numeric($valueStr)) {
                            $this->addError($field, "The " . ucfirst($field) . " must be a numeric value.");
                        }
                        break;
                }
            }
        }

        return empty($this->errors);
    }

    protected function addError(string $field, string $message): void
    {
        if (!isset($this->errors[$field])) {
            $this->errors[$field] = $message;
        }
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function getFirstError(): ?string
    {
        if (empty($this->errors)) {
            return null;
        }
        return reset($this->errors);
    }
}
