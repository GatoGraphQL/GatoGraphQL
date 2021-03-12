<?php

declare(strict_types=1);

namespace PoPSchema\Pages\Conditional\API\RouteModuleProcessors;

use PoP\ModuleRouting\AbstractEntryRouteModuleProcessor;
use PoPSchema\Pages\Routing\RouteNatures;
use PoP\API\Response\Schemes as APISchemes;
use PoPSchema\Pages\ModuleProcessors\FieldDataloads;

class EntryRouteModuleProcessor extends AbstractEntryRouteModuleProcessor
{
    /**
     * @return array<string, array<array>>
     */
    public function getModulesVarsPropertiesByNature(): array
    {
        $ret = array();

        $ret[RouteNatures::PAGE][] = [
            'module' => [FieldDataloads::class, FieldDataloads::MODULE_DATALOAD_RELATIONALFIELDS_PAGE],
            'conditions' => [
                'scheme' => APISchemes::API,
            ],
        ];

        return $ret;
    }
}
