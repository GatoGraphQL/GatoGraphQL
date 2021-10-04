<?php

declare(strict_types=1);

namespace PoPSchema\PostTags\ConditionalOnComponent\API\RouteModuleProcessors;

use PoP\API\Response\Schemes as APISchemes;
use PoP\ModuleRouting\AbstractEntryRouteModuleProcessor;
use PoP\Routing\RouteNatures;
use PoPSchema\Posts\ComponentConfiguration as PostsComponentConfiguration;
use PoPSchema\PostTags\ComponentConfiguration;
use PoPSchema\PostTags\ConditionalOnComponent\API\ModuleProcessors\PostTagFieldDataloadModuleProcessor;
use PoPSchema\PostTags\ConditionalOnComponent\API\ModuleProcessors\TagPostFieldDataloadModuleProcessor;
use PoPSchema\PostTags\TypeAPIs\PostTagTypeAPIInterface;
use PoPSchema\Tags\Routing\RouteNatures as TagRouteNatures;
use Symfony\Contracts\Service\Attribute\Required;

class EntryRouteModuleProcessor extends AbstractEntryRouteModuleProcessor
{
    protected PostTagTypeAPIInterface $postTagTypeAPI;

    #[Required]
    final public function autowireEntryRouteModuleProcessor(
        PostTagTypeAPIInterface $postTagTypeAPI,
    ): void {
        $this->postTagTypeAPI = $postTagTypeAPI;
    }

    /**
     * @return array<string, array<array>>
     */
    public function getModulesVarsPropertiesByNature(): array
    {
        $ret = array();
        $ret[TagRouteNatures::TAG][] = [
            'module' => [PostTagFieldDataloadModuleProcessor::class, PostTagFieldDataloadModuleProcessor::MODULE_DATALOAD_RELATIONALFIELDS_TAG],
            'conditions' => [
                'scheme' => APISchemes::API,
                'routing-state' => [
                    'taxonomy-name' => $this->postTagTypeAPI->getPostTagTaxonomyName(),
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
        $routemodules = array(
            ComponentConfiguration::getPostTagsRoute() => [PostTagFieldDataloadModuleProcessor::class, PostTagFieldDataloadModuleProcessor::MODULE_DATALOAD_RELATIONALFIELDS_TAGLIST],
        );
        foreach ($routemodules as $route => $module) {
            $ret[RouteNatures::STANDARD][$route][] = [
                'module' => $module,
                'conditions' => [
                    'scheme' => APISchemes::API,
                ],
            ];
        }
        $routemodules = array(
            PostsComponentConfiguration::getPostsRoute() => [TagPostFieldDataloadModuleProcessor::class, TagPostFieldDataloadModuleProcessor::MODULE_DATALOAD_RELATIONALFIELDS_TAGPOSTLIST],
        );
        foreach ($routemodules as $route => $module) {
            $ret[TagRouteNatures::TAG][$route][] = [
                'module' => $module,
                'conditions' => [
                    'scheme' => APISchemes::API,
                    'routing-state' => [
                        'taxonomy-name' => $this->postTagTypeAPI->getPostTagTaxonomyName(),
                    ],
                ],
            ];
        }
        return $ret;
    }
}
