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

    final public function setPostCategoryTypeAPI(PostCategoryTypeAPIInterface $postCategoryTypeAPI): void
    {
        $this->postCategoryTypeAPI = $postCategoryTypeAPI;
    }
    final protected function getPostCategoryTypeAPI(): PostCategoryTypeAPIInterface
    {
        return $this->postCategoryTypeAPI ??= $this->instanceManager->getInstance(PostCategoryTypeAPIInterface::class);
    }

    protected function getInitialRESTFields(): string
    {
        return 'id|name|count|url';
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
                        : $this->getRESTFields()
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
        $ret = array();
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $routeComponents = array(
            $moduleConfiguration->getPostCategoriesRoute() => [
                PostCategoryFieldDataloadComponentProcessor::class,
                PostCategoryFieldDataloadComponentProcessor::COMPONENT_DATALOAD_RELATIONALFIELDS_CATEGORYLIST,
                [
                    'query' => !empty(App::getState('query'))
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
        /** @var PostsModuleConfiguration */
        $moduleConfiguration = App::getModule(PostsModule::class)->getConfiguration();
        $routeComponents = array(
            $moduleConfiguration->getPostsRoute() => [
                CategoryPostFieldDataloadComponentProcessor::class,
                CategoryPostFieldDataloadComponentProcessor::COMPONENT_DATALOAD_RELATIONALFIELDS_CATEGORYPOSTLIST,
                [
                    'query' => !empty(App::getState('query'))
                        ? App::getState('query')
                        : $this->getRESTFields()
                    ]
                ],
        );
        foreach ($routeComponents as $route => $component) {
            $ret[CategoryRequestNature::CATEGORY][$route][] = [
                'component' => $component,
                'conditions' => [
                    'scheme' => APISchemes::API,
                    'datastructure' => $this->getRestDataStructureFormatter()->getName(),
                    'routing' => [
                        'taxonomy-name' => $this->getPostCategoryTypeAPI()->getPostCategoryTaxonomyName(),
                    ],
                ],
            ];
        }
        return $ret;
    }
}
