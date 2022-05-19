<?php

abstract class PoP_Module_Processor_SelectableTypeaheadAlertFormComponentsBase extends PoP_Module_Processor_HiddenInputAlertFormComponentsBase
{
    public function getJsmethods(array $component, array &$props)
    {
        $ret = parent::getJsmethods($component, $props);
        $this->addJsmethod($ret, 'selectableTypeaheadTrigger');
        return $ret;
    }

    public function getAlertClass(array $component, array &$props)
    {
        return 'alert-success alert-sm';
    }

    public function showCloseButton(array $component, array &$props)
    {
        return true;
    }
}
