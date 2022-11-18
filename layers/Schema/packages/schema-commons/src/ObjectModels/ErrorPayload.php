<?php

declare(strict_types=1);

namespace PoPSchema\SchemaCommons\ObjectModels;

use PoP\ComponentModel\ObjectModels\AbstractTransientObject;
use stdClass;

final class ErrorPayload extends AbstractTransientObject
{
    public function __construct(
        public readonly string $message,
        public readonly string|int|null $code = null,
        public readonly ?stdClass $data = null,
    ) {
        parent::__construct();
    }
}
