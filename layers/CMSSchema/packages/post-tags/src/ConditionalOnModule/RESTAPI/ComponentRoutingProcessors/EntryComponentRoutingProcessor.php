<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostTags\ConditionalOnModule\RESTAPI\ComponentRoutingProcessors;

use PoP\ComponentModel\Component\Component;
use PoP\Root\App;
use PoP\Root\Routing\RequestNature;
use PoPAPI\API\Response\Schemes as APISchemes;
use PoPAPI\RESTAPI\ComponentRoutingProcessors\AbstractRESTEntryComponentRoutingProcessor;
use PoPCMSSchema\Posts\Module as PostsModule;
use PoPCMSSchema\Posts\ModuleConfiguration as PostsModuleConfiguration;
use PoPCMSSchema\PostTags\ConditionalOnModule\API\ComponentProcessors\PostTagFieldDataloadComponentProcessor;
use PoPCMSSchema\PostTags\ConditionalOnModule\API\ComponentProcessors\TagPostFieldDataloadComponentProcessor;
use PoPCMSSchema\PostTags\Module;
use PoPCMSSchema\PostTags\ModuleConfiguration;
use PoPCMSSchema\PostTags\TypeAPIs\PostTagTypeAPIInterface;
use PoPCMSSchema\Tags\Routing\RequestNature as TagRequestNature;

class EntryComponentRoutingProcessor extends AbstractRESTEntryComponentRoutingProcessor
{
    private ?PostTagTypeAPIInterface $postTagTypeAPI = null;

    final public function setPostTagTypeAPI(PostTagTypeAPIInterface $postTagTypeAPI): void
    {
        $this->postTagTypeAPI = $postTagTypeAPI;
    }
    final protected function getPostTagTypeAPI(): PostTagTypeAPIInterface
    {
        /** @var PostTagTypeAPIInterface */
        return $this->postTagTypeAPI ??= $this->instanceManager->getInstance(PostTagTypeAPIInterface::class);
    }

    protected function doGetGraphQLQueryToResolveRESTEndpoint(): string
    {
        return <<<GRAPHQL
            query {
                id
                name
                count
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
        $ret[TagRequestNature::TAG][] = [
            'component' => new Component(
                PostTagFieldDataloadComponentProcessor::class,
                PostTagFieldDataloadComponentProcessor::COMPONENT_DATALOAD_RELATIONALFIELDS_TAG,
                [
                    'query' => !empty(App::getState('query'))
                        ? App::getState('query')
                        : $this->getGraphQLQueryToResolveRESTEndpoint()
                ]
            ),
            'conditions' => [
                'scheme' => APISchemes::API,
                'datastructure' => $this->getRestDataStructureFormatter()->getName(),
                'routing' => [
                    'taxonomy-name' => $this->getPostTagTypeAPI()->getPostTagTaxonomyName(),
                ],
            ],
        ];

        return $ret;
    }

    /**
     * @return array<string,array<string,array<array<string,mixed>>>>
     */
    public function getStatePropertiesToSelectComponentByNatureAndRoute(): array
    {
        $postTagTypeAPI = $this->getPostTagTypeAPI();
        $restDataStructureFormatter = $this->getRestDataStructureFormatter();

        $ret = array();
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $routeComponents = array(
            $moduleConfiguration->getPostTagsRoute() => [
                PostTagFieldDataloadComponentProcessor::class,
                PostTagFieldDataloadComponentProcessor::COMPONENT_DATALOAD_RELATIONALFIELDS_TAGLIST,
                [
                    'query' => !empty(App::getState('query'))
                        ? App::getState('query')
                        : $this->getGraphQLQueryToResolveRESTEndpoint()
                ]
            ],
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
        /** @var PostsModuleConfiguration */
        $moduleConfiguration = App::getModule(PostsModule::class)->getConfiguration();
        $routeComponents = array(
            $moduleConfiguration->getPostsRoute() => [
                TagPostFieldDataloadComponentProcessor::class,
                TagPostFieldDataloadComponentProcessor::COMPONENT_DATALOAD_RELATIONALFIELDS_TAGPOSTLIST,
                [
                    'query' => !empty(App::getState('query'))
                        ? App::getState('query')
                        : $this->getGraphQLQueryToResolveRESTEndpoint()
                    ]
                ],
        );
        foreach ($routeComponents as $route => $component) {
            $ret[TagRequestNature::TAG][$route][] = [
                'component' => $component,
                'conditions' => [
                    'scheme' => APISchemes::API,
                    'datastructure' => $restDataStructureFormatter->getName(),
                    'routing' => [
                        'taxonomy-name' => $postTagTypeAPI->getPostTagTaxonomyName(),
                    ],
                ],
            ];
        }
        return $ret;
    }
}
