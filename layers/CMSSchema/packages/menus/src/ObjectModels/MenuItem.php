<?php

declare(strict_types=1);

namespace PoPCMSSchema\Menus\ObjectModels;

/**
 * Make properties public so they can be accessed directly
 */
class MenuItem
{
    /**
     * @param string[] $cssClasses
     */
    public function __construct(
        public readonly string|int $id,
        public readonly string $itemType,
        public readonly string $objectType,
        public readonly string|int $objectID,
        public readonly string|int|null $parentID,
        public readonly string $label,
        public readonly string $rawLabel,
        public readonly string $titleAttribute,
        public readonly string $url,
        public readonly string $description,
        public readonly array $cssClasses,
        public readonly string $target,
        public readonly string $linkRelationship,
    ) {
    }
}
