<?php

declare(strict_types=1);

namespace PoP\Engine\RouteModuleProcessors;

use PoP\Engine\ModuleProcessors\RootModuleProcessors;
use PoP\ModuleRouting\AbstractEntryRouteModuleProcessor;

class EntryRouteModuleProcessor extends AbstractEntryRouteModuleProcessor
{
    /**
     * @return array<string, string[]>
     */
    public function getModulesVarsProperties(): array
    {
        $ret = array();

        $ret[] = [
            'module' => [RootModuleProcessors::class, RootModuleProcessors::MODULE_EMPTY],
        ];

        return $ret;
    }
}
