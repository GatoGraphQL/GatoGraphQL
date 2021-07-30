<?php

declare(strict_types=1);

namespace PoPSchema\Menus\ObjectModels;

/**
 * Make properties public so they can be accessed directly
 */
class MenuItem
{
    public function __construct(
        public string | int $id,
        public string | int $objectID,
        public string | int | null $parentID,
        public string $title,
        public string $url,
        public string $description,
        /** @var string[] */
        public array $classes,
        public string $target,
    ) {
    }
}
