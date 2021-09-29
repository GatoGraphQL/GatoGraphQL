<?php

declare(strict_types=1);

namespace PoPSchema\PostCategories\ConditionalOnComponent\API\RouteModuleProcessors;

use Symfony\Contracts\Service\Attribute\Required;
use PoP\API\Response\Schemes as APISchemes;
use PoP\ModuleRouting\AbstractEntryRouteModuleProcessor;
use PoP\Routing\RouteNatures;
use PoPSchema\Categories\Routing\RouteNatures as CategoryRouteNatures;
use PoPSchema\PostCategories\ComponentConfiguration;
use PoPSchema\PostCategories\ConditionalOnComponent\API\ModuleProcessors\CategoryPostFieldDataloadModuleProcessor;
use PoPSchema\PostCategories\ConditionalOnComponent\API\ModuleProcessors\PostCategoryFieldDataloadModuleProcessor;
use PoPSchema\PostCategories\TypeAPIs\PostCategoryTypeAPIInterface;
use PoPSchema\Posts\ComponentConfiguration as PostsComponentConfiguration;

class EntryRouteModuleProcessor extends AbstractEntryRouteModuleProcessor
{
    protected PostCategoryTypeAPIInterface $postCategoryTypeAPI;

    #[Required]
    public function autowireEntryRouteModuleProcessor(
        PostCategoryTypeAPIInterface $postCategoryTypeAPI,
    ): void {
        $this->postCategoryTypeAPI = $postCategoryTypeAPI;
    }

    /**
     * @return array<string, array<array>>
     */
    public function getModulesVarsPropertiesByNature(): array
    {
        $ret = array();
        $ret[CategoryRouteNatures::CATEGORY][] = [
            'module' => [PostCategoryFieldDataloadModuleProcessor::class, PostCategoryFieldDataloadModuleProcessor::MODULE_DATALOAD_RELATIONALFIELDS_CATEGORY],
            'conditions' => [
                'scheme' => APISchemes::API,
                'routing-state' => [
                    'taxonomy-name' => $this->postCategoryTypeAPI->getPostCategoryTaxonomyName(),
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
            ComponentConfiguration::getPostCategoriesRoute() => [PostCategoryFieldDataloadModuleProcessor::class, PostCategoryFieldDataloadModuleProcessor::MODULE_DATALOAD_RELATIONALFIELDS_CATEGORYLIST],
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
            PostsComponentConfiguration::getPostsRoute() => [CategoryPostFieldDataloadModuleProcessor::class, CategoryPostFieldDataloadModuleProcessor::MODULE_DATALOAD_RELATIONALFIELDS_CATEGORYPOSTLIST],
        );
        foreach ($routemodules as $route => $module) {
            $ret[CategoryRouteNatures::CATEGORY][$route][] = [
                'module' => $module,
                'conditions' => [
                    'scheme' => APISchemes::API,
                    'routing-state' => [
                        'taxonomy-name' => $this->postCategoryTypeAPI->getPostCategoryTaxonomyName(),
                    ],
                ],
            ];
        }
        return $ret;
    }
}
