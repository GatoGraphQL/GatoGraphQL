<?php

declare(strict_types=1);

namespace PoP\ComponentRouting\Facades;

use PoP\Root\App;
use PoP\ComponentRouting\RouteModuleProcessorManagerInterface;

class RouteModuleProcessorManagerFacade
{
    public static function getInstance(): RouteModuleProcessorManagerInterface
    {
        /**
         * @var RouteModuleProcessorManagerInterface
         */
        $service = App::getContainer()->get(RouteModuleProcessorManagerInterface::class);
        return $service;
    }
}
