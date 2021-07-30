<?php

declare(strict_types=1);

namespace PoPSchema\Menus\Facades\RuntimeRegistries;

use PoPSchema\Menus\RuntimeRegistries\MenuItemRuntimeRegistryInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class MenuItemRuntimeRegistryFacade
{
    public static function getInstance(): MenuItemRuntimeRegistryInterface
    {
        /**
         * @var MenuItemRuntimeRegistryInterface
         */
        $service = ContainerBuilderFactory::getInstance()->get(MenuItemRuntimeRegistryInterface::class);
        return $service;
    }
}
