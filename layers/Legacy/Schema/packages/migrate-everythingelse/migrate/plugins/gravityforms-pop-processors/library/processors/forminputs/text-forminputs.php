<?php

class GD_GF_Module_Processor_TextFormInputs extends PoP_Module_Processor_TextFormInputsBase
{
    public final const MODULE_GF_FORMINPUT_FORMID = 'forminput-formid';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_GF_FORMINPUT_FORMID],
        );
    }

    public function isHidden(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_GF_FORMINPUT_FORMID:
                return true;
        }
        
        return parent::isHidden($component, $props);
    }

    public function getName(array $component): string
    {
        switch ($component[1]) {
            // Do not change the name of this input below!
            case self::COMPONENT_GF_FORMINPUT_FORMID:
                return 'gform_submit';
        }
        
        return parent::getName($component);
    }
}



