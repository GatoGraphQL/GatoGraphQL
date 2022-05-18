<?php

class GD_GF_Module_Processor_TextFormInputs extends PoP_Module_Processor_TextFormInputsBase
{
    public final const MODULE_GF_FORMINPUT_FORMID = 'forminput-formid';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_GF_FORMINPUT_FORMID],
        );
    }

    public function isHidden(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_GF_FORMINPUT_FORMID:
                return true;
        }
        
        return parent::isHidden($componentVariation, $props);
    }

    public function getName(array $componentVariation): string
    {
        switch ($componentVariation[1]) {
            // Do not change the name of this input below!
            case self::MODULE_GF_FORMINPUT_FORMID:
                return 'gform_submit';
        }
        
        return parent::getName($componentVariation);
    }
}



