<?php

declare(strict_types=1);

namespace PoP\Engine\RouteModuleProcessors;

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
            'module' => [\PoP_Engine_Module_Processor_Elements::class, \PoP_Engine_Module_Processor_Elements::MODULE_EMPTY],
        ];

        return $ret;
    }
}
