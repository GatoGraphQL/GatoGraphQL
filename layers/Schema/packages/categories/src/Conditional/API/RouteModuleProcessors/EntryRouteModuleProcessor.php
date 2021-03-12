<?php

declare(strict_types=1);

namespace PoPSchema\Categories\Conditional\API\RouteModuleProcessors;

use PoP\ModuleRouting\AbstractEntryRouteModuleProcessor;
use PoP\Routing\RouteNatures;
use PoPSchema\Categories\Routing\RouteNatures as CategoryRouteNatures;
use PoP\API\Response\Schemes as APISchemes;
use PoPSchema\Categories\ModuleProcessors\FieldDataloadModuleProcessor;

class EntryRouteModuleProcessor extends AbstractEntryRouteModuleProcessor
{
    /**
     * @return array<string, array<array>>
     */
    public function getModulesVarsPropertiesByNature(): array
    {
        $ret = array();
        $ret[CategoryRouteNatures::CATEGORY][] = [
            'module' => [FieldDataloadModuleProcessor::class, FieldDataloadModuleProcessor::MODULE_DATALOAD_RELATIONALFIELDS_CATEGORY],
            'conditions' => [
                'scheme' => APISchemes::API,
            ],
        ];
        return $ret;
    }

    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getModulesVarsPropertiesByNatureAndRoute(): array
    {
        $ret = array();
        $routemodules = array(
            POP_CATEGORIES_ROUTE_CATEGORIES => [FieldDataloadModuleProcessor::class, FieldDataloadModuleProcessor::MODULE_DATALOAD_RELATIONALFIELDS_CATEGORYLIST],
        );
        foreach ($routemodules as $route => $module) {
            $ret[RouteNatures::STANDARD][$route][] = [
                'module' => $module,
                'conditions' => [
                    'scheme' => APISchemes::API,
                ],
            ];
        }
        // Commented until creating route POP_CUSTOMPOSTS_ROUTE_CUSTOMPOSTS
        // $routemodules = array(
        //     POP_CUSTOMPOSTS_ROUTE_CUSTOMPOSTS => [FieldDataloadModuleProcessor::class, \PoP_Categories_Posts_Module_Processor_FieldDataloads::MODULE_DATALOAD_RELATIONALFIELDS_CATEGORYPOSTLIST],
        // );
        // foreach ($routemodules as $route => $module) {
        //     $ret[CategoryRouteNatures::CATEGORY][$route][] = [
        //         'module' => $module,
        //         'conditions' => [
        //             'scheme' => APISchemes::API,
        //         ],
        //     ];
        // }
        return $ret;
    }
}
