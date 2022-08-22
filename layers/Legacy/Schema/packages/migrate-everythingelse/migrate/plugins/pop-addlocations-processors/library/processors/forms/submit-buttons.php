<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_EM_Module_Processor_SubmitButtons extends PoP_Module_Processor_SubmitButtonsBase
{
    public final const COMPONENT_EM_SUBMITBUTTON_ADDLOCATION = 'em-submitbutton-addlocation';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_EM_SUBMITBUTTON_ADDLOCATION,
        );
    }

    public function getLabel(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_EM_SUBMITBUTTON_ADDLOCATION:
                return TranslationAPIFacade::getInstance()->__('Add Location', 'em-popprocessors');
        }

        return parent::getLabel($component, $props);
    }

    public function getLoadingText(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_EM_SUBMITBUTTON_ADDLOCATION:
                return TranslationAPIFacade::getInstance()->__('Adding Location...', 'pop-coreprocessors');
        }
        
        return parent::getLoadingText($component, $props);
    }
}


