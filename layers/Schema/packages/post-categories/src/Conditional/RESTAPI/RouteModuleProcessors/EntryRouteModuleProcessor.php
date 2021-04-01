<?php

declare(strict_types=1);

namespace PoPSchema\PostCategories\Conditional\RESTAPI\RouteModuleProcessors;

use PoP\API\Facades\FieldQueryConvertorFacade;
use PoP\API\Response\Schemes as APISchemes;
use PoP\ComponentModel\State\ApplicationState;
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\RESTAPI\RouteModuleProcessors\AbstractRESTEntryRouteModuleProcessor;
use PoP\Routing\RouteNatures;
use PoPSchema\PostCategories\Facades\PostCategoryTypeAPIFacade;
use PoPSchema\PostCategories\ModuleProcessors\PostCategoryFieldDataloadModuleProcessor;
use PoPSchema\PostCategories\ModuleProcessors\CategoryPostFieldDataloadModuleProcessor;
use PoPSchema\Categories\Routing\RouteNatures as CategoryRouteNatures;

class EntryRouteModuleProcessor extends AbstractRESTEntryRouteModuleProcessor
{
    public const HOOK_REST_FIELDS = __CLASS__ . ':RESTFields';

    private static $restFieldsQuery;
    private static $restFields;
    public static function getRESTFields(): array
    {
        if (is_null(self::$restFields)) {
            self::$restFields = self::getRESTFieldsQuery();
            if (is_string(self::$restFields)) {
                $fieldQueryConvertor = FieldQueryConvertorFacade::getInstance();
                $fieldQuerySet = $fieldQueryConvertor->convertAPIQuery(self::$restFields);
                self::$restFields = $fieldQuerySet->getRequestedFieldQuery();
            }
        }
        return self::$restFields;
    }
    public static function getRESTFieldsQuery(): string
    {
        if (is_null(self::$restFieldsQuery)) {
            $restFieldsQuery = 'id|name|count|url';
            self::$restFieldsQuery = (string) HooksAPIFacade::getInstance()->applyFilters(
                self::HOOK_REST_FIELDS,
                $restFieldsQuery
            );
        }
        return self::$restFieldsQuery;
    }

    /**
     * @return array<string, array<array>>
     */
    public function getModulesVarsPropertiesByNature(): array
    {
        $ret = array();
        $vars = ApplicationState::getVars();
        $postCategoryTypeAPI = PostCategoryTypeAPIFacade::getInstance();
        $ret[CategoryRouteNatures::CATEGORY][] = [
            'module' => [
                PostCategoryFieldDataloadModuleProcessor::class,
                PostCategoryFieldDataloadModuleProcessor::MODULE_DATALOAD_RELATIONALFIELDS_CATEGORY,
                [
                    'fields' => isset($vars['query']) ?
                        $vars['query'] :
                        self::getRESTFields()
                ]
            ],
            'conditions' => [
                'scheme' => APISchemes::API,
                'datastructure' => $this->restDataStructureFormatter->getName(),
                'routing-state' => [
                    'taxonomy-name' => $postCategoryTypeAPI->getPostCategoryTaxonomyName(),
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
        $postCategoryTypeAPI = PostCategoryTypeAPIFacade::getInstance();
        $routemodules = array(
            POP_POSTCATEGORIES_ROUTE_POSTCATEGORIES => [
                PostCategoryFieldDataloadModuleProcessor::class,
                PostCategoryFieldDataloadModuleProcessor::MODULE_DATALOAD_RELATIONALFIELDS_CATEGORYLIST,
                [
                    'fields' => isset($vars['query']) ?
                        $vars['query'] :
                        self::getRESTFields()
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
            POP_POSTS_ROUTE_POSTS => [
                CategoryPostFieldDataloadModuleProcessor::class,
                CategoryPostFieldDataloadModuleProcessor::MODULE_DATALOAD_RELATIONALFIELDS_CATEGORYPOSTLIST,
                [
                    'fields' => isset($vars['query']) ?
                        $vars['query'] :
                        self::getRESTFields()
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
                        'taxonomy-name' => $postCategoryTypeAPI->getPostCategoryTaxonomyName(),
                    ],
                ],
            ];
        }
        return $ret;
    }
}
