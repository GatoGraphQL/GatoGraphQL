<?php

abstract class PoP_UserPlatform_Module_Processor_UserPreferencesCheckboxFormInputs extends PoP_Module_Processor_CheckboxFormInputsBase
{
    public function getDbobjectField(array $component): ?string
    {
        return 'userPreferences';
    }

    public function isMultiple(array $component): bool
    {
        return true;
    }

    public function getName(array $component): string
    {
        return 'userPreferences';
    }
}
