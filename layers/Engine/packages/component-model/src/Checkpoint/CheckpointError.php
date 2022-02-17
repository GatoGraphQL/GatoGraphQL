<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Checkpoint;

final class CheckpointError implements CheckpointErrorInterface
{
    public function __construct(
        protected string $message,
        protected string $code,
    ) {
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getCode(): string
    {
        return $this->code;
    }
}
