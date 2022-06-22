<?php

declare(strict_types=1);

namespace PoPCMSSchema\Posts\ConditionalOnModule\RESTAPI\ComponentRoutingProcessors;

use PoP\ComponentModel\Component\Component;
use PoP\ComponentModel\Module as ComponentModelModule;
use PoP\ComponentModel\ModuleConfiguration as ComponentModelModuleConfiguration;
use PoP\Root\App;
use PoP\Root\Routing\RequestNature;
use PoPAPI\API\Response\Schemes as APISchemes;
use PoPCMSSchema\CustomPosts\ConditionalOnModule\RESTAPI\ComponentRoutingProcessors\AbstractCustomPostRESTEntryComponentRoutingProcessor;
use PoPCMSSchema\CustomPosts\Routing\RequestNature as CustomPostRequestNature;
use PoPCMSSchema\Posts\ConditionalOnModule\API\ComponentProcessors\FieldDataloadComponentProcessor;
use PoPCMSSchema\Posts\Module;
use PoPCMSSchema\Posts\ModuleConfiguration;

class EntryComponentRoutingProcessor extends AbstractCustomPostRESTEntryComponentRoutingProcessor
{
    /**
     * @return array<string,array<array<string,mixed>>>
     */
    public function getStatePropertiesToSelectComponentByNature(): array
    {
        $ret = array();
        $ret[CustomPostRequestNature::CUSTOMPOST][] = [
            'component' => new Component(
                FieldDataloadComponentProcessor::class,
                FieldDataloadComponentProcessor::COMPONENT_DATALOAD_RELATIONALFIELDS_SINGLEPOST,
                [
                    'fields' => !empty(App::getState('query'))
                        ? App::getState('query')
                        : $this->getRESTFields()
                    ]
            ),
            'conditions' => [
                'scheme' => APISchemes::API,
                'datastructure' => $this->getRestDataStructureFormatter()->getName(),
            ],
        ];

        return $ret;
    }

    /**
     * @return array<string,array<string,array<array<string,mixed>>>>
     */
    public function getStatePropertiesToSelectComponentByNatureAndRoute(): array
    {
        $ret = array();
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        /** @var ComponentModelModuleConfiguration */
        $componentModelModuleConfiguration = App::getModule(ComponentModelModule::class)->getConfiguration();
        $routeComponents = array(
            $moduleConfiguration->getPostsRoute() => [
                FieldDataloadComponentProcessor::class,
                $componentModelModuleConfiguration->enableAdminSchema() ?
                    FieldDataloadComponentProcessor::COMPONENT_DATALOAD_RELATIONALFIELDS_ADMINPOSTLIST
                    : FieldDataloadComponentProcessor::COMPONENT_DATALOAD_RELATIONALFIELDS_POSTLIST,
                [
                    'fields' => !empty(App::getState('query'))
                        ? App::getState('query')
                        : $this->getRESTFields()
                    ]
                ],
        );
        foreach ($routeComponents as $route => $component) {
            $ret[RequestNature::GENERIC][$route][] = [
                'component' => $component,
                'conditions' => [
                    'scheme' => APISchemes::API,
                    'datastructure' => $this->getRestDataStructureFormatter()->getName(),
                ],
            ];
        }

        return $ret;
    }
}
