<?php

declare(strict_types=1);

namespace PoPCMSSchema\Menus\Facades\RuntimeRegistries;

use PoP\Root\App;
use PoPCMSSchema\Menus\RuntimeRegistries\MenuItemRuntimeRegistryInterface;

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
