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
        public readonly string $name,
        public readonly ?stdClass $attributes,
        public readonly ?array $innerBlocks,
        public readonly string $innerHTML,
        public readonly string $contentSource,
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

    public function getInnerHTML(): string
    {
        return $this->innerHTML;
    }

    public function getContentSource(): string
    {
        return $this->contentSource;
    }
}
