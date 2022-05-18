<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_ContentCreation_Module_Processor_TextareaFormInputs extends PoP_Module_Processor_TextareaFormInputsBase
{
    public final const MODULE_FORMINPUT_WHYFLAG = 'gf-field-whyflag';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMINPUT_WHYFLAG],
        );
    }

    public function getLabelText(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FORMINPUT_WHYFLAG:
                return TranslationAPIFacade::getInstance()->__('Please explain why this post is inappropriate', 'pop-genericforms');
        }
        
        return parent::getLabelText($componentVariation, $props);
    }

    public function isMandatory(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FORMINPUT_WHYFLAG:
                return true;
        }
        
        return parent::isMandatory($componentVariation, $props);
    }

    public function clearInput(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FORMINPUT_WHYFLAG:
                return true;
        }

        return parent::clearInput($componentVariation, $props);
    }
}



