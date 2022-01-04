<?php

declare(strict_types=1);

namespace PoPSchema\PostTags\ConditionalOnComponent\RESTAPI\RouteModuleProcessors;

use PoP\API\Response\Schemes as APISchemes;
use PoP\ComponentModel\State\ApplicationState;
use PoP\RESTAPI\RouteModuleProcessors\AbstractRESTEntryRouteModuleProcessor;
use PoP\Routing\RouteNatures;
use PoPSchema\Posts\Component as PostsComponent;
use PoPSchema\Posts\ComponentConfiguration as PostsComponentConfiguration;
use PoPSchema\PostTags\Component;
use PoPSchema\PostTags\ComponentConfiguration;
use PoPSchema\PostTags\ConditionalOnComponent\API\ModuleProcessors\PostTagFieldDataloadModuleProcessor;
use PoPSchema\PostTags\ConditionalOnComponent\API\ModuleProcessors\TagPostFieldDataloadModuleProcessor;
use PoPSchema\PostTags\TypeAPIs\PostTagTypeAPIInterface;
use PoPSchema\Tags\Routing\RouteNatures as TagRouteNatures;

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
        $vars = ApplicationState::getVars();
        $ret[TagRouteNatures::TAG][] = [
            'module' => [
                PostTagFieldDataloadModuleProcessor::class,
                PostTagFieldDataloadModuleProcessor::MODULE_DATALOAD_RELATIONALFIELDS_TAG,
                [
                    'fields' => isset($vars['query']) ?
                        $vars['query'] :
                        $this->getRESTFields()
                ]
            ],
            'conditions' => [
                'scheme' => APISchemes::API,
                'datastructure' => $this->getRestDataStructureFormatter()->getName(),
                'routing-state' => [
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
        $vars = ApplicationState::getVars();
        $routemodules = array(
            ComponentConfiguration::getPostTagsRoute() => [
                PostTagFieldDataloadModuleProcessor::class,
                PostTagFieldDataloadModuleProcessor::MODULE_DATALOAD_RELATIONALFIELDS_TAGLIST,
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
        $routemodules = array(
            PostsComponentConfiguration::getPostsRoute() => [
                TagPostFieldDataloadModuleProcessor::class,
                TagPostFieldDataloadModuleProcessor::MODULE_DATALOAD_RELATIONALFIELDS_TAGPOSTLIST,
                [
                    'fields' => isset($vars['query']) ?
                        $vars['query'] :
                        $this->getRESTFields()
                    ]
                ],
        );
        foreach ($routemodules as $route => $module) {
            $ret[TagRouteNatures::TAG][$route][] = [
                'module' => $module,
                'conditions' => [
                    'scheme' => APISchemes::API,
                    'datastructure' => $this->getRestDataStructureFormatter()->getName(),
                    'routing-state' => [
                        'taxonomy-name' => $this->getPostTagTypeAPI()->getPostTagTaxonomyName(),
                    ],
                ],
            ];
        }
        return $ret;
    }
}
