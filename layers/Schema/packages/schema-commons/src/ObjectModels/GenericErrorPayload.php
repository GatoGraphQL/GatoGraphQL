<?php

declare(strict_types=1);

namespace PoPSchema\SchemaCommons\ObjectModels;

use stdClass;

final class GenericErrorPayload extends AbstractErrorPayload implements GenericErrorPayloadInterface
{
    public function __construct(
        string $message,
        public readonly ?string $code = null,
        public readonly ?stdClass $data = null,
    ) {
        parent::__construct($message);
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function getData(): ?stdClass
    {
        return $this->data;
    }
}
