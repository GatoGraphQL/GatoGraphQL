<?php

declare(strict_types=1);

namespace PoPSchema\SchemaCommons\ObjectModels;

use PoPSchema\SchemaCommons\ObjectModels\ErrorPayloadInterface;
use PoP\ComponentModel\ObjectModels\AbstractTransientObject;

abstract class AbstractTransientOperationPayload extends AbstractTransientObject
{
    /**
     * @param ErrorPayloadInterface[]|null $errors
     */
     public function __construct(
        public readonly string $status,
        public readonly ?array $errors,
    ) {
        parent::__construct();
    }
}
