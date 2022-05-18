<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Share_Module_Processor_TextFormInputs extends PoP_Module_Processor_TextFormInputsBase
{
    public final const MODULE_FORMINPUT_DESTINATIONEMAIL = 'gf-field-destinationemail';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FORMINPUT_DESTINATIONEMAIL],
        );
    }

    public function getLabelText(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_FORMINPUT_DESTINATIONEMAIL:
                return TranslationAPIFacade::getInstance()->__('To Email(s)', 'pop-genericforms');
        }
        
        return parent::getLabelText($component, $props);
    }

    public function isMandatory(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_FORMINPUT_DESTINATIONEMAIL:
                return true;
        }
        
        return parent::isMandatory($component, $props);
    }
}



