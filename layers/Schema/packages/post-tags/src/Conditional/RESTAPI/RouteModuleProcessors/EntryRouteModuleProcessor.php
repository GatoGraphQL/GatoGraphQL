<?php

declare(strict_types=1);

namespace PoPSchema\PostTags\Conditional\RESTAPI\RouteModuleProcessors;

use PoP\API\Facades\FieldQueryConvertorFacade;
use PoP\API\Response\Schemes as APISchemes;
use PoP\ComponentModel\State\ApplicationState;
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\RESTAPI\RouteModuleProcessors\AbstractRESTEntryRouteModuleProcessor;
use PoP\Routing\RouteNatures;
use PoPSchema\PostTags\Facades\PostTagTypeAPIFacade;
use PoPSchema\PostTags\ModuleProcessors\PostTagFieldDataloadModuleProcessor;
use PoPSchema\PostTags\ModuleProcessors\TagPostFieldDataloadModuleProcessor;
use PoPSchema\Tags\Routing\RouteNatures as TagRouteNatures;

class EntryRouteModuleProcessor extends AbstractRESTEntryRouteModuleProcessor
{
    public const HOOK_REST_FIELDS = __CLASS__ . ':RESTFields';

    private static ?string $restFieldsQuery = null;
    private static ?array $restFields = null;
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
                PostTagFieldDataloadModuleProcessor::class,
                PostTagFieldDataloadModuleProcessor::MODULE_DATALOAD_RELATIONALFIELDS_TAG,
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
                PostTagFieldDataloadModuleProcessor::class,
                PostTagFieldDataloadModuleProcessor::MODULE_DATALOAD_RELATIONALFIELDS_TAGLIST,
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
                TagPostFieldDataloadModuleProcessor::class,
                TagPostFieldDataloadModuleProcessor::MODULE_DATALOAD_RELATIONALFIELDS_TAGPOSTLIST,
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
                    'datastructure' => $this->restDataStructureFormatter->getName(),
                    'routing-state' => [
                        'taxonomy-name' => $postTagTypeAPI->getPostTagTaxonomyName(),
                    ],
                ],
            ];
        }
        return $ret;
    }
}
