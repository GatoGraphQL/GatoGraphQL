<?php
/**
Helper functions, they have the same logic as the original javascript helper file wp-content/plugins/pop-coreprocessors/js/helpers.handlebars.js
*/
class PoP_ServerSide_LatestCountHelperCallers
{
    public static function latestCountTargets($dbObject, $options)
    {
        global $pop_serverside_latestcounthelpers;
        return $pop_serverside_latestcounthelpers->latestCountTargets($dbObject, $options);
    }
}

/**
 * Registration
 */
PoP_SSR_CLI_HelperRegistration::register(PoP_ServerSide_LatestCountHelperCallers::class);
