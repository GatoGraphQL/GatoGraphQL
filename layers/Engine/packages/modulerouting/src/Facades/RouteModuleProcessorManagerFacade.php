<?php

declare(strict_types=1);

namespace PoP\ModuleRouting\Facades;

use PoP\ModuleRouting\RouteModuleProcessorManagerInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class RouteModuleProcessorManagerFacade
{
    public static function getInstance(): RouteModuleProcessorManagerInterface
    {
        /**
         * @var RouteModuleProcessorManagerInterface
         */
        $service = ContainerBuilderFactory::getInstance()->get(RouteModuleProcessorManagerInterface::class);
        return $service;
    }
}
