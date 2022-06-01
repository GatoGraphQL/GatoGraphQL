<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Share_Module_Processor_SubmitButtons extends PoP_Module_Processor_SubmitButtonsBase
{
    public final const COMPONENT_GF_SUBMITBUTTON_SENDEMAIL = 'gf-submitbutton-sendemail';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_GF_SUBMITBUTTON_SENDEMAIL],
        );
    }

    public function getLabel(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_GF_SUBMITBUTTON_SENDEMAIL:
                return TranslationAPIFacade::getInstance()->__('Send Email', 'pop-genericforms');
        }

        return parent::getLabel($component, $props);
    }

    public function getLoadingText(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_GF_SUBMITBUTTON_SENDEMAIL:
                return TranslationAPIFacade::getInstance()->__('Sending...', 'pop-genericforms');
        }
        
        return parent::getLoadingText($component, $props);
    }
}


