<?php

declare(strict_types=1);

namespace PoPSchema\Pages\ConditionalOnComponent\RESTAPI\RouteModuleProcessors;

use PoP\API\Facades\FieldQueryConvertorFacade;
use PoP\API\Response\Schemes as APISchemes;
use PoP\ComponentModel\State\ApplicationState;
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\RESTAPI\RouteModuleProcessors\AbstractRESTEntryRouteModuleProcessor;
use PoPSchema\Pages\Routing\RouteNatures;
use PoPSchema\Pages\ModuleProcessors\FieldDataloadModuleProcessor;

class EntryRouteModuleProcessor extends AbstractRESTEntryRouteModuleProcessor
{
    private static ?string $restFieldsQuery = null;
    private static ?array $restFields = null;
    public static function getRESTFields(): array
    {
        if (is_null(self::$restFields)) {
            $restFields = self::getRESTFieldsQuery();
            $fieldQueryConvertor = FieldQueryConvertorFacade::getInstance();
            $fieldQuerySet = $fieldQueryConvertor->convertAPIQuery($restFields);
            self::$restFields = $fieldQuerySet->getRequestedFieldQuery();
        }
        return self::$restFields;
    }
    public static function getRESTFieldsQuery(): string
    {
        if (is_null(self::$restFieldsQuery)) {
            $restFieldsQuery = 'id|title|url|content';
            self::$restFieldsQuery = (string) HooksAPIFacade::getInstance()->applyFilters(
                'Pages:RESTFields',
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
        $ret[RouteNatures::PAGE][] = [
            'module' => [FieldDataloadModuleProcessor::class, FieldDataloadModuleProcessor::MODULE_DATALOAD_RELATIONALFIELDS_PAGE, ['fields' => isset($vars['query']) ? $vars['query'] : self::getRESTFields()]],
            'conditions' => [
                'scheme' => APISchemes::API,
                'datastructure' => $this->restDataStructureFormatter->getName(),
            ],
        ];

        return $ret;
    }
}
