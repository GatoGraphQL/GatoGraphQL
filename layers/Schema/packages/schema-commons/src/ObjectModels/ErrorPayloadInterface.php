<?php

declare(strict_types=1);

namespace PoPSchema\SchemaCommons\ObjectModels;

use PoP\ComponentModel\ObjectModels\TransientObjectInterface;

interface ErrorPayloadInterface extends TransientObjectInterface
{
    public function getMessage(): string;
}
