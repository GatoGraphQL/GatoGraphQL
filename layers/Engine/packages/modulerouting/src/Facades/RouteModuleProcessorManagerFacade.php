<?php

declare(strict_types=1);

namespace PoP\ModuleRouting\Facades;

use PoP\Root\App;
use PoP\ModuleRouting\RouteModuleProcessorManagerInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class RouteModuleProcessorManagerFacade
{
    public static function getInstance(): RouteModuleProcessorManagerInterface
    {
        /**
         * @var RouteModuleProcessorManagerInterface
         */
        $service = App::getContainerBuilderFactory()->getInstance()->get(RouteModuleProcessorManagerInterface::class);
        return $service;
    }
}
