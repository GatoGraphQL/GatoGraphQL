<?php

abstract class PoP_Module_Processor_MultiSelectFormInputsBase extends PoP_Module_Processor_SelectFormInputsBase
{
    public function isMultiple(array $componentVariation): bool
    {
        return true;
    }

    public function getJsmethods(array $componentVariation, array &$props)
    {
        $ret = parent::getJsmethods($componentVariation, $props);
        // Make it critical because the multiselect looks very ugly without initializing
        $this->addJsmethod($ret, 'multiselect', '', false, POP_PROGRESSIVEBOOTING_CRITICAL);
        return $ret;
    }
}
