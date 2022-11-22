<?php

declare(strict_types=1);

namespace PoPSchema\SchemaCommons\ObjectModels;

use PoP\ComponentModel\ObjectModels\AbstractTransientObject;
use stdClass;

abstract class AbstractErrorPayload extends AbstractTransientObject implements ErrorPayloadInterface
{
    public function __construct(
        public readonly string $message,
        public readonly string|int|null $code = null,
        public readonly ?stdClass $data = null,
    ) {
        parent::__construct();
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getCode(): string|int|null
    {
        return $this->code;
    }

    public function getData(): ?stdClass
    {
        return $this->data;
    }
}
