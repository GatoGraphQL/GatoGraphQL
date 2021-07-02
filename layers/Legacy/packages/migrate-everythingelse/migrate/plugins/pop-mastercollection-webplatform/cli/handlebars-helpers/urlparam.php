<?php
/**
Helper functions, they have the same logic as the original javascript helper file wp-content/plugins/pop-engine-webplatform/js/helpers.handlebars.js
*/
class PoP_ServerSide_URLParamHelperCallers
{
    public static function addQueryArg($param, $value, $url, $options)
    {
        global $pop_serverside_urlparamhelpers;
        return $pop_serverside_urlparamhelpers->addQueryArg($param, $value, $url, $options);
    }
}

/**
 * Registration
 */
PoP_SSR_CLI_HelperRegistration::register(PoP_ServerSide_URLParamHelperCallers::class);
