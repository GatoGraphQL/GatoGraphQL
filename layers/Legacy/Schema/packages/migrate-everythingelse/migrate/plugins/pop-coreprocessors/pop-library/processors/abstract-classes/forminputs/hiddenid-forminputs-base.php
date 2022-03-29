<?php

abstract class PoP_Module_Processor_HiddenIDTextFormInputsBase extends PoP_Module_Processor_TextFormInputsBase
{
    public function isHidden(array $module, array &$props)
    {
        return true;
    }

    public function getDbobjectField(array $module): ?string
    {
        return 'id';
    }
}
