<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GenericForms_Module_Processor_CheckboxFormInputs extends PoP_Module_Processor_BooleanCheckboxFormInputsBase
{
    public final const COMPONENT_FORMINPUT_CUP_NEWSLETTER = 'gf-cup-newsletter';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FORMINPUT_CUP_NEWSLETTER],
        );
    }

    public function getLabelText(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_FORMINPUT_CUP_NEWSLETTER:
                return TranslationAPIFacade::getInstance()->__('Subscribe to our Newsletter?', 'pop-genericforms');
        }
        
        return parent::getLabelText($component, $props);
    }

    public function getInputDefaultValue(\PoP\ComponentModel\Component\Component $component, array &$props): mixed
    {
        switch ($component->name) {
            case self::COMPONENT_FORMINPUT_CUP_NEWSLETTER:
                // Subscribe to newsletter by default
                return true;
        }
        
        return parent::getInputDefaultValue($component, $props);
    }
}



