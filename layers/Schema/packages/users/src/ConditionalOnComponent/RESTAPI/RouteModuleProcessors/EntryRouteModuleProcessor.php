<?php

declare(strict_types=1);

namespace PoPSchema\Users\ConditionalOnComponent\RESTAPI\RouteModuleProcessors;

use PoP\Engine\App;
use PoP\API\Response\Schemes as APISchemes;
use PoP\ComponentModel\Component as ComponentModelComponent;
use PoP\ComponentModel\ComponentConfiguration as ComponentModelComponentConfiguration;
use PoP\ComponentModel\State\ApplicationState;
use PoP\RESTAPI\RouteModuleProcessors\AbstractRESTEntryRouteModuleProcessor;
use PoP\Routing\RouteNatures;
use PoPSchema\Users\Component;
use PoPSchema\Users\ComponentConfiguration;
use PoPSchema\Users\ConditionalOnComponent\API\ModuleProcessors\FieldDataloadModuleProcessor;
use PoPSchema\Users\Routing\RouteNatures as UserRouteNatures;

class EntryRouteModuleProcessor extends AbstractRESTEntryRouteModuleProcessor
{
    protected function getInitialRESTFields(): string
    {
        return 'id|name|url';
    }

    /**
     * @return array<string, array<array>>
     */
    public function getModulesVarsPropertiesByNature(): array
    {
        $ret = array();
        $vars = ApplicationState::getVars();
        $ret[UserRouteNatures::USER][] = [
            'module' => [
                FieldDataloadModuleProcessor::class,
                FieldDataloadModuleProcessor::MODULE_DATALOAD_RELATIONALFIELDS_SINGLEUSER,
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
        $componentConfiguration = App::getComponent(Component::class)->getConfiguration();
        /** @var ComponentModelComponentConfiguration */
        $componentModelComponentConfiguration = App::getComponent(ComponentModelComponent::class)->getConfiguration();
        $routemodules = array(
            $componentConfiguration->getUsersRoute() => [
                FieldDataloadModuleProcessor::class,
                $componentModelComponentConfiguration->enableAdminSchema() ?
                    FieldDataloadModuleProcessor::MODULE_DATALOAD_RELATIONALFIELDS_ADMINUSERLIST
                    : FieldDataloadModuleProcessor::MODULE_DATALOAD_RELATIONALFIELDS_USERLIST,
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
