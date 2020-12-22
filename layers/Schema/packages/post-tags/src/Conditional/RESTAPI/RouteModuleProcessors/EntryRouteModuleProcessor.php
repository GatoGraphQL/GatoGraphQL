<?php

declare(strict_types=1);

namespace PoPSchema\PostTags\Conditional\RESTAPI\RouteModuleProcessors;

use PoP\ModuleRouting\AbstractEntryRouteModuleProcessor;
use PoP\ComponentModel\State\ApplicationState;
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\API\Facades\FieldQueryConvertorFacade;
use PoPSchema\PostTags\Facades\PostTagTypeAPIFacade;
use PoP\Routing\RouteNatures;
use PoPSchema\Tags\Routing\RouteNatures as TagRouteNatures;
use PoP\RESTAPI\DataStructureFormatters\RESTDataStructureFormatter;
use PoP\API\Response\Schemes as APISchemes;

class EntryRouteModuleProcessor extends AbstractEntryRouteModuleProcessor
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
        $postTagTypeAPI = PostTagTypeAPIFacade::getInstance();
        $ret[TagRouteNatures::TAG][] = [
            'module' => [
                \PoP_PostTags_Module_Processor_FieldDataloads::class,
                \PoP_PostTags_Module_Processor_FieldDataloads::MODULE_DATALOAD_RELATIONALFIELDS_TAG,
                [
                    'fields' => isset($vars['query']) ?
                        $vars['query'] :
                        self::getRESTFields()
                ]
            ],
            'conditions' => [
                'scheme' => APISchemes::API,
                'datastructure' => RESTDataStructureFormatter::getName(),
                'routing-state' => [
                    'taxonomy-name' => $postTagTypeAPI->getPostTagTaxonomyName(),
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
        $postTagTypeAPI = PostTagTypeAPIFacade::getInstance();
        $routemodules = array(
            POP_POSTTAGS_ROUTE_POSTTAGS => [
                \PoP_PostTags_Module_Processor_FieldDataloads::class,
                \PoP_PostTags_Module_Processor_FieldDataloads::MODULE_DATALOAD_RELATIONALFIELDS_TAGLIST,
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
                    'datastructure' => RESTDataStructureFormatter::getName(),
                ],
            ];
        }
        $routemodules = array(
            POP_POSTS_ROUTE_POSTS => [
                \PoP_Taxonomies_Posts_Module_Processor_FieldDataloads::class,
                \PoP_Taxonomies_Posts_Module_Processor_FieldDataloads::MODULE_DATALOAD_RELATIONALFIELDS_TAGPOSTLIST,
                [
                    'fields' => isset($vars['query']) ?
                        $vars['query'] :
                        self::getRESTFields()
                    ]
                ],
        );
        foreach ($routemodules as $route => $module) {
            $ret[TagRouteNatures::TAG][$route][] = [
                'module' => $module,
                'conditions' => [
                    'scheme' => APISchemes::API,
                    'datastructure' => RESTDataStructureFormatter::getName(),
                    'routing-state' => [
                        'taxonomy-name' => $postTagTypeAPI->getPostTagTaxonomyName(),
                    ],
                ],
            ];
        }
        return $ret;
    }
}
