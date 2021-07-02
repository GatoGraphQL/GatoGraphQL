<?php
/**
Helper functions, they have the same logic as the original javascript helper file wp-content/plugins/pop-coreprocessors/js/helpers.handlebars.js
 */
class PoP_Forms_ServerSide_FormcomponentsHelperCallers
{
    public static function formcomponentValue($value, $dbObject, $dbObjectField, $defaultValue, $options)
    {
        global $pop_serverside_formcomponentshelpers;
        return $pop_serverside_formcomponentshelpers->formcomponentValue($value, $dbObject, $dbObjectField, $defaultValue, $options);
    }
}

/**
 * Registration
 */
PoP_SSR_CLI_HelperRegistration::register(PoP_Forms_ServerSide_FormcomponentsHelperCallers::class);
