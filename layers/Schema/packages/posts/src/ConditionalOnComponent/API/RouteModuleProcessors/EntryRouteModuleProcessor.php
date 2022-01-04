<?php

declare(strict_types=1);

namespace PoPSchema\Posts\ConditionalOnComponent\API\RouteModuleProcessors;

use PoP\API\Response\Schemes as APISchemes;
use PoP\ModuleRouting\AbstractEntryRouteModuleProcessor;
use PoP\Routing\RouteNatures;
use PoPSchema\CustomPosts\Routing\RouteNatures as CustomPostRouteNatures;
use PoPSchema\Posts\Component;
use PoPSchema\Posts\ComponentConfiguration;
use PoPSchema\Posts\ConditionalOnComponent\API\ModuleProcessors\FieldDataloadModuleProcessor;

class EntryRouteModuleProcessor extends AbstractEntryRouteModuleProcessor
{
    /**
     * @return array<string, array<array>>
     */
    public function getModulesVarsPropertiesByNature(): array
    {
        $ret = array();
        $ret[CustomPostRouteNatures::CUSTOMPOST][] = [
            'module' => [FieldDataloadModuleProcessor::class, FieldDataloadModuleProcessor::MODULE_DATALOAD_RELATIONALFIELDS_SINGLEPOST],
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
            ComponentConfiguration::getPostsRoute() => [FieldDataloadModuleProcessor::class, FieldDataloadModuleProcessor::MODULE_DATALOAD_RELATIONALFIELDS_POSTLIST],
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
