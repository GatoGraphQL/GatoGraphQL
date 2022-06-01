<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_ContactUs_Module_Processor_SubmitButtons extends PoP_Module_Processor_SubmitButtonsBase
{
    public final const COMPONENT_GF_SUBMITBUTTON_SENDMESSAGE = 'gf-submitbutton-sendmessage';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_GF_SUBMITBUTTON_SENDMESSAGE],
        );
    }

    public function getLabel(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_GF_SUBMITBUTTON_SENDMESSAGE:
                return TranslationAPIFacade::getInstance()->__('Send Message', 'pop-genericforms');
        }

        return parent::getLabel($component, $props);
    }

    public function getLoadingText(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_GF_SUBMITBUTTON_SENDMESSAGE:
                return TranslationAPIFacade::getInstance()->__('Sending...', 'pop-genericforms');
        }
        
        return parent::getLoadingText($component, $props);
    }
}


