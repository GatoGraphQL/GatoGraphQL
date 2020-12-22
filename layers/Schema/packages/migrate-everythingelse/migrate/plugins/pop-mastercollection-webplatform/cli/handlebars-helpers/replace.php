<?php
/**
Helper functions, they have the same logic as the original javascript helper file wp-content/plugins/pop-engine-webplatform/js/helpers.handlebars.js
*/
class PoP_ServerSide_ReplaceHelperCallers
{
    public static function replace($search, $replace, $options)
    {
        global $pop_serverside_replacehelpers;
        return $pop_serverside_replacehelpers->replace($search, $replace, $options);
    }
}

/**
 * Registration
 */
PoP_SSR_CLI_HelperRegistration::register(PoP_ServerSide_ReplaceHelperCallers::class);
