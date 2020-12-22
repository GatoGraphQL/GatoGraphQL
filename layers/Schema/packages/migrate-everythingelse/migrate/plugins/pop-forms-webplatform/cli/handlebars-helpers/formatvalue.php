<?php
/**
Helper functions, they have the same logic as the original javascript helper file wp-content/plugins/pop-engine-webplatform/js/helpers.handlebars.js
 */
class PoP_ServerSide_FormatValueHelperCallers
{
    public static function formatValue($value, $format, $options)
    {
        global $pop_serverside_formatvaluehelpers;
        return $pop_serverside_formatvaluehelpers->formatValue($value, $format, $options);
    }
}

/**
 * Registration
 */
PoP_SSR_CLI_HelperRegistration::register(PoP_ServerSide_FormatValueHelperCallers::class);
