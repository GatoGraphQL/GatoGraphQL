<?php

declare(strict_types=1);

namespace PoPWPSchema\Blocks\ObjectModels;

use stdClass;

final class GeneralBlock extends AbstractBlock
{
    public function __construct(
        string $message,
        public readonly ?string $code = null,
        public readonly ?stdClass $data = null,
    ) {
        parent::__construct($message);
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function getData(): ?stdClass
    {
        return $this->data;
    }
}
