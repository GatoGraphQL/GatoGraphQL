<?php

declare(strict_types=1);

namespace PoPSchema\Menus\Facades\RuntimeRegistries;

use PoP\Engine\App;
use PoP\Root\Container\ContainerBuilderFactory;
use PoPSchema\Menus\RuntimeRegistries\MenuItemRuntimeRegistryInterface;

class MenuItemRuntimeRegistryFacade
{
    public static function getInstance(): MenuItemRuntimeRegistryInterface
    {
        /**
         * @var MenuItemRuntimeRegistryInterface
         */
        $service = App::getContainerBuilderFactory()->getInstance()->get(MenuItemRuntimeRegistryInterface::class);
        return $service;
    }
}
