<?php

class UserStance_Module_Processor_ButtonGroupFormInputs extends PoP_Module_Processor_ButtonGroupFormInputsBase
{
    public final const COMPONENT_FORMINPUT_BUTTONGROUP_STANCE = 'forminput-buttongroup-stance';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_FORMINPUT_BUTTONGROUP_STANCE,
        );
    }

    public function getInputClass(\PoP\ComponentModel\Component\Component $component): string
    {
        switch ($component->name) {
            case self::COMPONENT_FORMINPUT_BUTTONGROUP_STANCE:
                return GD_FormInput_Stance::class;
        }
        
        return parent::getInputClass($component);
    }

    public function getDbobjectField(\PoP\ComponentModel\Component\Component $component): ?string
    {
        switch ($component->name) {
            case self::COMPONENT_FORMINPUT_BUTTONGROUP_STANCE:
                return 'stance';
        }
        
        return parent::getDbobjectField($component);
    }
}



