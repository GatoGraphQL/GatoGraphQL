<?php

declare(strict_types=1);

namespace PoPSchema\Menus\ObjectModels;

/**
 * Matching the properties retrieved via `wp_get_nav_menu_items`.
 * Make them public so they can be accessed directly.
 */
class MenuItem
{
    public function __construct(
        public string | int $ID,
        public string | int $object_id,
        public string | int | null $menu_item_parent,
        public string $title,
        public string $url,
        public string $description,
        /** @var string[] */
        public array $classes,
        public string $target,
    ) {
    }
}
