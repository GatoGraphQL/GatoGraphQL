<?php

declare(strict_types=1);

namespace PoPWPSchema\Blocks\ObjectModels;

use PoP\ComponentModel\ObjectModels\AbstractTransientObject;

abstract class AbstractBlock extends AbstractTransientObject implements BlockInterface
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
