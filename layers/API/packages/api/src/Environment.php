<?php

declare(strict_types=1);

namespace PoP\API;

class Environment
{
    public const USE_SCHEMA_DEFINITION_CACHE = 'USE_SCHEMA_DEFINITION_CACHE';
    public const EXECUTE_QUERY_BATCH_IN_STRICT_ORDER = 'EXECUTE_QUERY_BATCH_IN_STRICT_ORDER';
    public const ENABLE_EMBEDDABLE_FIELDS = 'ENABLE_EMBEDDABLE_FIELDS';
    public const ENABLE_MUTATIONS = 'ENABLE_MUTATIONS';
    public const OVERRIDE_REQUEST_URI = 'OVERRIDE_REQUEST_URI';
    public const ADD_GLOBAL_FIELDS_TO_SCHEMA = 'ADD_GLOBAL_FIELDS_TO_SCHEMA';

    public static function disableAPI(): bool
    {
        return getenv('DISABLE_API') !== false ? strtolower(getenv('DISABLE_API')) == "true" : false;
    }

    public static function enableSettingNamespacingByURLParam(): bool
    {
        return getenv('ENABLE_SETTING_NAMESPACING_BY_URL_PARAM') !== false ? strtolower(getenv('ENABLE_SETTING_NAMESPACING_BY_URL_PARAM')) == "true" : false;
    }
}
