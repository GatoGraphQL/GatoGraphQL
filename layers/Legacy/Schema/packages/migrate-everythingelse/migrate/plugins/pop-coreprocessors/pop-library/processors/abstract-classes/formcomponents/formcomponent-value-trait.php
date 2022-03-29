<?php

trait FormComponentValueTrait
{

    //-------------------------------------------------
    // PUBLIC Functions
    //-------------------------------------------------

    public function getDbobjectField(array $module): ?string
    {
        return null;
    }

    public function addMetaFormcomponentDataFields(&$ret, array $module, array &$props)
    {
        if ($field = $this->getDbobjectField($module)) {
            $ret[] = $field;
        }
    }

    public function printValue(array $module, array &$props)
    {

        // Currently there is a bug: calling https://www.mesym.com/en/posts/?profiles[0]=1782&profiles[1]=1764&filter=posts
        // produces each hiddeninput to have value "1782,1764"
        // This is because the value is set by a parent formcomponent, the selectable trigger typeahead, which can have an array of values
        // and since all formcomponents share the same name, then they also share the value, creating an issue in this case
        // The solution is to simply not print the value in the formcomponent, which will then be printed from the dbObject in function formcomponentValue
        return true;
    }

    public function addMetaFormcomponentModuleRuntimeconfiguration(&$ret, array $module, array &$props)
    {

        // Sometimes we do not want to print the value. Check the description in function `printValue`
        if ($this->printValue($module, $props)) {
            // The value goes into the runtime configuration and not the configuration, so that the configuration can be cached without particular values attached.
            // Eg: calling https://www.mesym.com/add-discussion/?related[]=19373 would initiate the value to 19373 and cache it
            // This way, take all particular stuff to any one URL out from its settings
            // $filter = $this->getProp($module, $props, 'filter');
            $value = $this->getValue($module);

            // If it is null, then fetch value from the dbObject
            // If it is empty, then it's a valid value, it takes priority over dbObject
            if (!is_null($value)) {
                $ret['value'] = $value;
            }
        }

        // After {{value}} and {{dbObject[dbObjectField]}} both fail, print {{default-value}}
        // The value can also be the boolean false, so check for the !is_null condition
        $default_value = $this->getDefaultValue($module, $props);
        if (!is_null($default_value)) {
            $ret['default-value'] = $default_value;
        }
    }

    public function addMetaFormcomponentModuleConfiguration(&$ret, array $module, array &$props)
    {
        if ($field = $this->getDbobjectField($module)) {
            $ret['dbobject-field'] = $field;
        }
    }
}
