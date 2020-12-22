<?php
/**
Helper functions, they have the same logic as the original javascript helper file wp-content/plugins/pop-engine-webplatform/js/helpers.handlebars.js
*/
class PoP_ServerSide_LabelsHelperCallers
{
    public static function labelize($strings, $label, $options)
    {
        global $pop_serverside_labelshelpers;
        return $pop_serverside_labelshelpers->labelize($strings, $label, $options);
    }
}

/**
 * Registration
 */
PoP_SSR_CLI_HelperRegistration::register(PoP_ServerSide_LabelsHelperCallers::class);
