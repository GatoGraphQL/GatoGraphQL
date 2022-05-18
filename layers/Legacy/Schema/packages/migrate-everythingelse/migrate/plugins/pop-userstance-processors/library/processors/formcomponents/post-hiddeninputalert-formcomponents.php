<?php

class PoP_UserStance_Module_Processor_PostHiddenInputAlertFormComponents extends PoP_Module_Processor_PostHiddenInputAlertFormComponentsBase
{
    public final const COMPONENT_FORMCOMPONENT_HIDDENINPUTALERT_STANCETARGET = 'formcomponent-hiddeninputalert-stancetarget';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FORMCOMPONENT_HIDDENINPUTALERT_STANCETARGET],
        );
    }
    
    public function getHiddeninputModule(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_FORMCOMPONENT_HIDDENINPUTALERT_STANCETARGET:
                return [PoP_UserStance_Processor_HiddenInputFormInputs::class, PoP_UserStance_Processor_HiddenInputFormInputs::COMPONENT_FORMINPUT_HIDDENINPUT_STANCETARGET];
        }

        return parent::getHiddeninputModule($component);
    }
}



