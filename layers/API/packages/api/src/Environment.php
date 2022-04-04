<?php

declare(strict_types=1);

namespace PoPAPI\API;

class Environment
{
    public final const USE_SCHEMA_DEFINITION_CACHE = 'USE_SCHEMA_DEFINITION_CACHE';
    public final const EXECUTE_QUERY_BATCH_IN_STRICT_ORDER = 'EXECUTE_QUERY_BATCH_IN_STRICT_ORDER';
    public final const ENABLE_EMBEDDABLE_FIELDS = 'ENABLE_EMBEDDABLE_FIELDS';
    public final const SKIP_EXPOSING_GLOBAL_FIELDS_IN_FULL_SCHEMA = 'SKIP_EXPOSING_GLOBAL_FIELDS_IN_FULL_SCHEMA';
    public final const SORT_FULL_SCHEMA_ALPHABETICALLY = 'SORT_FULL_SCHEMA_ALPHABETICALLY';
    public final const DISABLE_API = 'DISABLE_API';
    public final const ENABLE_SETTING_NAMESPACING_BY_URL_PARAM = 'ENABLE_SETTING_NAMESPACING_BY_URL_PARAM';
    public final const ADD_FULLSCHEMA_FIELD_TO_SCHEMA = 'ADD_FULLSCHEMA_FIELD_TO_SCHEMA';

    public static function disableAPI(): bool
    {
        return getenv(self::DISABLE_API) !== false ? strtolower(getenv(self::DISABLE_API)) === "true" : false;
    }

    public static function enableSettingNamespacingByURLParam(): bool
    {
        return getenv(self::ENABLE_SETTING_NAMESPACING_BY_URL_PARAM) !== false ? strtolower(getenv(self::ENABLE_SETTING_NAMESPACING_BY_URL_PARAM)) === "true" : false;
    }
}
