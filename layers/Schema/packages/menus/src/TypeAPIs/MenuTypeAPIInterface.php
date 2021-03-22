<?php

declare(strict_types=1);

namespace PoPSchema\Menus\TypeAPIs;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
interface MenuTypeAPIInterface
{
    /**
     * Indicates if the passed object is of type Menu
     *
     * @param [type] $object
     */
    public function isInstanceOfMenuType($object): bool;
}
