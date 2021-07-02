<?php
use PoP\ComponentModel\Misc\GeneralUtils;

/**
Helper functions, they have the same logic as the original javascript helper file wp-content/plugins/pop-engine-webplatform/js/helpers.handlebars.js
 */
class PoP_ServerSide_URLParamHelpers
{
    public function addQueryArg($param, $value, $url, $options)
    {

        // Allow for inputs with multiple values
        // if (!is_array($value)) {
        //     $value = array($value);
        // }
        $url = GeneralUtils::addQueryArgs([
            $param => $value, 
        ], $url);

        return $url;
    }
}

/**
 * Initialization
 */
global $pop_serverside_urlparamhelpers;
$pop_serverside_urlparamhelpers = new PoP_ServerSide_URLParamHelpers();
