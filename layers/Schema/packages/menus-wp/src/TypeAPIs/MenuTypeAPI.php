<?php

declare(strict_types=1);

namespace PoPSchema\MenusWP\TypeAPIs;

use WP_Menu;
use PoPSchema\Menus\TypeAPIs\MenuTypeAPIInterface;
/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
class MenuTypeAPI implements MenuTypeAPIInterface
{
    /**
     * Indicates if the passed object is of type Menu
     *
     * @param [type] $object
     * @return boolean
     */
    public function isInstanceOfMenuType($object): bool
    {
        return $object instanceof WP_Menu;
    }
}
