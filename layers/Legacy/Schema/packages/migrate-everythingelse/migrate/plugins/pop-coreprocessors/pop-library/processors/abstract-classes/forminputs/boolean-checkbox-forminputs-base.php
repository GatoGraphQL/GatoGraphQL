<?php

abstract class PoP_Module_Processor_BooleanCheckboxFormInputsBase extends PoP_Module_Processor_CheckboxFormInputsBase
{
    use PoP_Module_Processor_BooleanFormInputsTrait;

    public function getCheckboxValue(array $component, array &$props)
    {

        // If the checkbox is "on", it will have value "1"
        // It would be possible to return "" and check that the value is not null, but then it's not easy to compare to see if that checkbox was selected.
        // For multiple checkboxes, this values must be overriden, to the actual value of that checkbox among all values
        return true;
    }
}
