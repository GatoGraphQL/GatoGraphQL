<?php

abstract class PoP_UserPlatform_Module_Processor_UserPreferencesCheckboxFormInputs extends PoP_Module_Processor_CheckboxFormInputsBase
{
    public function getDbobjectField(\PoP\ComponentModel\Component\Component $component): ?string
    {
        return 'userPreferences';
    }

    public function isMultiple(\PoP\ComponentModel\Component\Component $component): bool
    {
        return true;
    }

    public function getName(\PoP\ComponentModel\Component\Component $component): string
    {
        return 'userPreferences';
    }
}
