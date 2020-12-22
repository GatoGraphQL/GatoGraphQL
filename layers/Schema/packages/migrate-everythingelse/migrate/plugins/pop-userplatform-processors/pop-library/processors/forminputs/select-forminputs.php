<?php
use PoP\Translation\Facades\TranslationAPIFacade;

class GD_UserPlatform_Module_Processor_SelectFormInputs extends PoP_Module_Processor_SelectFormInputsBase
{
    public const MODULE_FORMINPUT_SETTINGSFORMAT = 'forminput-settingsformat';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMINPUT_SETTINGSFORMAT],
        );
    }

    public function getLabelText(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_FORMINPUT_SETTINGSFORMAT:
                return TranslationAPIFacade::getInstance()->__('Default view', 'pop-coreprocessors');
        }
        
        return parent::getLabelText($module, $props);
    }

    public function getInputClass(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_FORMINPUT_SETTINGSFORMAT:
                return GD_FormInput_SettingsFormat::class;
        }
        
        return parent::getInputClass($module);
    }
}



