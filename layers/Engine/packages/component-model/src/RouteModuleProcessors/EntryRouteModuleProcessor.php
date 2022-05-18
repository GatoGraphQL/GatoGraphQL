<?php

declare(strict_types=1);

namespace PoP\ComponentModel\RouteModuleProcessors;

use PoP\ComponentModel\ModuleProcessors\RootModuleProcessors;
use PoP\ComponentRouting\AbstractEntryRouteModuleProcessor;

class EntryRouteModuleProcessor extends AbstractEntryRouteModuleProcessor
{
    /**
     * @return array<array<string, string[]>>
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
