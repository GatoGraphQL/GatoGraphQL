<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Volunteering_Module_Processor_TextareaFormInputs extends PoP_Module_Processor_TextareaFormInputsBase
{
    public final const COMPONENT_FORMINPUT_WHYVOLUNTEER = 'gf-field-whyvolunteer';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FORMINPUT_WHYVOLUNTEER],
        );
    }

    public function getLabelText(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_FORMINPUT_WHYVOLUNTEER:
                return TranslationAPIFacade::getInstance()->__('Why do you want to volunteer?', 'pop-genericforms');
        }
        
        return parent::getLabelText($component, $props);
    }

    public function isMandatory(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_FORMINPUT_WHYVOLUNTEER:
                return true;
        }
        
        return parent::isMandatory($component, $props);
    }

    public function clearInput(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_FORMINPUT_WHYVOLUNTEER:
                return true;
        }

        return parent::clearInput($component, $props);
    }
}



