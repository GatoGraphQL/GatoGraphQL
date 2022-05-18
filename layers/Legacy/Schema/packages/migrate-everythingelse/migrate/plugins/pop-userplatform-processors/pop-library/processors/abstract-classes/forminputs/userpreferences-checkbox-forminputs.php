<?php

abstract class PoP_UserPlatform_Module_Processor_UserPreferencesCheckboxFormInputs extends PoP_Module_Processor_CheckboxFormInputsBase
{
    public function getDbobjectField(array $componentVariation): ?string
    {
        return 'userPreferences';
    }

    public function isMultiple(array $componentVariation): bool
    {
        return true;
    }

    public function getName(array $componentVariation): string
    {
        return 'userPreferences';
    }
}
