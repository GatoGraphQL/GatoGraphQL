<?php
/**
Helper functions, they have the same logic as the original javascript helper file wp-content/plugins/pop-engine-webplatform/js/helpers.handlebars.js
*/
class PoP_ServerSide_CompareHelperCallers
{
    public static function compare($lvalue, $rvalue, $options)
    {
        global $pop_serverside_comparehelpers;
        return $pop_serverside_comparehelpers->compare($lvalue, $rvalue, $options);
    }
}

/**
 * Registration
 */
PoP_SSR_CLI_HelperRegistration::register(PoP_ServerSide_CompareHelperCallers::class);
