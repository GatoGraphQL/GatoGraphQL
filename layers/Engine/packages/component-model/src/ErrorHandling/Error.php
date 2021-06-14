<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ErrorHandling;

use PoP\Translation\Facades\TranslationAPIFacade;

class Error
{
    protected string $code;
    protected ?string $message;
    /**
     * @var array<string, mixed>
     */
    protected array $data;
    /**
     * @var Error[]
     */
    protected array $nestedErrors;
    
    public function __construct(
        string $code,
        ?string $message = null,
        ?array $data = null,
        ?array $nestedErrors = null
    ) {
        $this->code = $code;
        $this->message = $message;
        $this->data = $data ?? [];
        $this->nestedErrors = $nestedErrors ?? [];
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function getMessageWithCode(): string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        return $this->message !== null ?
            sprintf(
                $translationAPI->__('[%1$s] %2$s', 'component-model'),
                $this->code,
                $this->message
            )
            : sprintf(
                $translationAPI->__('Error code: %s', 'component-model'),
                $this->code
            );
    }

    /**
     * @return array<string, mixed>
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @return Error[]
     */
    public function getNestedErrors(): array
    {
        return $this->nestedErrors;
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
