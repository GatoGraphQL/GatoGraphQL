<?php

declare(strict_types=1);

namespace PoPSchema\MenusWP\TypeAPIs;

use PoPSchema\Menus\TypeAPIs\MenuItemTypeAPIInterface;
use WP_Post;

abstract class MenuItemTypeAPI implements MenuItemTypeAPIInterface
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
