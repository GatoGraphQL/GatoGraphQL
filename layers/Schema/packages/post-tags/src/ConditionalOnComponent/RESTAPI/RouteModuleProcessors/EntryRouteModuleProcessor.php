<?php

declare(strict_types=1);

namespace PoPSchema\PostTags\ConditionalOnComponent\RESTAPI\RouteModuleProcessors;

use PoP\Root\App;
use PoP\API\Response\Schemes as APISchemes;
use PoP\RESTAPI\RouteModuleProcessors\AbstractRESTEntryRouteModuleProcessor;
use PoP\Root\Routing\RequestNature;
use PoPSchema\Posts\Component as PostsComponent;
use PoPSchema\Posts\ComponentConfiguration as PostsComponentConfiguration;
use PoPSchema\PostTags\Component;
use PoPSchema\PostTags\ComponentConfiguration;
use PoPSchema\PostTags\ConditionalOnComponent\API\ModuleProcessors\PostTagFieldDataloadModuleProcessor;
use PoPSchema\PostTags\ConditionalOnComponent\API\ModuleProcessors\TagPostFieldDataloadModuleProcessor;
use PoPSchema\PostTags\TypeAPIs\PostTagTypeAPIInterface;
use PoPSchema\Tags\Routing\RequestNature as TagRequestNature;

class EntryRouteModuleProcessor extends AbstractRESTEntryRouteModuleProcessor
{
    private ?PostTagTypeAPIInterface $postTagTypeAPI = null;

    final public function setPostTagTypeAPI(PostTagTypeAPIInterface $postTagTypeAPI): void
    {
        $this->postTagTypeAPI = $postTagTypeAPI;
    }
    final protected function getPostTagTypeAPI(): PostTagTypeAPIInterface
    {
        return $this->postTagTypeAPI ??= $this->instanceManager->getInstance(PostTagTypeAPIInterface::class);
    }

    protected function getInitialRESTFields(): string
    {
        return 'id|name|count|url';
    }

    /**
     * @return array<string, array<array>>
     */
    public function getModulesVarsPropertiesByNature(): array
    {
        $ret = array();
        $ret[TagRequestNature::TAG][] = [
            'module' => [
                PostTagFieldDataloadModuleProcessor::class,
                PostTagFieldDataloadModuleProcessor::MODULE_DATALOAD_RELATIONALFIELDS_TAG,
                [
                    'fields' => !empty(App::getState('query')) ?
                        App::getState('query') :
                        $this->getRESTFields()
                ]
            ],
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
     * @return array<string, array<string, array<array>>>
     */
    public function getModulesVarsPropertiesByNatureAndRoute(): array
    {
        $ret = array();
        /** @var ComponentConfiguration */
        $componentConfiguration = App::getComponent(Component::class)->getConfiguration();
        $routemodules = array(
            $componentConfiguration->getPostTagsRoute() => [
                PostTagFieldDataloadModuleProcessor::class,
                PostTagFieldDataloadModuleProcessor::MODULE_DATALOAD_RELATIONALFIELDS_TAGLIST,
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
        /** @var PostsComponentConfiguration */
        $componentConfiguration = App::getComponent(PostsComponent::class)->getConfiguration();
        $routemodules = array(
            $componentConfiguration->getPostsRoute() => [
                TagPostFieldDataloadModuleProcessor::class,
                TagPostFieldDataloadModuleProcessor::MODULE_DATALOAD_RELATIONALFIELDS_TAGPOSTLIST,
                [
                    'fields' => !empty(App::getState('query')) ?
                        App::getState('query') :
                        $this->getRESTFields()
                    ]
                ],
        );
        foreach ($routemodules as $route => $module) {
            $ret[TagRequestNature::TAG][$route][] = [
                'module' => $module,
                'conditions' => [
                    'scheme' => APISchemes::API,
                    'datastructure' => $this->getRestDataStructureFormatter()->getName(),
                    'routing' => [
                        'taxonomy-name' => $this->getPostTagTypeAPI()->getPostTagTaxonomyName(),
                    ],
                ],
            ];
        }
        return $ret;
    }
}
