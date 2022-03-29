<?php

abstract class PoP_UserPlatform_Module_Processor_UserPreferencesCheckboxFormInputs extends PoP_Module_Processor_CheckboxFormInputsBase
{
    public function getDbobjectField(array $module): ?string
    {
        return 'userPreferences';
    }

    public function isMultiple(array $module): bool
    {
        return true;
    }

    public function getName(array $module): string
    {
        return 'userPreferences';
    }
}
