<?php
/**
Helper functions, they have the same logic as the original javascript helper file wp-content/plugins/pop-engine-webplatform/js/helpers.handlebars.js
*/
class PoP_ServerSide_StatusHelperCallers
{
    public static function statusLabel($status, $options)
    {
        global $pop_serverside_statushelpers;
        return $pop_serverside_statushelpers->statusLabel($status, $options);
    }
}

/**
 * Registration
 */
PoP_SSR_CLI_HelperRegistration::register(PoP_ServerSide_StatusHelperCallers::class);
