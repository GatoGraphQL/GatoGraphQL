<?php

class PoP_Module_Processor_PostHiddenInputAlertFormComponents extends PoP_Module_Processor_PostHiddenInputAlertFormComponentsBase
{
    public final const COMPONENT_FORMCOMPONENT_HIDDENINPUTALERT_LAYOUTPOST = 'formcomponent-hiddeninputalert-layoutpost';
    public final const COMPONENT_FORMCOMPONENT_HIDDENINPUTALERT_LAYOUTCOMMENTPOST = 'formcomponent-hiddeninputalert-layoutcommentpost';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_FORMCOMPONENT_HIDDENINPUTALERT_LAYOUTPOST,
            self::COMPONENT_FORMCOMPONENT_HIDDENINPUTALERT_LAYOUTCOMMENTPOST,
        );
    }
    
    public function getHiddenInputComponent(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_FORMCOMPONENT_HIDDENINPUTALERT_LAYOUTPOST:
                return [PoP_Module_Processor_HiddenInputFormInputs::class, PoP_Module_Processor_HiddenInputFormInputs::COMPONENT_FORMINPUT_HIDDENINPUT_LAYOUTPOST];

            case self::COMPONENT_FORMCOMPONENT_HIDDENINPUTALERT_LAYOUTCOMMENTPOST:
                return [PoP_Module_Processor_HiddenInputFormInputs::class, PoP_Module_Processor_HiddenInputFormInputs::COMPONENT_FORMINPUT_HIDDENINPUT_LAYOUTCOMMENTPOST];
        }

        return parent::getHiddenInputComponent($component);
    }
}



