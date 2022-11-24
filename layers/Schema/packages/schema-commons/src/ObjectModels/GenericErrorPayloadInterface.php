<?php

declare(strict_types=1);

namespace PoPSchema\SchemaCommons\ObjectModels;

use stdClass;

interface GenericErrorPayloadInterface extends ErrorPayloadInterface
{
    public function getCode(): string|int|null;

    public function getData(): ?stdClass;
}
