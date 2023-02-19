<?php

declare(strict_types=1);

namespace PoPCMSSchema\Users\ConditionalOnModule\RESTAPI\ComponentRoutingProcessors;

use PoP\ComponentModel\Component\Component;
use PoP\ComponentModel\Module as ComponentModelModule;
use PoP\ComponentModel\ModuleConfiguration as ComponentModelModuleConfiguration;
use PoP\Root\App;
use PoP\Root\Routing\RequestNature;
use PoPAPI\API\Response\Schemes as APISchemes;
use PoPAPI\RESTAPI\ComponentRoutingProcessors\AbstractRESTEntryComponentRoutingProcessor;
use PoPCMSSchema\Users\ConditionalOnModule\API\ComponentProcessors\FieldDataloadComponentProcessor;
use PoPCMSSchema\Users\Module;
use PoPCMSSchema\Users\ModuleConfiguration;
use PoPCMSSchema\Users\Routing\RequestNature as UserRequestNature;

class EntryComponentRoutingProcessor extends AbstractRESTEntryComponentRoutingProcessor
{
    protected function doGetGraphQLQueryToResolveRESTEndpoint(): string
    {
        return <<<GRAPHQL
            query {
                id
                name
                url
            }
        GRAPHQL;
    }

    /**
     * @return array<string,array<array<string,mixed>>>
     */
    public function getStatePropertiesToSelectComponentByNature(): array
    {
        $ret = array();
        $ret[UserRequestNature::USER][] = [
            'component' => new Component(
                FieldDataloadComponentProcessor::class,
                FieldDataloadComponentProcessor::COMPONENT_DATALOAD_RELATIONALFIELDS_SINGLEUSER,
                [
                    'query' => !empty(App::getState('query'))
                        ? App::getState('query')
                        : $this->getGraphQLQueryToResolveRESTEndpoint()
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
        $restDataStructureFormatter = $this->getRestDataStructureFormatter();

        $ret = array();
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        /** @var ComponentModelModuleConfiguration */
        $componentModelModuleConfiguration = App::getModule(ComponentModelModule::class)->getConfiguration();
        $routeComponents = array(
            $moduleConfiguration->getUsersRoute() => new Component(
                FieldDataloadComponentProcessor::class,
                $componentModelModuleConfiguration->exposeSensitiveDataInSchema() ?
                    FieldDataloadComponentProcessor::COMPONENT_DATALOAD_RELATIONALFIELDS_ADMINUSERLIST
                    : FieldDataloadComponentProcessor::COMPONENT_DATALOAD_RELATIONALFIELDS_USERLIST,
                [
                    'query' => !empty(App::getState('query'))
                        ? App::getState('query')
                        : $this->getGraphQLQueryToResolveRESTEndpoint()
                ]
            ),
        );
        foreach ($routeComponents as $route => $component) {
            $ret[RequestNature::GENERIC][$route][] = [
                'component' => $component,
                'conditions' => [
                    'scheme' => APISchemes::API,
                    'datastructure' => $restDataStructureFormatter->getName(),
                ],
            ];
        }
        return $ret;
    }
}
