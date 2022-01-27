<?php

declare(strict_types=1);

namespace PoPCMSSchema\MenusWP\TypeAPIs;

use WP_Post;

abstract class MenuItemTypeAPI
{
    /**
     * MenuItem is the CPT 'nav_menu_item'
     * @see https://developer.wordpress.org/reference/functions/wp_get_nav_menu_items/#source
     */
    public function getMenuItem(string | int $id): ?object
    {
        /** @var WP_Post|null */
        return get_post($id, ARRAY_A);
    }
}
