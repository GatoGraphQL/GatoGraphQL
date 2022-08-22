<?php

class PoP_Module_Processor_CommentHiddenInputAlertFormComponents extends PoP_Module_Processor_CommentHiddenInputAlertFormComponentsBase
{
    public final const COMPONENT_FORMCOMPONENT_HIDDENINPUTALERT_LAYOUTCOMMENT = 'formcomponent-hiddeninputalert-layoutcomment';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_FORMCOMPONENT_HIDDENINPUTALERT_LAYOUTCOMMENT,
        );
    }
    
    public function getHiddenInputComponent(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_FORMCOMPONENT_HIDDENINPUTALERT_LAYOUTCOMMENT:
                return [PoP_Module_Processor_HiddenInputFormInputs::class, PoP_Module_Processor_HiddenInputFormInputs::COMPONENT_FORMINPUT_HIDDENINPUT_LAYOUTCOMMENT];
        }

        return parent::getHiddenInputComponent($component);
    }
}



