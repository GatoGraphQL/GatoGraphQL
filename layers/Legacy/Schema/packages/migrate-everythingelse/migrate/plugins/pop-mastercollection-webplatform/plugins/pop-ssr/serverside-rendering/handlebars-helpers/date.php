<?php
/**
Helper functions, they have the same logic as the original javascript helper file wp-content/plugins/pop-engine-webplatform/js/helpers.handlebars.js
 */
class PoP_ServerSide_DateHelpers
{
    public function ondate($date, $options)
    {
        return new LS(sprintf(PoP_HTMLCSSPlatform_ConfigurationUtils::getOndateString(), $date));
    }
}

/**
 * Initialization
 */
global $pop_serverside_datehelpers;
$pop_serverside_datehelpers = new PoP_ServerSide_DateHelpers();
