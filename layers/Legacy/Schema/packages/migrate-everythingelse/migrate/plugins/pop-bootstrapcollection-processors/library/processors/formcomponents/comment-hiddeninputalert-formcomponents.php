<?php

class PoP_Module_Processor_CommentHiddenInputAlertFormComponents extends PoP_Module_Processor_CommentHiddenInputAlertFormComponentsBase
{
    public final const MODULE_FORMCOMPONENT_HIDDENINPUTALERT_LAYOUTCOMMENT = 'formcomponent-hiddeninputalert-layoutcomment';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FORMCOMPONENT_HIDDENINPUTALERT_LAYOUTCOMMENT],
        );
    }
    
    public function getHiddeninputModule(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_FORMCOMPONENT_HIDDENINPUTALERT_LAYOUTCOMMENT:
                return [PoP_Module_Processor_HiddenInputFormInputs::class, PoP_Module_Processor_HiddenInputFormInputs::COMPONENT_FORMINPUT_HIDDENINPUT_LAYOUTCOMMENT];
        }

        return parent::getHiddeninputModule($component);
    }
}



