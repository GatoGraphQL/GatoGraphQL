<?php
/**
Helper functions, they have the same logic as the original javascript helper file wp-content/plugins/pop-engine-webplatform/js/helpers.handlebars.js
*/
class PoP_ServerSide_MultiLayoutHelperCallers
{
    public static function withConditionalOnDataFieldModule($dbKey, $dbObjectID, $conditionDataFieldModules, $defaultModule, $context, $options)
    {
        global $pop_serverside_multilayouthelpers;
        return $pop_serverside_multilayouthelpers->withConditionalOnDataFieldModule($dbKey, $dbObjectID, $conditionDataFieldModules, $defaultModule, $context, $options);
    }

    public static function layoutLabel($dbKey, $dbObject, $options)
    {
        global $pop_serverside_multilayouthelpers;
        return $pop_serverside_multilayouthelpers->layoutLabel($dbKey, $dbObject, $options);
    }
}

/**
 * Registration
 */
PoP_SSR_CLI_HelperRegistration::register(PoP_ServerSide_MultiLayoutHelperCallers::class);
