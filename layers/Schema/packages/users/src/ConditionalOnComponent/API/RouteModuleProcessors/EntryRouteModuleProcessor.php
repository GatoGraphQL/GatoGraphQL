<?php

declare(strict_types=1);

namespace PoPSchema\Users\ConditionalOnComponent\API\RouteModuleProcessors;

use PoP\API\Response\Schemes as APISchemes;
use PoP\ModuleRouting\AbstractEntryRouteModuleProcessor;
use PoP\Routing\RouteNatures;
use PoPSchema\Users\Component;
use PoPSchema\Users\ComponentConfiguration;
use PoPSchema\Users\ConditionalOnComponent\API\ModuleProcessors\FieldDataloadModuleProcessor;
use PoPSchema\Users\Routing\RouteNatures as UserRouteNatures;

class EntryRouteModuleProcessor extends AbstractEntryRouteModuleProcessor
{
    /**
     * @return array<string, array<array>>
     */
    public function getModulesVarsPropertiesByNature(): array
    {
        $ret = array();
        $ret[UserRouteNatures::USER][] = [
            'module' => [FieldDataloadModuleProcessor::class, FieldDataloadModuleProcessor::MODULE_DATALOAD_RELATIONALFIELDS_SINGLEUSER],
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
            ComponentConfiguration::getUsersRoute() => [FieldDataloadModuleProcessor::class, FieldDataloadModuleProcessor::MODULE_DATALOAD_RELATIONALFIELDS_USERLIST],
        );
        foreach ($routemodules as $route => $module) {
            $ret[RouteNatures::STANDARD][$route][] = [
                'module' => $module,
                'conditions' => [
                    'scheme' => APISchemes::API,
                ],
            ];
        }
        return $ret;
    }
}
