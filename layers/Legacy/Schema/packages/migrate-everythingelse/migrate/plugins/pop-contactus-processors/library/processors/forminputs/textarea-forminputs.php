<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_ContactUs_Module_Processor_TextareaFormInputs extends PoP_Module_Processor_TextareaFormInputsBase
{
    public final const MODULE_FORMINPUT_MESSAGE = 'gf-field-message';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMINPUT_MESSAGE],
        );
    }

    public function getLabelText(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_FORMINPUT_MESSAGE:
                return TranslationAPIFacade::getInstance()->__('Message', 'pop-genericforms');
        }
        
        return parent::getLabelText($module, $props);
    }

    public function isMandatory(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_FORMINPUT_MESSAGE:
                return true;
        }
        
        return parent::isMandatory($module, $props);
    }

    public function clearInput(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_FORMINPUT_MESSAGE:
                return true;
        }

        return parent::clearInput($module, $props);
    }
}



