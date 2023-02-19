<?php

declare(strict_types=1);

namespace PoPCMSSchema\Posts\ConditionalOnModule\Users\ConditionalOnModule\RESTAPI\ComponentRoutingProcessors;

use PoPAPI\API\Response\Schemes as APISchemes;
use PoPCMSSchema\CustomPosts\ConditionalOnModule\RESTAPI\ComponentRoutingProcessors\AbstractCustomPostRESTEntryComponentRoutingProcessor;
use PoPCMSSchema\Posts\ConditionalOnModule\Users\ConditionalOnModule\API\ComponentProcessors\FieldDataloadComponentProcessor;
use PoPCMSSchema\Posts\Module;
use PoPCMSSchema\Posts\ModuleConfiguration;
use PoPCMSSchema\Users\ConditionalOnModule\CustomPosts\ConditionalOnModule\RESTAPI\Hooks\CustomPostHookSet;
use PoPCMSSchema\Users\Routing\RequestNature;
use PoP\ComponentModel\Component\Component;
use PoP\Root\App;

class EntryComponentRoutingProcessor extends AbstractCustomPostRESTEntryComponentRoutingProcessor
{
    /**
     * Remove the author data, added by hook to CustomPosts
     */
    public function getGraphQLQueryToResolveRESTEndpoint(): string
    {
        if ($this->restEndpointGraphQLQuery === null) {
            $this->restEndpointGraphQLQuery = str_replace(
                CustomPostHookSet::AUTHOR_RESTFIELDS,
                '',
                parent::getGraphQLQueryToResolveRESTEndpoint()
            );
        }
        return $this->restEndpointGraphQLQuery;
    }

    /**
     * @return array<string,array<string,array<array<string,mixed>>>>
     */
    public function getStatePropertiesToSelectComponentByNatureAndRoute(): array
    {
        $restDataStructureFormatter = $this->getRestDataStructureFormatter();

        $ret = array();
        // Author's posts
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $routeComponents = array(
            $moduleConfiguration->getPostsRoute() => new Component(
                FieldDataloadComponentProcessor::class,
                FieldDataloadComponentProcessor::COMPONENT_DATALOAD_RELATIONALFIELDS_AUTHORPOSTLIST,
                [
                    'query' => !empty(App::getState('query'))
                        ? App::getState('query')
                        : $this->getGraphQLQueryToResolveRESTEndpoint()
                ]
            ),
        );
        foreach ($routeComponents as $route => $component) {
            $ret[RequestNature::USER][$route][] = [
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
