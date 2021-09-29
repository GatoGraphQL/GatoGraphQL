<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ModuleRouting;

use PoP\ComponentModel\State\ApplicationState;
use PoP\ModuleRouting\AbstractRouteModuleProcessorManager;

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
