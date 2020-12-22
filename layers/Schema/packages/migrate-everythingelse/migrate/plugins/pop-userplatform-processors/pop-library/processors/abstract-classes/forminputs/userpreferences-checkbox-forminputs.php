<?php

abstract class PoP_UserPlatform_Module_Processor_UserPreferencesCheckboxFormInputs extends PoP_Module_Processor_CheckboxFormInputsBase
{
    public function getDbobjectField(array $module)
    {
        return 'userPreferences';
    }

    public function isMultiple(array $module)
    {
        return true;
    }

    public function getName(array $module)
    {
        return 'userPreferences';
    }
}
