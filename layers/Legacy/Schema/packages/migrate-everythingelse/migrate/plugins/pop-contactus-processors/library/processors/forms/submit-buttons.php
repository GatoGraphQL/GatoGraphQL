<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_ContactUs_Module_Processor_SubmitButtons extends PoP_Module_Processor_SubmitButtonsBase
{
    public final const MODULE_GF_SUBMITBUTTON_SENDMESSAGE = 'gf-submitbutton-sendmessage';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_GF_SUBMITBUTTON_SENDMESSAGE],
        );
    }

    public function getLabel(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_GF_SUBMITBUTTON_SENDMESSAGE:
                return TranslationAPIFacade::getInstance()->__('Send Message', 'pop-genericforms');
        }

        return parent::getLabel($module, $props);
    }

    public function getLoadingText(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_GF_SUBMITBUTTON_SENDMESSAGE:
                return TranslationAPIFacade::getInstance()->__('Sending...', 'pop-genericforms');
        }
        
        return parent::getLoadingText($module, $props);
    }
}


