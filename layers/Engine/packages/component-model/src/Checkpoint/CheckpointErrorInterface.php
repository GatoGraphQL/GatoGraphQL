<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Checkpoint;

interface CheckpointErrorInterface
{
    public function getMessage(): string;
    public function getCode(): string;
}
