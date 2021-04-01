<?php

declare(strict_types=1);

namespace PoPSchema\Menus\Facades;

use PoPSchema\Menus\TypeAPIs\MenuItemTypeAPIInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class MenuItemTypeAPIFacade
{
    public static function getInstance(): MenuItemTypeAPIInterface
    {
        /**
         * @var MenuItemTypeAPIInterface
         */
        $service = ContainerBuilderFactory::getInstance()->get(MenuItemTypeAPIInterface::class);
        return $service;
    }
}
