<?php

declare(strict_types=1);

namespace PoPAPI\API;

class Environment
{
    public final const USE_SCHEMA_DEFINITION_CACHE = 'USE_SCHEMA_DEFINITION_CACHE';
    public final const SKIP_EXPOSING_GLOBAL_FIELDS_IN_FULL_SCHEMA = 'SKIP_EXPOSING_GLOBAL_FIELDS_IN_FULL_SCHEMA';
    public final const SORT_FULL_SCHEMA_ALPHABETICALLY = 'SORT_FULL_SCHEMA_ALPHABETICALLY';
    public final const DISABLE_API = 'DISABLE_API';
    public final const ENABLE_SETTING_NAMESPACING_BY_URL_PARAM = 'ENABLE_SETTING_NAMESPACING_BY_URL_PARAM';
    public final const ADD_FULLSCHEMA_FIELD_TO_SCHEMA = 'ADD_FULLSCHEMA_FIELD_TO_SCHEMA';
    public final const ENABLE_PASSING_PERSISTED_QUERY_NAME_VIA_URL_PARAM = 'ENABLE_PASSING_PERSISTED_QUERY_NAME_VIA_URL_PARAM';

    public static function disableAPI(): bool
    {
        $envValue = getenv(self::DISABLE_API);
        return $envValue !== false ? strtolower($envValue) === "true" : false;
    }

    public static function enableSettingNamespacingByURLParam(): bool
    {
        $envValue = getenv(self::ENABLE_SETTING_NAMESPACING_BY_URL_PARAM);
        return $envValue !== false ? strtolower($envValue) === "true" : false;
    }
}
