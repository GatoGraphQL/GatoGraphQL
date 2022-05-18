<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_SocialNetwork_Module_Processor_TextareaFormInputs extends PoP_Module_Processor_TextareaFormInputsBase
{
    public final const MODULE_FORMINPUT_MESSAGETOUSER = 'gf-field-messagetouser';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMINPUT_MESSAGETOUSER],
        );
    }

    public function getLabelText(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FORMINPUT_MESSAGETOUSER:
                return TranslationAPIFacade::getInstance()->__('Message', 'pop-genericforms');
        }
        
        return parent::getLabelText($componentVariation, $props);
    }

    public function isMandatory(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FORMINPUT_MESSAGETOUSER:
                return true;
        }
        
        return parent::isMandatory($componentVariation, $props);
    }

    public function clearInput(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FORMINPUT_MESSAGETOUSER:
                return true;
        }

        return parent::clearInput($componentVariation, $props);
    }
}



