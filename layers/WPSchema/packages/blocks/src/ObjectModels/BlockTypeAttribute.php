<?php

declare(strict_types=1);

namespace PoPWPSchema\Blocks\ObjectModels;

use PoP\ComponentModel\ObjectModels\AbstractTransientObject;

/**
 * A single registered attribute of a block type, as defined in
 * `block.json` (or via `register_block_type()` in PHP).
 *
 * Attributes have no DB ID, so they're handled as Transient Objects:
 * the parent auto-generates a counter-based ID and the instance is
 * auto-registered in the Object Dictionary upon construction
 * (see AbstractTransientObject).
 */
class BlockTypeAttribute extends AbstractTransientObject
{
    /**
     * @param array<string,mixed> $schema The attribute's JSON-Schema-like definition
     */
    public function __construct(
        public readonly BlockType $blockType,
        public readonly string $attributeName,
        public readonly array $schema,
    ) {
        parent::__construct();
    }

    public function getBlockType(): BlockType
    {
        return $this->blockType;
    }

    public function getBlockTypeName(): string
    {
        return $this->blockType->getName();
    }

    public function getAttributeName(): string
    {
        return $this->attributeName;
    }

    /**
     * @return array<string,mixed>
     */
    public function getSchema(): array
    {
        return $this->schema;
    }

    /**
     * Returns the attribute's "type" property as declared in the schema.
     * It can be a single string ("string", "integer", "number", "boolean",
     * "array", "object", "null") or an array of strings (union types).
     *
     * @return string|string[]|null
     */
    public function getFieldType(): string|array|null
    {
        return $this->schema['type'] ?? null;
    }
}
