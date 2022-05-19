<?php

abstract class PoP_Module_Processor_SelectableTypeaheadMapFormComponentsBase extends PoP_Module_Processor_TypeaheadMapFormComponentsBase
{
    public function getJsmethods(array $component, array &$props)
    {
        $ret = parent::getJsmethods($component, $props);
        $this->addJsmethod($ret, 'selectableTypeaheadMap');
        return $ret;
    }
}
