<?php
/**
Helper functions, they have the same logic as the original javascript helper file wp-content/plugins/pop-engine-webplatform/js/helpers.handlebars.js
*/
class PoP_ServerSide_ArraysHelperCallers
{
    public static function maybeMakeArray($element, $options)
    {
        global $pop_serverside_arrayshelpers;
        return $pop_serverside_arrayshelpers->maybeMakeArray($element, $options);
    }
}

/**
 * Registration
 */
PoP_SSR_CLI_HelperRegistration::register(PoP_ServerSide_ArraysHelperCallers::class);
