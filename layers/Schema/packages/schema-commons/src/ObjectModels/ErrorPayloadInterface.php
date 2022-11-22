<?php

declare(strict_types=1);

namespace PoPSchema\SchemaCommons\ObjectModels;

use PoP\ComponentModel\ObjectModels\TransientObjectInterface;
use stdClass;

interface ErrorPayloadInterface extends TransientObjectInterface
{
    public function getMessage(): string;

    public function getCode(): string|int|null;

    public function getData(): ?stdClass;
}
