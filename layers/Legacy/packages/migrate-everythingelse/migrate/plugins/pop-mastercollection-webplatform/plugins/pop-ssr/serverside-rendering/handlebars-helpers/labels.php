<?php
/**
Helper functions, they have the same logic as the original javascript helper file wp-content/plugins/pop-engine-webplatform/js/helpers.handlebars.js
 */
class PoP_ServerSide_LabelsHelpers
{
    public function labelize($strings, $label, $options)
    {
        $labelize_classes = PoP_HTMLCSSPlatform_ConfigurationUtils::getLabelizeClasses();
        $ret = '';
        $extra_class = '';
        if ($strings) {
            for ($i = 0; $i < count($strings); $i++) {
                $extra_class = $labelize_classes[$strings[$i]] ?? '';
                $ret .= '<span class="label '.$label.' '.$extra_class.'">'.$strings[$i].'</span> ';
            }
        }

        return new LS($ret);
    }
}

/**
 * Initialization
 */
global $pop_serverside_labelshelpers;
$pop_serverside_labelshelpers = new PoP_ServerSide_LabelsHelpers();
