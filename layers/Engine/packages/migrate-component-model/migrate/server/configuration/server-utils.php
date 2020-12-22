<?php
namespace PoP\ComponentModel\Server;

use PoP\ComponentModel\Configuration\Request;

class Utils
{
    public static function enableVersionByParams()
    {
        return getenv('ENABLE_VERSION_BY_PARAMS') !== false ? strtolower(getenv('ENABLE_VERSION_BY_PARAMS')) == "true" : false;
    }

    // public static function failIfSubcomponentTypeResolverUndefined()
    // {
    //     return getenv('FAIL_IF_SUBCOMPONENT_TYPERESOLVER_IS_UNDEFINED') !== false ? strtolower(getenv('FAIL_IF_SUBCOMPONENT_TYPERESOLVER_IS_UNDEFINED')) == "true" : false;
    // }

    public static function enableExtraRoutesByParams()
    {
        return getenv('ENABLE_EXTRA_ROUTES_BY_PARAMS') !== false ? strtolower(getenv('ENABLE_EXTRA_ROUTES_BY_PARAMS')) == "true" : false;
    }

    public static function enableShowLogs()
    {
        return getenv('ENABLE_SHOW_LOGS') !== false ? strtolower(getenv('ENABLE_SHOW_LOGS')) == "true" : false;
    }

    public static function showTracesInResponse()
    {
        return getenv('SHOW_TRACES_IN_RESPONSE') !== false ? strtolower(getenv('SHOW_TRACES_IN_RESPONSE')) == "true" : false;
    }

    /**
     * Use 'modules' or 'm' in the JS context. Used to compress the file size in PROD
     */
    public static function compactResponseJsonKeys()
    {
        // Do not compact if not mangled
        if (!Request::isMangled()) {
            return false;
        }

        return getenv('COMPACT_RESPONSE_JSON_KEYS') !== false ? strtolower(getenv('COMPACT_RESPONSE_JSON_KEYS')) == "true" : false;
    }

    public static function externalSitesRunSameSoftware()
    {
        return getenv('EXTERNAL_SITES_RUN_SAME_SOFTWARE') !== false ? strtolower(getenv('EXTERNAL_SITES_RUN_SAME_SOFTWARE')) == "true" : false;
    }

    public static function disableCustomCMSCode()
    {
        return getenv('DISABLE_CUSTOM_CMS_CODE') !== false ? strtolower(getenv('DISABLE_CUSTOM_CMS_CODE')) == "true" : false;
    }
}
