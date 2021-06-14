<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ErrorHandling;

class Error
{
    public function __construct(
        protected string $code,
        protected ?string $message = null,
        /**
         * @var array<string, mixed>|null
         */
        protected ?array $data = null,
        /**
         * @var Error[]
         */
        protected ?array $nestedErrors = null
    ) {}

    /**
     * @return string[]
     */
    public function getErrorCodes(): array
    {
        return array_keys($this->errors);
    }

    public function getErrorCode()
    {
        if ($codes = $this->getErrorCodes()) {
            return $codes[0];
        }

        return null;
    }

    public function getErrorMessages($code = null)
    {
        if ($code) {
            return $this->errors[$code] ?? [];
        }

        // Return all messages if no code specified.
        return array_reduce($this->errors, 'array_merge', array());
    }

    public function getErrorMessage($code = null)
    {
        if (!$code) {
            $code = $this->getErrorCode();
        }
        $messages = $this->getErrorMessages($code);
        return $messages[0] ?? '';
    }

    public function getErrorData($code = null)
    {
        if (!$code) {
            $code = $this->getErrorCode();
        }

        return $this->error_data[$code];
    }

    public function add($code, $message, $data = null)
    {
        $this->errors[$code][] = $message;
        if ($data) {
            $this->error_data[$code] = $data;
        }
    }

    public function addData($data, $code = null)
    {
        if (!$code) {
            $code = $this->getErrorCode();
        }

        $this->error_data[$code] = $data;
    }

    public function remove($code)
    {
        unset($this->errors[$code]);
        unset($this->error_data[$code]);
    }
}
