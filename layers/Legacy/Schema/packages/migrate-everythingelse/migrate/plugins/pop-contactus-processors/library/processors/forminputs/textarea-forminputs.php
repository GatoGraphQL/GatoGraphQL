<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_ContactUs_Module_Processor_TextareaFormInputs extends PoP_Module_Processor_TextareaFormInputsBase
{
    public final const COMPONENT_FORMINPUT_MESSAGE = 'gf-field-message';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_FORMINPUT_MESSAGE,
        );
    }

    public function getLabelText(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_FORMINPUT_MESSAGE:
                return TranslationAPIFacade::getInstance()->__('Message', 'pop-genericforms');
        }
        
        return parent::getLabelText($component, $props);
    }

    public function isMandatory(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_FORMINPUT_MESSAGE:
                return true;
        }
        
        return parent::isMandatory($component, $props);
    }

    public function clearInput(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_FORMINPUT_MESSAGE:
                return true;
        }

        return parent::clearInput($component, $props);
    }
}



