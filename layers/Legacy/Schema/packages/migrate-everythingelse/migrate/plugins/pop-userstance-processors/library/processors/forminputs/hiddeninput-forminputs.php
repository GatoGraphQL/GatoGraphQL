<?php

class PoP_UserStance_Processor_HiddenInputFormInputs extends PoP_Module_Processor_HiddenInputFormInputsBase
{
    public final const COMPONENT_FORMINPUT_HIDDENINPUT_STANCETARGET = 'forminput-hiddeninput-stancetarget';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_FORMINPUT_HIDDENINPUT_STANCETARGET,
        );
    }

    public function getName(\PoP\ComponentModel\Component\Component $component): string
    {
        switch ($component->name) {
            case self::COMPONENT_FORMINPUT_HIDDENINPUT_STANCETARGET:
                return POP_INPUTNAME_STANCETARGET;
        }

        return parent::getName($component);
    }
}
