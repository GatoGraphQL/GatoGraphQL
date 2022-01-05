<?php

declare(strict_types=1);

namespace PoPSchema\Posts\ConditionalOnComponent\Users\ConditionalOnComponent\API\RouteModuleProcessors;

use PoP\Engine\App;
use PoP\API\Response\Schemes as APISchemes;
use PoP\ModuleRouting\AbstractEntryRouteModuleProcessor;
use PoPSchema\Posts\Component;
use PoPSchema\Posts\ComponentConfiguration;
use PoPSchema\Posts\ConditionalOnComponent\Users\ConditionalOnComponent\API\ModuleProcessors\FieldDataloadModuleProcessor;
use PoPSchema\Users\Routing\RouteNatures;

class EntryRouteModuleProcessor extends AbstractEntryRouteModuleProcessor
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getModulesVarsPropertiesByNatureAndRoute(): array
    {
        $ret = array();

        // Author's posts
        /** @var ComponentConfiguration */
        $componentConfiguration = App::getComponentManager()->getComponent(Component::class)->getConfiguration();
        $routemodules = array(
            $componentConfiguration->getPostsRoute() => [
                FieldDataloadModuleProcessor::class,
                FieldDataloadModuleProcessor::MODULE_DATALOAD_RELATIONALFIELDS_AUTHORPOSTLIST
            ],
        );
        foreach ($routemodules as $route => $module) {
            $ret[RouteNatures::USER][$route][] = [
                'module' => $module,
                'conditions' => [
                    'scheme' => APISchemes::API,
                ],
            ];
        }
        return $ret;
    }
}
