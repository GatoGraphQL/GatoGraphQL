<?php

class UserStance_Module_Processor_ButtonGroupFormInputs extends PoP_Module_Processor_ButtonGroupFormInputsBase
{
    public final const MODULE_FORMINPUT_BUTTONGROUP_STANCE = 'forminput-buttongroup-stance';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMINPUT_BUTTONGROUP_STANCE],
        );
    }

    public function getInputClass(array $componentVariation): string
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FORMINPUT_BUTTONGROUP_STANCE:
                return GD_FormInput_Stance::class;
        }
        
        return parent::getInputClass($componentVariation);
    }

    public function getDbobjectField(array $componentVariation): ?string
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FORMINPUT_BUTTONGROUP_STANCE:
                return 'stance';
        }
        
        return parent::getDbobjectField($componentVariation);
    }
}



