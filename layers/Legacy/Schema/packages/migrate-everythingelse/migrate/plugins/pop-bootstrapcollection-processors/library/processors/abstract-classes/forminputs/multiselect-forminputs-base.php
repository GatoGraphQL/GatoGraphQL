<?php

abstract class PoP_Module_Processor_MultiSelectFormInputsBase extends PoP_Module_Processor_SelectFormInputsBase
{
    public function isMultiple(array $component): bool
    {
        return true;
    }

    public function getJsmethods(array $component, array &$props)
    {
        $ret = parent::getJsmethods($component, $props);
        // Make it critical because the multiselect looks very ugly without initializing
        $this->addJsmethod($ret, 'multiselect', '', false, POP_PROGRESSIVEBOOTING_CRITICAL);
        return $ret;
    }
}
