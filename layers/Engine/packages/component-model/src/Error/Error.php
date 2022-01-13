<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Error;

use PoP\Root\Facades\Translation\TranslationAPIFacade;

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

    /**
     * @param Error[]|null $nestedErrors
     */
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

    public function getMessageOrCode(): string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        return $this->message ?? sprintf(
            $translationAPI->__('Error code: \'%s\'', 'component-model'),
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
     * @param array<string, mixed> $data
     */
    public function setData(array $data): void
    {
        $this->data = $data;
    }

    public function addData(string $key, mixed $value): void
    {
        $this->data[$key] = $value;
    }

    /**
     * @return Error[]
     */
    public function getNestedErrors(): array
    {
        return $this->nestedErrors;
    }
}
