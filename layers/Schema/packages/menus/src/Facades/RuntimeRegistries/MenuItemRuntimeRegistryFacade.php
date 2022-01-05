<?php

declare(strict_types=1);

namespace PoPSchema\Menus\Facades\RuntimeRegistries;

use PoP\Engine\App;
use PoPSchema\Menus\RuntimeRegistries\MenuItemRuntimeRegistryInterface;

class MenuItemRuntimeRegistryFacade
{
    public static function getInstance(): MenuItemRuntimeRegistryInterface
    {
        /**
         * @var MenuItemRuntimeRegistryInterface
         */
        $service = App::getContainer()->get(MenuItemRuntimeRegistryInterface::class);
        return $service;
    }
}
