<?php

abstract class PoP_Module_Processor_SelectableTypeaheadAlertFormComponentsBase extends PoP_Module_Processor_HiddenInputAlertFormComponentsBase
{
    public function getJsmethods(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $ret = parent::getJsmethods($component, $props);
        $this->addJsmethod($ret, 'selectableTypeaheadTrigger');
        return $ret;
    }

    public function getAlertClass(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return 'alert-success alert-sm';
    }

    public function showCloseButton(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return true;
    }
}
