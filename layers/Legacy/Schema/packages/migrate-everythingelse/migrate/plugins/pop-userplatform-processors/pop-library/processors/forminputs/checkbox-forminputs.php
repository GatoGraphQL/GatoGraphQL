<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_CreateUpdateProfileCheckboxFormInputs extends PoP_Module_Processor_BooleanCheckboxFormInputsBase
{
    public final const COMPONENT_FORMINPUT_CUP_DISPLAYEMAIL = 'forminput-cup-displayemail';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FORMINPUT_CUP_DISPLAYEMAIL],
        );
    }

    public function getLabelText(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_FORMINPUT_CUP_DISPLAYEMAIL:
                return TranslationAPIFacade::getInstance()->__('Show email in your user profile?', 'pop-coreprocessors');
        }
        
        return parent::getLabelText($component, $props);
    }

    public function getDbobjectField(array $component): ?string
    {
        switch ($component[1]) {
            case self::COMPONENT_FORMINPUT_CUP_DISPLAYEMAIL:
                return 'displayEmail';
        }

        return parent::getDbobjectField($component);
    }
}



