<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GenericForms_Module_Processor_SelectFormInputs extends PoP_Module_Processor_SelectFormInputsBase
{
    public final const COMPONENT_FORMINPUT_TOPIC = 'gf-field-topic';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_FORMINPUT_TOPIC,
        );
    }

    public function getLabelText(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_FORMINPUT_TOPIC:
                return TranslationAPIFacade::getInstance()->__('Topic', 'pop-genericforms');
        }
        
        return parent::getLabelText($component, $props);
    }

    public function getInputClass(\PoP\ComponentModel\Component\Component $component): string
    {
        switch ($component->name) {
            case self::COMPONENT_FORMINPUT_TOPIC:
                return GD_FormInput_ContactUs_Topics::class;
        }
        
        return parent::getInputClass($component);
    }
}



