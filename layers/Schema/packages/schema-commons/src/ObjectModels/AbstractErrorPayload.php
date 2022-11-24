<?php

declare(strict_types=1);

namespace PoPSchema\SchemaCommons\ObjectModels;

use PoP\ComponentModel\ObjectModels\AbstractTransientObject;

abstract class AbstractErrorPayload extends AbstractTransientObject implements ErrorPayloadInterface
{
    public function __construct(
        public readonly string $message,
    ) {
        parent::__construct();
    }

    public function getMessage(): string
    {
        return $this->message;
    }
}
