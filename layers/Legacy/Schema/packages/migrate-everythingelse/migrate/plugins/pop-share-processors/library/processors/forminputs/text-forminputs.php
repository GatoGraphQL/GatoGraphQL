<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Share_Module_Processor_TextFormInputs extends PoP_Module_Processor_TextFormInputsBase
{
    public final const MODULE_FORMINPUT_DESTINATIONEMAIL = 'gf-field-destinationemail';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMINPUT_DESTINATIONEMAIL],
        );
    }

    public function getLabelText(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FORMINPUT_DESTINATIONEMAIL:
                return TranslationAPIFacade::getInstance()->__('To Email(s)', 'pop-genericforms');
        }
        
        return parent::getLabelText($componentVariation, $props);
    }

    public function isMandatory(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FORMINPUT_DESTINATIONEMAIL:
                return true;
        }
        
        return parent::isMandatory($componentVariation, $props);
    }
}



