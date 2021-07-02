<?php
/**
Helper functions, they have the same logic as the original javascript helper file wp-content/plugins/pop-engine-webplatform/js/helpers.handlebars.js
 */
class PoP_ServerSide_FormatValueHelpers
{
    public function formatValue($value, $format, $options)
    {

        // If the value is null, don't do anything, since it must be coming from failing both value and dbObject[dbObjectField] in formcomponentValue
        if (is_null($value)) {
            return $value;
        }

        switch ($format) {
         // Convert from boolean to string
            case POP_VALUEFORMAT_BOOLTOSTRING:
                // Handle arrays
                if (is_array($value)) {
                    $ret = array();
                    foreach ($value as $val) {
                        $ret[] = ($val ? POP_BOOLSTRING_TRUE : POP_BOOLSTRING_FALSE);
                    }
                    return $ret;
                }

                if ($value) {
                    return POP_BOOLSTRING_TRUE;
                }
                return POP_BOOLSTRING_FALSE;
        }

        return $value;
    }
}

/**
 * Initialization
 */
global $pop_serverside_formatvaluehelpers;
$pop_serverside_formatvaluehelpers = new PoP_ServerSide_FormatValueHelpers();
