<?php
/**
Helper functions, they have the same logic as the original javascript helper file wp-content/plugins/pop-engine-webplatform/js/helpers.handlebars.js
*/
class PoP_ServerSide_ModHelperCallers
{
    public static function mod($lvalue, $rvalue, $options)
    {
        global $pop_serverside_modhelpers;
        return $pop_serverside_modhelpers->mod($lvalue, $rvalue, $options);
    }
}

/**
 * Registration
 */
PoP_SSR_CLI_HelperRegistration::register(PoP_ServerSide_ModHelperCallers::class);
