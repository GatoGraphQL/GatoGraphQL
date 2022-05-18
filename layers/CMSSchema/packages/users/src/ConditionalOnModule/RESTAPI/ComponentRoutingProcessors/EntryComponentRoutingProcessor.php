<?php

declare(strict_types=1);

namespace PoPCMSSchema\Users\ConditionalOnModule\RESTAPI\ComponentRoutingProcessors;

use PoP\Root\App;
use PoPAPI\API\Response\Schemes as APISchemes;
use PoP\ComponentModel\Module as ComponentModelModule;
use PoP\ComponentModel\ModuleConfiguration as ComponentModelModuleConfiguration;
use PoPAPI\RESTAPI\ComponentRoutingProcessors\AbstractRESTEntryComponentRoutingProcessor;
use PoP\Root\Routing\RequestNature;
use PoPCMSSchema\Users\Module;
use PoPCMSSchema\Users\ModuleConfiguration;
use PoPCMSSchema\Users\ConditionalOnModule\API\ComponentProcessors\FieldDataloadComponentProcessor;
use PoPCMSSchema\Users\Routing\RequestNature as UserRequestNature;

class EntryComponentRoutingProcessor extends AbstractRESTEntryComponentRoutingProcessor
{
    protected function getInitialRESTFields(): string
    {
        return 'id|name|url';
    }

    /**
     * @return array<string, array<array>>
     */
    public function getStatePropertiesToSelectComponentByNature(): array
    {
        $ret = array();
        $ret[UserRequestNature::USER][] = [
            'component' => [
                FieldDataloadComponentProcessor::class,
                FieldDataloadComponentProcessor::MODULE_DATALOAD_RELATIONALFIELDS_SINGLEUSER,
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
    public function getStatePropertiesToSelectComponentByNatureAndRoute(): array
    {
        $ret = array();
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        /** @var ComponentModelModuleConfiguration */
        $componentModelModuleConfiguration = App::getModule(ComponentModelModule::class)->getConfiguration();
        $routeComponents = array(
            $moduleConfiguration->getUsersRoute() => [
                FieldDataloadComponentProcessor::class,
                $componentModelModuleConfiguration->enableAdminSchema() ?
                    FieldDataloadComponentProcessor::MODULE_DATALOAD_RELATIONALFIELDS_ADMINUSERLIST
                    : FieldDataloadComponentProcessor::MODULE_DATALOAD_RELATIONALFIELDS_USERLIST,
                [
                    'fields' => !empty(App::getState('query')) ?
                        App::getState('query') :
                        $this->getRESTFields()
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
