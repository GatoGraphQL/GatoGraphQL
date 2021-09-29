<?php

declare(strict_types=1);

namespace PoP\API\RouteModuleProcessors;

use PoP\API\ModuleProcessors\RootRelationalFieldDataloadModuleProcessor;
use PoP\API\Response\Schemes as APISchemes;
use PoP\ModuleRouting\AbstractEntryRouteModuleProcessor;
use PoP\Routing\RouteNatures;

class EntryRouteModuleProcessor extends AbstractEntryRouteModuleProcessor
{
    /**
     * @return array<string, array<array>>
     */
    public function getModulesVarsPropertiesByNature(): array
    {
        $ret = array();

        $ret[RouteNatures::HOME][] = [
            'module' => [RootRelationalFieldDataloadModuleProcessor::class, RootRelationalFieldDataloadModuleProcessor::MODULE_DATALOAD_RELATIONALFIELDS_ROOT],
            'conditions' => [
                'scheme' => APISchemes::API,
            ],
        ];

        return $ret;
    }
}
