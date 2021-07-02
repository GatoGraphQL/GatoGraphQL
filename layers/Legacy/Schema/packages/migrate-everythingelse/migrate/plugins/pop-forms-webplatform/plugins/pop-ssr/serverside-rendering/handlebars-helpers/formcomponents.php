<?php
/**
Helper functions, they have the same logic as the original javascript helper file wp-content/plugins/pop-engine-webplatform/js/helpers.handlebars.js
 */
class PoP_Forms_ServerSide_FormcomponentsHelpers
{
    public function formcomponentValue($value, $dbObject, $dbObjectField, $defaultValue, $options)
    {

        // If the value has been set, return that value already, even if it is empty
        if (!is_null($value)) {
            return $this->applyFormfieldvalueOptions($value, $options);
        }

        // If the field has been provided and is not empty, and the dbObject exists (aka it has been set in the context), then return that field from the dbObject
        if ($dbObject && $dbObjectField) {
            return $this->applyFormfieldvalueOptions($dbObject[$dbObjectField], $options);
        }

        // If there is a default value, use it next
        if (!is_null($defaultValue)) {
            return $this->applyFormfieldvalueOptions($defaultValue, $options);
        }

        // Return nothing through null, since it works for both multiple ([]) / single ("") values
        return null;
    }

    protected function applyFormfieldvalueOptions($value, $options)
    {

        // Allow MultipleInput to obtain the values for the subfields (eg: DateRange from/to values)
        $subfields = $options['hash']['subfields'] ?? array();
        foreach ($subfields as $subfield) {
            $value = $value[$subfield];
        }

        return $value;
    }
}

/**
 * Initialization
 */
global $pop_serverside_formcomponentshelpers;
$pop_serverside_formcomponentshelpers = new PoP_Forms_ServerSide_FormcomponentsHelpers();
