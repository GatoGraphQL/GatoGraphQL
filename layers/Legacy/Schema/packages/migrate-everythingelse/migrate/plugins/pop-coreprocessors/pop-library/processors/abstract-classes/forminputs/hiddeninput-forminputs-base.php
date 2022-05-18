<?php

abstract class PoP_Module_Processor_HiddenInputFormInputsBase extends PoP_Module_Processor_HiddenIDTextFormInputsBase
{
    public function printValue(array $component, array &$props)
    {

        // Currently there is a bug: calling https://www.mesym.com/en/posts/?profiles[0]=1782&profiles[1]=1764&filter=posts
        // produces each hiddeninput to have value "1782,1764"
        // This is because the value is set by a parent formcomponent, the selectable trigger typeahead, which can have an array of values
        // and since all formcomponents share the same name, then they also share the value, creating an issue in this case
        // The solution is to simply not print the value in the formcomponent, which will then be printed from the dbObject in function formcomponentValue
        // Just for a hacky fix, check here that if the value is an array, then override it. It is not ideal, but it fixes the bug (better solution required!)
        // $filter = $this->getProp($component, $props, 'filter');
        $value = $this->getValue($component);
        if ($value && is_array($value)) {
            return false;
        }

        return parent::printValue($component, $props);
    }
}
