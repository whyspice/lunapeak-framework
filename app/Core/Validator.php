<?php
/*
# Welcome to WHYSPICE OS v0.0.1 (GNU/Linux 3.13.0.129-generic x86_64)

root@localhost:~ bash ./whyspice-work.sh
> Executing...

         _       ____  ____  _______ ____  ________________
        | |     / / / / /\ \/ / ___// __ \/  _/ ____/ ____/
        | | /| / / /_/ /  \  /\__ \/ /_/ // // /   / __/
        | |/ |/ / __  /   / /___/ / ____// // /___/ /___
        |__/|__/_/ /_/   /_//____/_/   /___/\____/_____/

                            Web Dev.
                WHYSPICE © 2025 # whyspice.su

> Disconnecting.

# Connection closed by remote host.
*/
namespace App\Core;

class Validator
{
    protected array $data;
    protected array $rules;
    protected array $messages = [];
    protected array $errors = [];

    public function __construct(array $data, array $rules, array $messages = [])
    {
        $this->data = $data;
        $this->rules = $rules;
        $this->messages = $messages;
        $this->validate();
    }

    public static function make(array $data, array $rules, array $messages = []): self
    {
        return new self($data, $rules, $messages);
    }

    public function validate(): void
    {
        foreach ($this->rules as $field => $ruleString) {
            $rules = explode('|', $ruleString);
            $value = $this->data[$field] ?? null;

            foreach ($rules as $rule) {
                $this->applyRule($field, $value, $rule);
            }
        }

        if (!empty($this->errors)) {
            throw new ValidationException($this->errors);
        }
    }

    protected function applyRule(string $field, mixed $value, string $rule): void
    {
        [$ruleName, $param] = array_pad(explode(':', $rule, 2), 2, null);

        switch ($ruleName) {
            case 'required':
                if (is_null($value) || $value === '') {
                    $this->addError($field, 'required', "The {$field} field is required.");
                }
                break;
            case 'email':
                if ($value && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $this->addError($field, 'email', "The {$field} must be a valid email address.");
                }
                break;
            case 'min':
                if ($param && $value && strlen($value) < (int)$param) {
                    $this->addError($field, 'min', "The {$field} must be at least {$param} characters.");
                }
                break;
            case 'max':
                if ($param && $value && strlen($value) > (int)$param) {
                    $this->addError($field, 'max', "The {$field} may not be greater than {$param} characters.");
                }
                break;
            case 'file':
                if ($value instanceof UploadedFile) {
                    if (!$value->isValid()) {
                        $this->addError($field, 'file', "The {$field} must be a valid uploaded file.");
                    }
                } elseif (!isset($_FILES[$field]) || $_FILES[$field]['error'] === UPLOAD_ERR_NO_FILE) {
                    $this->addError($field, 'file', "The {$field} must be a file.");
                }
                break;
            case 'mimes':
                if ($param && $value instanceof UploadedFile && $value->isValid()) {
                    $allowed = explode(',', $param);
                    $mime = $value->getMimeType();
                    if (!in_array(strtolower(pathinfo($mime, PATHINFO_EXTENSION)), $allowed)) {
                        $this->addError($field, 'mimes', "The {$field} must be a file of type: {$param}.");
                    }
                }
                break;
        }
    }

    protected function addError(string $field, string $rule, string $defaultMessage): void
    {
        $messageKey = "{$field}.{$rule}";
        $this->errors[$field] = $this->messages[$messageKey] ?? $defaultMessage;
    }

    public function errors(): array
    {
        return $this->errors;
    }
}

class ValidationException extends \Exception
{
    protected array $errors;

    public function __construct(array $errors)
    {
        $this->errors = $errors;
        parent::__construct('Validation failed');
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}