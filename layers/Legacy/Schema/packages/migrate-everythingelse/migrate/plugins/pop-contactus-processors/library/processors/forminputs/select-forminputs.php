<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GenericForms_Module_Processor_SelectFormInputs extends PoP_Module_Processor_SelectFormInputsBase
{
    public final const COMPONENT_FORMINPUT_TOPIC = 'gf-field-topic';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FORMINPUT_TOPIC],
        );
    }

    public function getLabelText(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_FORMINPUT_TOPIC:
                return TranslationAPIFacade::getInstance()->__('Topic', 'pop-genericforms');
        }
        
        return parent::getLabelText($component, $props);
    }

    public function getInputClass(array $component): string
    {
        switch ($component[1]) {
            case self::COMPONENT_FORMINPUT_TOPIC:
                return GD_FormInput_ContactUs_Topics::class;
        }
        
        return parent::getInputClass($component);
    }
}



