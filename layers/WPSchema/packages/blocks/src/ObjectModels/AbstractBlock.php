<?php

declare(strict_types=1);

namespace PoPWPSchema\Blocks\ObjectModels;

use PoP\ComponentModel\ObjectModels\AbstractTransientObject;
use stdClass;

abstract class AbstractBlock extends AbstractTransientObject implements BlockInterface
{
    /**
     * @param BlockInterface[]|null $innerBlocks
     */
    public function __construct(
        public string $name,
        public readonly ?stdClass $attributes = null,
        public readonly ?array $innerBlocks = null,
    ) {
        parent::__construct();
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getAttributes(): ?stdClass
    {
        return $this->attributes;
    }

    /**
     * @return BlockInterface[]|null
     */
    public function getInnerBlocks(): ?array
    {
        return $this->innerBlocks;
    }
}
