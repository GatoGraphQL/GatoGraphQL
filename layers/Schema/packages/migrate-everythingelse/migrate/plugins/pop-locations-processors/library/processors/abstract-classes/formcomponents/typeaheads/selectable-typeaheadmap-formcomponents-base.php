<?php

abstract class PoP_Module_Processor_SelectableTypeaheadMapFormComponentsBase extends PoP_Module_Processor_TypeaheadMapFormComponentsBase
{
    public function getJsmethods(array $module, array &$props)
    {
        $ret = parent::getJsmethods($module, $props);
        $this->addJsmethod($ret, 'selectableTypeaheadMap');
        return $ret;
    }
}
