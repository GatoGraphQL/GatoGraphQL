<?php

declare(strict_types=1);

namespace PoPSchema\Posts\ConditionalOnComponent\RESTAPI\RouteModuleProcessors;

use PoP\API\Response\Schemes as APISchemes;
use PoP\ComponentModel\Component as ComponentModelComponent;
use PoP\ComponentModel\ComponentConfiguration as ComponentModelComponentConfiguration;
use PoP\ComponentModel\State\ApplicationState;
use PoP\Routing\RouteNatures;
use PoPSchema\CustomPosts\ConditionalOnComponent\RESTAPI\RouteModuleProcessors\AbstractCustomPostRESTEntryRouteModuleProcessor;
use PoPSchema\CustomPosts\Routing\RouteNatures as CustomPostRouteNatures;
use PoPSchema\Posts\Component;
use PoPSchema\Posts\ComponentConfiguration;
use PoPSchema\Posts\ConditionalOnComponent\API\ModuleProcessors\FieldDataloadModuleProcessor;

class EntryRouteModuleProcessor extends AbstractCustomPostRESTEntryRouteModuleProcessor
{
    /**
     * @return array<string, array<array>>
     */
    public function getModulesVarsPropertiesByNature(): array
    {
        $ret = array();
        $vars = ApplicationState::getVars();
        $ret[CustomPostRouteNatures::CUSTOMPOST][] = [
            'module' => [
                FieldDataloadModuleProcessor::class,
                FieldDataloadModuleProcessor::MODULE_DATALOAD_RELATIONALFIELDS_SINGLEPOST,
                [
                    'fields' => isset($vars['query']) ?
                        $vars['query'] :
                        $this->getRESTFields()
                    ]
                ],
            'conditions' => [
                'scheme' => APISchemes::API,
                'datastructure' => $this->getRestDataStructureFormatter()->getName(),
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
        $vars = ApplicationState::getVars();
        /** @var ComponentConfiguration */
        $componentConfiguration = \PoP\Root\Managers\ComponentManager::getComponent(Component::class)->getConfiguration();
        /** @var ComponentModelComponentConfiguration */
        $componentModelComponentConfiguration = \PoP\Root\Managers\ComponentManager::getComponent(ComponentModelComponent::class)->getConfiguration();
        $routemodules = array(
            $componentConfiguration->getPostsRoute() => [
                FieldDataloadModuleProcessor::class,
                $componentModelComponentConfiguration->enableAdminSchema() ?
                    FieldDataloadModuleProcessor::MODULE_DATALOAD_RELATIONALFIELDS_ADMINPOSTLIST
                    : FieldDataloadModuleProcessor::MODULE_DATALOAD_RELATIONALFIELDS_POSTLIST,
                [
                    'fields' => isset($vars['query']) ?
                        $vars['query'] :
                        $this->getRESTFields()
                    ]
                ],
        );
        foreach ($routemodules as $route => $module) {
            $ret[RouteNatures::STANDARD][$route][] = [
                'module' => $module,
                'conditions' => [
                    'scheme' => APISchemes::API,
                    'datastructure' => $this->getRestDataStructureFormatter()->getName(),
                ],
            ];
        }

        return $ret;
    }
}
