<?php

declare(strict_types=1);

namespace PoPWPSchema\Blocks\ObjectModels;

use PoP\ComponentModel\ObjectModels\AbstractTransientObject;
use stdClass;

abstract class AbstractBlock extends AbstractTransientObject implements BlockInterface
{
    /**
     * Initialize this field lazily
     */
    private ?string $contentSource = null;

    /**
     * @param array<string|null> $innerContent
     * @param BlockInterface[]|null $innerBlocks
     * @param stdClass $blockItem Block data, needed to recreate the contentSource attribute lazily
     */
    public function __construct(
        public readonly string $name,
        public readonly ?stdClass $attributes,
        public readonly ?array $innerBlocks,
        public readonly array $innerContent,
        private stdClass $blockItem,
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
        if ($this->contentSource == null) {
            /**
             * Regenerate the original content source.
             *
             * Please notice that it will not be exactly the same!
             * Because:
             *
             * - the default attributes should not be included,
             *   but they are
             * - attributes stored inside the innerHTML are also
             *   stored within the attributes
             *
             * A better solution is to retrieve the HTML content as is
             * already when parsing the blocks in class
             * `WP_Block_Parser_Block` (but this is currently not supported!)
             *
             * @see wp-includes/class-wp-block-parser.php
             *
             * @todo If `WP_Block_Parser_Block` ever retrieves the original HTML source, then improve this solution
             *
             * @see https://github.com/GatoGraphQL/PoP/issues/2346
             */
            $this->contentSource = serialize_block($this->getSerializeBlockData($this->blockItem));
        }
        return $this->contentSource;
    }

    /**
     * Retrieve the properties that, passed to `serialize_block`,
     * recreates the Block HTML.
     *
     * @return array<string,mixed>
     */
    protected function getSerializeBlockData(stdClass $blockItem): array
    {
        /** @var string */
        $name = $blockItem->name;
        /** @var stdClass|null */
        $attributes = $blockItem->attributes ?? null;
        /** @var array<string|null> */
        $innerContent = $blockItem->innerContent;

        $serializeBlockData = [
            'blockName' => $name,
            'attrs' => $attributes !== null ? (array) $attributes : [],
            'innerContent' => $innerContent,
        ];

        if (isset($blockItem->innerBlocks)) {
            /** @var array<stdClass> */
            $blockInnerBlocks = $blockItem->innerBlocks;
            $serializeBlockData['innerBlocks'] = array_map(
                $this->getSerializeBlockData(...),
                $blockInnerBlocks
            );
        }
        return $serializeBlockData;
    }
}
