<?php

declare(strict_types=1);

namespace PoPCMSSchema\Posts\ConditionalOnModule\RESTAPI\RouteModuleProcessors;

use PoP\Root\App;
use PoPAPI\API\Response\Schemes as APISchemes;
use PoP\ComponentModel\Module as ComponentModelModule;
use PoP\ComponentModel\ModuleConfiguration as ComponentModelComponentConfiguration;
use PoP\Root\Routing\RequestNature;
use PoPCMSSchema\CustomPosts\ConditionalOnModule\RESTAPI\RouteModuleProcessors\AbstractCustomPostRESTEntryRouteModuleProcessor;
use PoPCMSSchema\CustomPosts\Routing\RequestNature as CustomPostRequestNature;
use PoPCMSSchema\Posts\Module;
use PoPCMSSchema\Posts\ModuleConfiguration;
use PoPCMSSchema\Posts\ConditionalOnModule\API\ModuleProcessors\FieldDataloadModuleProcessor;

class EntryRouteModuleProcessor extends AbstractCustomPostRESTEntryRouteModuleProcessor
{
    /**
     * @return array<string, array<array>>
     */
    public function getModulesVarsPropertiesByNature(): array
    {
        $ret = array();
        $ret[CustomPostRequestNature::CUSTOMPOST][] = [
            'module' => [
                FieldDataloadModuleProcessor::class,
                FieldDataloadModuleProcessor::MODULE_DATALOAD_RELATIONALFIELDS_SINGLEPOST,
                [
                    'fields' => !empty(App::getState('query')) ?
                        App::getState('query') :
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
        /** @var ModuleConfiguration */
        $componentConfiguration = App::getComponent(Module::class)->getConfiguration();
        /** @var ComponentModelComponentConfiguration */
        $componentModelComponentConfiguration = App::getComponent(ComponentModelModule::class)->getConfiguration();
        $routemodules = array(
            $componentConfiguration->getPostsRoute() => [
                FieldDataloadModuleProcessor::class,
                $componentModelComponentConfiguration->enableAdminSchema() ?
                    FieldDataloadModuleProcessor::MODULE_DATALOAD_RELATIONALFIELDS_ADMINPOSTLIST
                    : FieldDataloadModuleProcessor::MODULE_DATALOAD_RELATIONALFIELDS_POSTLIST,
                [
                    'fields' => !empty(App::getState('query')) ?
                        App::getState('query') :
                        $this->getRESTFields()
                    ]
                ],
        );
        foreach ($routemodules as $route => $module) {
            $ret[RequestNature::GENERIC][$route][] = [
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
