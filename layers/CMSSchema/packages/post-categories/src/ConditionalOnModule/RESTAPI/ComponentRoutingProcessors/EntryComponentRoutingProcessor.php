<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostCategories\ConditionalOnModule\RESTAPI\ComponentRoutingProcessors;

use PoP\ComponentModel\Component\Component;
use PoP\Root\App;
use PoP\Root\Routing\RequestNature;
use PoPAPI\API\Response\Schemes as APISchemes;
use PoPAPI\RESTAPI\ComponentRoutingProcessors\AbstractRESTEntryComponentRoutingProcessor;
use PoPCMSSchema\Categories\Routing\RequestNature as CategoryRequestNature;
use PoPCMSSchema\PostCategories\ConditionalOnModule\API\ComponentProcessors\CategoryPostFieldDataloadComponentProcessor;
use PoPCMSSchema\PostCategories\ConditionalOnModule\API\ComponentProcessors\PostCategoryFieldDataloadComponentProcessor;
use PoPCMSSchema\PostCategories\Module;
use PoPCMSSchema\PostCategories\ModuleConfiguration;
use PoPCMSSchema\PostCategories\TypeAPIs\PostCategoryTypeAPIInterface;
use PoPCMSSchema\Posts\Module as PostsModule;
use PoPCMSSchema\Posts\ModuleConfiguration as PostsModuleConfiguration;

class EntryComponentRoutingProcessor extends AbstractRESTEntryComponentRoutingProcessor
{
    private ?PostCategoryTypeAPIInterface $postCategoryTypeAPI = null;

    final protected function getPostCategoryTypeAPI(): PostCategoryTypeAPIInterface
    {
        if ($this->postCategoryTypeAPI === null) {
            /** @var PostCategoryTypeAPIInterface */
            $postCategoryTypeAPI = $this->instanceManager->getInstance(PostCategoryTypeAPIInterface::class);
            $this->postCategoryTypeAPI = $postCategoryTypeAPI;
        }
        return $this->postCategoryTypeAPI;
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
        $ret[CategoryRequestNature::CATEGORY][] = [
            'component' => new Component(
                PostCategoryFieldDataloadComponentProcessor::class,
                PostCategoryFieldDataloadComponentProcessor::COMPONENT_DATALOAD_RELATIONALFIELDS_CATEGORY,
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
                    'taxonomy-name' => $this->getPostCategoryTypeAPI()->getPostCategoryTaxonomyName(),
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
        $postCategoryTypeAPI = $this->getPostCategoryTypeAPI();
        $restDataStructureFormatter = $this->getRestDataStructureFormatter();

        $ret = array();
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $routeComponents = array(
            $moduleConfiguration->getPostCategoriesRoute() => new Component(
                PostCategoryFieldDataloadComponentProcessor::class,
                PostCategoryFieldDataloadComponentProcessor::COMPONENT_DATALOAD_RELATIONALFIELDS_CATEGORYLIST,
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
        /** @var PostsModuleConfiguration */
        $moduleConfiguration = App::getModule(PostsModule::class)->getConfiguration();
        $routeComponents = array(
            $moduleConfiguration->getPostsRoute() => new Component(
                CategoryPostFieldDataloadComponentProcessor::class,
                CategoryPostFieldDataloadComponentProcessor::COMPONENT_DATALOAD_RELATIONALFIELDS_CATEGORYPOSTLIST,
                [
                    'query' => !empty(App::getState('query'))
                        ? App::getState('query')
                        : $this->getGraphQLQueryToResolveRESTEndpoint()
                ]
            ),
        );
        foreach ($routeComponents as $route => $component) {
            $ret[CategoryRequestNature::CATEGORY][$route][] = [
                'component' => $component,
                'conditions' => [
                    'scheme' => APISchemes::API,
                    'datastructure' => $restDataStructureFormatter->getName(),
                    'routing' => [
                        'taxonomy-name' => $postCategoryTypeAPI->getPostCategoryTaxonomyName(),
                    ],
                ],
            ];
        }
        return $ret;
    }
}
