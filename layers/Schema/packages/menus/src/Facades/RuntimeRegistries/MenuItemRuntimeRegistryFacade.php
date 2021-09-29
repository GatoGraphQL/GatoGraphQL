<?php

declare(strict_types=1);

namespace PoPSchema\Menus\Facades\RuntimeRegistries;

use PoP\Root\Container\ContainerBuilderFactory;
use PoPSchema\Menus\RuntimeRegistries\MenuItemRuntimeRegistryInterface;

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
