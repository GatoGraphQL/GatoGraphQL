<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_UserPlatform_Module_Processor_SelectFormInputs extends PoP_Module_Processor_SelectFormInputsBase
{
    public final const MODULE_FORMINPUT_SETTINGSFORMAT = 'forminput-settingsformat';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMINPUT_SETTINGSFORMAT],
        );
    }

    public function getLabelText(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FORMINPUT_SETTINGSFORMAT:
                return TranslationAPIFacade::getInstance()->__('Default view', 'pop-coreprocessors');
        }
        
        return parent::getLabelText($componentVariation, $props);
    }

    public function getInputClass(array $componentVariation): string
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FORMINPUT_SETTINGSFORMAT:
                return GD_FormInput_SettingsFormat::class;
        }
        
        return parent::getInputClass($componentVariation);
    }
}



