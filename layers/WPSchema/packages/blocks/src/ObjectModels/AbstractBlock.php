<?php

declare(strict_types=1);

namespace PoPWPSchema\Blocks\ObjectModels;

use PoP\ComponentModel\ObjectModels\AbstractTransientObject;
use stdClass;

abstract class AbstractBlock extends AbstractTransientObject implements BlockInterface
{
    /**
     * @param array<string|null> $innerContent
     * @param BlockInterface[]|null $innerBlocks
     */
    public function __construct(
        public readonly string $name,
        public readonly ?stdClass $attributes,
        public readonly ?array $innerBlocks,
        public readonly array $innerContent,
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

    /**
     * @return array<string|null>
     */
    public function getInnerContent(): array
    {
        return $this->innerContent;
    }

    public function getContentSource(): string
    {
        return $this->contentSource;
    }
}
