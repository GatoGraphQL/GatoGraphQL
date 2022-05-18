<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_ContentCreation_Module_Processor_TextareaFormInputs extends PoP_Module_Processor_TextareaFormInputsBase
{
    public final const MODULE_FORMINPUT_WHYFLAG = 'gf-field-whyflag';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMINPUT_WHYFLAG],
        );
    }

    public function getLabelText(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::MODULE_FORMINPUT_WHYFLAG:
                return TranslationAPIFacade::getInstance()->__('Please explain why this post is inappropriate', 'pop-genericforms');
        }
        
        return parent::getLabelText($component, $props);
    }

    public function isMandatory(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::MODULE_FORMINPUT_WHYFLAG:
                return true;
        }
        
        return parent::isMandatory($component, $props);
    }

    public function clearInput(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::MODULE_FORMINPUT_WHYFLAG:
                return true;
        }

        return parent::clearInput($component, $props);
    }
}



