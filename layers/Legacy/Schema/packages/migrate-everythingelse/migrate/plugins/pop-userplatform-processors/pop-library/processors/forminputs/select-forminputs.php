<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_UserPlatform_Module_Processor_SelectFormInputs extends PoP_Module_Processor_SelectFormInputsBase
{
    public final const COMPONENT_FORMINPUT_SETTINGSFORMAT = 'forminput-settingsformat';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_FORMINPUT_SETTINGSFORMAT,
        );
    }

    public function getLabelText(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_FORMINPUT_SETTINGSFORMAT:
                return TranslationAPIFacade::getInstance()->__('Default view', 'pop-coreprocessors');
        }
        
        return parent::getLabelText($component, $props);
    }

    public function getInputClass(\PoP\ComponentModel\Component\Component $component): string
    {
        switch ($component->name) {
            case self::COMPONENT_FORMINPUT_SETTINGSFORMAT:
                return GD_FormInput_SettingsFormat::class;
        }
        
        return parent::getInputClass($component);
    }
}



