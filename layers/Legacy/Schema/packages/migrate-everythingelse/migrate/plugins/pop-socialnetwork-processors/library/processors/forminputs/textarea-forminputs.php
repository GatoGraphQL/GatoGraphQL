<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_SocialNetwork_Module_Processor_TextareaFormInputs extends PoP_Module_Processor_TextareaFormInputsBase
{
    public final const COMPONENT_FORMINPUT_MESSAGETOUSER = 'gf-field-messagetouser';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_FORMINPUT_MESSAGETOUSER,
        );
    }

    public function getLabelText(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_FORMINPUT_MESSAGETOUSER:
                return TranslationAPIFacade::getInstance()->__('Message', 'pop-genericforms');
        }
        
        return parent::getLabelText($component, $props);
    }

    public function isMandatory(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_FORMINPUT_MESSAGETOUSER:
                return true;
        }
        
        return parent::isMandatory($component, $props);
    }

    public function clearInput(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_FORMINPUT_MESSAGETOUSER:
                return true;
        }

        return parent::clearInput($component, $props);
    }
}



