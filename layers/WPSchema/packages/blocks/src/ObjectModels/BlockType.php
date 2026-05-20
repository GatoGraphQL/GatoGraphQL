<?php

declare(strict_types=1);

namespace PoPWPSchema\Blocks\ObjectModels;

use PoP\ComponentModel\ObjectModels\AbstractTransientObject;
use WP_Block_Type;

/**
 * Wraps a WP_Block_Type instance from `WP_Block_Type_Registry`,
 * exposing its registered properties to the GraphQL schema.
 *
 * Block types have no DB ID, so they're handled as Transient Objects,
 * using the (globally unique) block name as ID. The instance is
 * auto-registered in the Object Dictionary upon construction
 * (see AbstractTransientObject).
 */
class BlockType extends AbstractTransientObject
{
    public function __construct(
        public readonly WP_Block_Type $blockType,
    ) {
        parent::__construct($blockType->name);
    }

    /**
     * Block name (e.g. "core/paragraph").
     */
    public function getName(): string
    {
        return $this->blockType->name;
    }

    public function hasRenderCallback(): bool
    {
        /**
         * WP_Block_Type::$render_callback is stub-typed as non-nullable callable,
         * but it can actually be null for blocks without a dynamic renderer.
         * @phpstan-ignore function.alreadyNarrowedType
         */
        return is_callable($this->blockType->render_callback);
    }

    /**
     * @return array<string,mixed>
     */
    public function getSupports(): array
    {
        $supports = $this->blockType->supports;
        return is_array($supports) ? $supports : [];
    }

    /**
     * @return array<string,array<string,mixed>>
     */
    public function getAttributes(): array
    {
        $attributes = $this->blockType->attributes;
        return is_array($attributes) ? $attributes : [];
    }
}
