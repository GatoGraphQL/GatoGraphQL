<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_SocialNetwork_Module_Processor_TextFormInputs extends PoP_Module_Processor_TextFormInputsBase
{
    public final const COMPONENT_FORMINPUT_MESSAGESUBJECT = 'gf-field-messagesubject';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FORMINPUT_MESSAGESUBJECT],
        );
    }

    public function getLabelText(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_FORMINPUT_MESSAGESUBJECT:
                return  TranslationAPIFacade::getInstance()->__('Subject', 'pop-genericforms');
        }
        
        return parent::getLabelText($component, $props);
    }

    public function clearInput(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_FORMINPUT_MESSAGESUBJECT:
                return true;
        }

        return parent::clearInput($component, $props);
    }
}



