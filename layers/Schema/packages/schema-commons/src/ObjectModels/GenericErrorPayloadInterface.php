<?php

declare(strict_types=1);

namespace PoPSchema\SchemaCommons\ObjectModels;

use stdClass;

interface GenericErrorPayloadInterface extends ErrorPayloadInterface
{
    public function getCode(): ?string;

    public function getData(): ?stdClass;
}
