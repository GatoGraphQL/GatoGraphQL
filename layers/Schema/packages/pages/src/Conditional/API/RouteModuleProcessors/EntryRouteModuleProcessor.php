<?php

declare(strict_types=1);

namespace PoPSchema\Pages\Conditional\API\RouteModuleProcessors;

use PoP\ModuleRouting\AbstractEntryRouteModuleProcessor;
use PoPSchema\Pages\Routing\RouteNatures;
use PoP\API\Response\Schemes as APISchemes;

class EntryRouteModuleProcessor extends AbstractEntryRouteModuleProcessor
{
    /**
     * @return array<string, array<array>>
     */
    public function getModulesVarsPropertiesByNature(): array
    {
        $ret = array();

        $ret[RouteNatures::PAGE][] = [
            'module' => [\PoP_Pages_Module_Processor_FieldDataloads::class, \PoP_Pages_Module_Processor_FieldDataloads::MODULE_DATALOAD_RELATIONALFIELDS_PAGE],
            'conditions' => [
                'scheme' => APISchemes::API,
            ],
        ];

        return $ret;
    }
}
