<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_EM_Module_Processor_SubmitButtons extends PoP_Module_Processor_SubmitButtonsBase
{
    public final const MODULE_EM_SUBMITBUTTON_ADDLOCATION = 'em-submitbutton-addlocation';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_EM_SUBMITBUTTON_ADDLOCATION],
        );
    }

    public function getLabel(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_EM_SUBMITBUTTON_ADDLOCATION:
                return TranslationAPIFacade::getInstance()->__('Add Location', 'em-popprocessors');
        }

        return parent::getLabel($component, $props);
    }

    public function getLoadingText(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_EM_SUBMITBUTTON_ADDLOCATION:
                return TranslationAPIFacade::getInstance()->__('Adding Location...', 'pop-coreprocessors');
        }
        
        return parent::getLoadingText($component, $props);
    }
}


