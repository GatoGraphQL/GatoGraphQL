<?php

declare(strict_types=1);

namespace PoPCMSSchema\Menus\ObjectModels;

/**
 * Make properties public so they can be accessed directly
 */
class MenuItem
{
    public function __construct(
        public readonly string | int $id,
        public readonly string | int $objectID,
        public readonly string | int | null $parentID,
        public readonly string $label,
        public readonly string $title,
        public readonly string $url,
        public readonly string $description,
        /** @var string[] */
        public readonly array $classes,
        public readonly string $target,
        public readonly string $linkRelationship,
    ) {
    }
}
