<?php
/**
Helper functions, they have the same logic as the original javascript helper file wp-content/plugins/pop-engine-webplatform/js/helpers.handlebars.js
*/
class PoP_ServerSide_DBObjectHelperCallers
{
    public static function withDBObject($dbKey, $dbObjectID, $options)
    {
        global $pop_serverside_dbobjecthelpers;
        return $pop_serverside_dbobjecthelpers->withDBObject($dbKey, $dbObjectID, $options);
    }
}

/**
 * Registration
 */
PoP_SSR_CLI_HelperRegistration::register(PoP_ServerSide_DBObjectHelperCallers::class);
