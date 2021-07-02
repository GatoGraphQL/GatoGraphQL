<?php
/**
Helper functions, they have the same logic as the original javascript helper file wp-content/plugins/pop-engine-webplatform/js/helpers.handlebars.js
 */
class PoP_ServerSide_StatusHelpers
{
    public function statusLabel($status, $options)
    {
        $statusSettings = PoP_HTMLCSSPlatform_ConfigurationUtils::getStatusSettings();
        $ret = '<span class="label '.$statusSettings['class'][$status].' label-'.$status.'">'.$statusSettings['text'][$status].'</span>';

        return new LS($ret);
    }
}

/**
 * Initialization
 */
global $pop_serverside_statushelpers;
$pop_serverside_statushelpers = new PoP_ServerSide_StatusHelpers();
