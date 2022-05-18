<?php

declare(strict_types=1);

namespace PoPAPI\API\ComponentRoutingProcessors;

use PoPAPI\API\ModuleProcessors\RootRelationalFieldDataloadModuleProcessor;
use PoPAPI\API\Response\Schemes as APISchemes;
use PoPAPI\API\Routing\RequestNature;
use PoP\ComponentRouting\AbstractEntryComponentRoutingProcessor;

class EntryComponentRoutingProcessor extends AbstractEntryComponentRoutingProcessor
{
    /**
     * @return array<string, array<array>>
     */
    public function getModulesVarsPropertiesByNature(): array
    {
        $ret = array();

        $ret[RequestNature::QUERY_ROOT][] = [
            'module' => [
                RootRelationalFieldDataloadModuleProcessor::class,
                RootRelationalFieldDataloadModuleProcessor::MODULE_DATALOAD_RELATIONALFIELDS_ROOT
            ],
            'conditions' => [
                'scheme' => APISchemes::API,
            ],
        ];

        return $ret;
    }
}
