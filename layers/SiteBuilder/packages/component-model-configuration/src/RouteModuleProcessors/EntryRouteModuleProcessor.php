<?php

declare(strict_types=1);

namespace PoP\ConfigurationComponentModel\RouteModuleProcessors;

use PoP_ConfigurationComponentModel_Module_Processor_Elements;
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
            'module' => [PoP_ConfigurationComponentModel_Module_Processor_Elements::class, PoP_ConfigurationComponentModel_Module_Processor_Elements::MODULE_EMPTY],
        ];

        return $ret;
    }
}
