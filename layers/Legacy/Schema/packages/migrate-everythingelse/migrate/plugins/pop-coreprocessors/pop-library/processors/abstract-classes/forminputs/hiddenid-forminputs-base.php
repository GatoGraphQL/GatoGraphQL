<?php

abstract class PoP_Module_Processor_HiddenIDTextFormInputsBase extends PoP_Module_Processor_TextFormInputsBase
{
    public function isHidden(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return true;
    }

    public function getDbobjectField(\PoP\ComponentModel\Component\Component $component): ?string
    {
        return 'id';
    }
}
