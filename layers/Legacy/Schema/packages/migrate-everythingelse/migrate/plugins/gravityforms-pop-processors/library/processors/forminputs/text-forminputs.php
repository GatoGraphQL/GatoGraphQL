<?php

class GD_GF_Module_Processor_TextFormInputs extends PoP_Module_Processor_TextFormInputsBase
{
    public final const COMPONENT_GF_FORMINPUT_FORMID = 'forminput-formid';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_GF_FORMINPUT_FORMID,
        );
    }

    public function isHidden(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_GF_FORMINPUT_FORMID:
                return true;
        }
        
        return parent::isHidden($component, $props);
    }

    public function getName(\PoP\ComponentModel\Component\Component $component): string
    {
        switch ($component->name) {
            // Do not change the name of this input below!
            case self::COMPONENT_GF_FORMINPUT_FORMID:
                return 'gform_submit';
        }
        
        return parent::getName($component);
    }
}



