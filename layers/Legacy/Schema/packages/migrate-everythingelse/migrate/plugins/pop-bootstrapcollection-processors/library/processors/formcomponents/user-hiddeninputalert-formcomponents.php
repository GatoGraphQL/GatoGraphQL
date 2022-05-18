<?php

class PoP_Module_Processor_UserHiddenInputAlertFormComponents extends PoP_Module_Processor_UserHiddenInputAlertFormComponentsBase
{
    public final const MODULE_FORMCOMPONENT_HIDDENINPUTALERT_LAYOUTUSER = 'formcomponent-hiddeninputalert-layoutuser';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMCOMPONENT_HIDDENINPUTALERT_LAYOUTUSER],
        );
    }
    
    public function getHiddeninputModule(array $component)
    {
        switch ($component[1]) {
            case self::MODULE_FORMCOMPONENT_HIDDENINPUTALERT_LAYOUTUSER:
                return [PoP_Module_Processor_HiddenInputFormInputs::class, PoP_Module_Processor_HiddenInputFormInputs::MODULE_FORMINPUT_HIDDENINPUT_LAYOUTUSER];
        }

        return parent::getHiddeninputModule($component);
    }
}



