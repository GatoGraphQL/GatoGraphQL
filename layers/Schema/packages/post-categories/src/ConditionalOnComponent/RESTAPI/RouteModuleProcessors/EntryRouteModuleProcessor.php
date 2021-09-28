<?php

declare(strict_types=1);

namespace PoPSchema\PostCategories\ConditionalOnComponent\RESTAPI\RouteModuleProcessors;

use Symfony\Contracts\Service\Attribute\Required;
use PoP\API\Response\Schemes as APISchemes;
use PoP\API\Schema\FieldQueryConvertorInterface;
use PoP\ComponentModel\State\ApplicationState;
use PoP\Hooks\HooksAPIInterface;
use PoP\RESTAPI\DataStructureFormatters\RESTDataStructureFormatter;
use PoP\RESTAPI\RouteModuleProcessors\AbstractRESTEntryRouteModuleProcessor;
use PoP\Routing\RouteNatures;
use PoPSchema\Categories\Routing\RouteNatures as CategoryRouteNatures;
use PoPSchema\PostCategories\ComponentConfiguration;
use PoPSchema\PostCategories\ConditionalOnComponent\API\ModuleProcessors\CategoryPostFieldDataloadModuleProcessor;
use PoPSchema\PostCategories\ConditionalOnComponent\API\ModuleProcessors\PostCategoryFieldDataloadModuleProcessor;
use PoPSchema\PostCategories\Facades\PostCategoryTypeAPIFacade;
use PoPSchema\PostCategories\TypeAPIs\PostCategoryTypeAPIInterface;
use PoPSchema\Posts\ComponentConfiguration as PostsComponentConfiguration;

class EntryRouteModuleProcessor extends AbstractRESTEntryRouteModuleProcessor
{
    protected PostCategoryTypeAPIInterface $postCategoryTypeAPI;

    #[Required]
    public function autowireEntryRouteModuleProcessor(
        PostCategoryTypeAPIInterface $postCategoryTypeAPI,
    ): void {
        $this->postCategoryTypeAPI = $postCategoryTypeAPI;
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
        $ret[CategoryRouteNatures::CATEGORY][] = [
            'module' => [
                PostCategoryFieldDataloadModuleProcessor::class,
                PostCategoryFieldDataloadModuleProcessor::MODULE_DATALOAD_RELATIONALFIELDS_CATEGORY,
                [
                    'fields' => isset($vars['query']) ?
                        $vars['query'] :
                        $this->getRESTFields()
                ]
            ],
            'conditions' => [
                'scheme' => APISchemes::API,
                'datastructure' => $this->restDataStructureFormatter->getName(),
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
        $vars = ApplicationState::getVars();
        $routemodules = array(
            ComponentConfiguration::getPostCategoriesRoute() => [
                PostCategoryFieldDataloadModuleProcessor::class,
                PostCategoryFieldDataloadModuleProcessor::MODULE_DATALOAD_RELATIONALFIELDS_CATEGORYLIST,
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
                    'datastructure' => $this->restDataStructureFormatter->getName(),
                ],
            ];
        }
        $routemodules = array(
            PostsComponentConfiguration::getPostsRoute() => [
                CategoryPostFieldDataloadModuleProcessor::class,
                CategoryPostFieldDataloadModuleProcessor::MODULE_DATALOAD_RELATIONALFIELDS_CATEGORYPOSTLIST,
                [
                    'fields' => isset($vars['query']) ?
                        $vars['query'] :
                        $this->getRESTFields()
                    ]
                ],
        );
        foreach ($routemodules as $route => $module) {
            $ret[CategoryRouteNatures::CATEGORY][$route][] = [
                'module' => $module,
                'conditions' => [
                    'scheme' => APISchemes::API,
                    'datastructure' => $this->restDataStructureFormatter->getName(),
                    'routing-state' => [
                        'taxonomy-name' => $this->postCategoryTypeAPI->getPostCategoryTaxonomyName(),
                    ],
                ],
            ];
        }
        return $ret;
    }
}
