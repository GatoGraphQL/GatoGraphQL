<?php
/**
Helper functions, they have the same logic as the original javascript helper file wp-content/plugins/pop-engine-webplatform/js/helpers.handlebars.js
 */
class PoP_ServerSide_ArraysHelpers
{
    public function maybeMakeArray($element, $options)
    {

        // If null, return null
        if (is_null($element)) {
            return $element;
        }

        // If empty, return an empty array
        if (!$element) {
            return array();
        }

        // If not an array, make it so
        if (!is_array($element)) {
            return array($element);
        }
        
        return $element;
    }
}

/**
 * Initialization
 */
global $pop_serverside_arrayshelpers;
$pop_serverside_arrayshelpers = new PoP_ServerSide_ArraysHelpers();
