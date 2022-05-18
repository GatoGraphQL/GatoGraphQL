<?php

class PoP_Module_Processor_SettingsForms extends PoP_Module_Processor_FormsBase
{
    public final const MODULE_FORM_SETTINGS = 'form-settings';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORM_SETTINGS],
        );
    }

    public function getInnerSubmodule(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FORM_SETTINGS:
                return [PoP_Module_Processor_SettingsFormInners::class, PoP_Module_Processor_SettingsFormInners::MODULE_FORMINNER_SETTINGS];
        }

        return parent::getInnerSubmodule($componentVariation);
    }
}



