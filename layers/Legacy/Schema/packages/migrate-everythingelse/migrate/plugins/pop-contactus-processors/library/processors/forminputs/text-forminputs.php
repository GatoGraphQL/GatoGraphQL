<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_ContactUs_Module_Processor_TextFormInputs extends PoP_Module_Processor_TextFormInputsBase
{
    public final const MODULE_FORMINPUT_SUBJECT = 'gf-field-subject';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMINPUT_SUBJECT],
        );
    }

    public function getLabelText(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::MODULE_FORMINPUT_SUBJECT:
                return  TranslationAPIFacade::getInstance()->__('Subject', 'pop-genericforms');
        }
        
        return parent::getLabelText($component, $props);
    }

    public function clearInput(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::MODULE_FORMINPUT_SUBJECT:
                return true;
        }

        return parent::clearInput($component, $props);
    }
}



