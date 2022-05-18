<?php

class UserStance_Module_Processor_ButtonGroupFormInputs extends PoP_Module_Processor_ButtonGroupFormInputsBase
{
    public final const MODULE_FORMINPUT_BUTTONGROUP_STANCE = 'forminput-buttongroup-stance';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FORMINPUT_BUTTONGROUP_STANCE],
        );
    }

    public function getInputClass(array $component): string
    {
        switch ($component[1]) {
            case self::COMPONENT_FORMINPUT_BUTTONGROUP_STANCE:
                return GD_FormInput_Stance::class;
        }
        
        return parent::getInputClass($component);
    }

    public function getDbobjectField(array $component): ?string
    {
        switch ($component[1]) {
            case self::COMPONENT_FORMINPUT_BUTTONGROUP_STANCE:
                return 'stance';
        }
        
        return parent::getDbobjectField($component);
    }
}



