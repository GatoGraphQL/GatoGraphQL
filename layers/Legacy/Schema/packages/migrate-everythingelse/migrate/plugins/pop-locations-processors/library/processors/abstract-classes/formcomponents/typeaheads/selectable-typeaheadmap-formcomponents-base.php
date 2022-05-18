<?php

abstract class PoP_Module_Processor_SelectableTypeaheadMapFormComponentsBase extends PoP_Module_Processor_TypeaheadMapFormComponentsBase
{
    public function getJsmethods(array $componentVariation, array &$props)
    {
        $ret = parent::getJsmethods($componentVariation, $props);
        $this->addJsmethod($ret, 'selectableTypeaheadMap');
        return $ret;
    }
}
