<?php
/**
Helper functions, they have the same logic as the original javascript helper file wp-content/plugins/pop-engine-webplatform/js/helpers.handlebars.js
*/
class PoP_ServerSide_ShowMoreHelperCallers
{
    public static function showmore($str, $options)
    {
        global $pop_serverside_showmorehelpers;
        return $pop_serverside_showmorehelpers->showmore($str, $options);
    }
}

/**
 * Registration
 */
PoP_SSR_CLI_HelperRegistration::register(PoP_ServerSide_ShowMoreHelperCallers::class);
