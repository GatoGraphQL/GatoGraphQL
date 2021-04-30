<?php

declare(strict_types=1);

namespace PoPSchema\Users\ConditionalOnComponent\RESTAPI\RouteModuleProcessors;

use PoP\API\Facades\FieldQueryConvertorFacade;
use PoP\API\Response\Schemes as APISchemes;
use PoP\ComponentModel\State\ApplicationState;
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\RESTAPI\RouteModuleProcessors\AbstractRESTEntryRouteModuleProcessor;
use PoP\Routing\RouteNatures;
use PoPSchema\Users\Routing\RouteNatures as UserRouteNatures;
use PoPSchema\Users\ModuleProcessors\FieldDataloadModuleProcessor;
use PoPSchema\Users\ComponentConfiguration;

class EntryRouteModuleProcessor extends AbstractRESTEntryRouteModuleProcessor
{
    public const HOOK_REST_FIELDS = __CLASS__ . ':RESTFields';

    private static ?string $restFieldsQuery = null;
    private static ?array $restFields = null;
    public static function getRESTFields(): array
    {
        if (is_null(self::$restFields)) {
            $fieldQueryConvertor = FieldQueryConvertorFacade::getInstance();
            $fieldQuerySet = $fieldQueryConvertor->convertAPIQuery(self::getRESTFieldsQuery());
            self::$restFields = $fieldQuerySet->getRequestedFieldQuery();
        }
        return self::$restFields;
    }
    public static function getRESTFieldsQuery(): string
    {
        if (is_null(self::$restFieldsQuery)) {
            $restFieldsQuery = 'id|name|url';
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
        $ret[UserRouteNatures::USER][] = [
            'module' => [
                FieldDataloadModuleProcessor::class,
                FieldDataloadModuleProcessor::MODULE_DATALOAD_RELATIONALFIELDS_SINGLEUSER,
                [
                    'fields' => isset($vars['query']) ?
                        $vars['query'] :
                        self::getRESTFields()
                ]
            ],
            'conditions' => [
                'scheme' => APISchemes::API,
                'datastructure' => $this->restDataStructureFormatter->getName(),
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
            ComponentConfiguration::getUsersRoute() => [
                FieldDataloadModuleProcessor::class,
                FieldDataloadModuleProcessor::MODULE_DATALOAD_RELATIONALFIELDS_USERLIST,
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
        return $ret;
    }
}
