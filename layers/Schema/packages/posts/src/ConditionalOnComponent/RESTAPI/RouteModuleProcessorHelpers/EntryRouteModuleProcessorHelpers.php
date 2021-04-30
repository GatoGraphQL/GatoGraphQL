<?php

declare(strict_types=1);

namespace PoPSchema\Posts\ConditionalOnComponent\RESTAPI\RouteModuleProcessorHelpers;

use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\API\Facades\FieldQueryConvertorFacade;

class EntryRouteModuleProcessorHelpers
{
    public const HOOK_REST_FIELDS = __CLASS__ . ':RESTFields';
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
            self::$restFieldsQuery = (string) HooksAPIFacade::getInstance()->applyFilters(
                self::HOOK_REST_FIELDS,
                \PoPSchema\CustomPosts\ConditionalOnComponent\RESTAPI\RouteModuleProcessorHelpers\EntryRouteModuleProcessorHelpers::getRESTFieldsQuery()
            );
        }
        return self::$restFieldsQuery;
    }
}
