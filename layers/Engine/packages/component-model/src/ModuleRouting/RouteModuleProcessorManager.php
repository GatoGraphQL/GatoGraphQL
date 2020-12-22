<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ModuleRouting;

use PoP\ModuleRouting\AbstractRouteModuleProcessorManager;
use PoP\ComponentModel\State\ApplicationState;

class RouteModuleProcessorManager extends AbstractRouteModuleProcessorManager
{
    /**
     * @return array<string, mixed>
     */
    public function getVars(): array
    {
        return ApplicationState::getVars();
    }
}
