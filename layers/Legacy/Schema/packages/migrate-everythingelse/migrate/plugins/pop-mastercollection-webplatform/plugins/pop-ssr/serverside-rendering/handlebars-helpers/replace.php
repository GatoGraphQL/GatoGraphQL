<?php
/**
Helper functions, they have the same logic as the original javascript helper file wp-content/plugins/pop-engine-webplatform/js/helpers.handlebars.js
 */
class PoP_ServerSide_ReplaceHelpers
{
    public function replace($search, $replace, $options)
    {
        if ($search) {
            // Watch out! If $replace == null (as it happens coming from formcomponentValue) then it will replace by "null", in that case make it an empty string
            $replace = $replace ?? '';

            if ($search != $replace) {
                return str_replace($search, $replace, $options['fn']());
            }
        }

        return $options['fn']();
    }
}

/**
 * Initialization
 */
global $pop_serverside_replacehelpers;
$pop_serverside_replacehelpers = new PoP_ServerSide_ReplaceHelpers();
