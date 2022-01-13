<?php

declare(strict_types=1);

namespace PoP\API\RouteModuleProcessors;

use PoP\API\ModuleProcessors\RootRelationalFieldDataloadModuleProcessor;
use PoP\API\Response\Schemes as APISchemes;
use PoP\ModuleRouting\AbstractEntryRouteModuleProcessor;

class EntryRouteModuleProcessor extends AbstractEntryRouteModuleProcessor
{
    /**
     * @return array<array>
     */
    public function getModulesVarsProperties(): array
    {
        $ret = array();

        $ret[] = [
            'module' => [RootRelationalFieldDataloadModuleProcessor::class, RootRelationalFieldDataloadModuleProcessor::MODULE_DATALOAD_RELATIONALFIELDS_ROOT],
            'conditions' => [
                'scheme' => APISchemes::API,
            ],
        ];

        return $ret;
    }
}
