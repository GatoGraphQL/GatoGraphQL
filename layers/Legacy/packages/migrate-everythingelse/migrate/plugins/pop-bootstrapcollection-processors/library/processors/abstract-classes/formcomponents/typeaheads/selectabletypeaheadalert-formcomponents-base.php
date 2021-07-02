<?php

abstract class PoP_Module_Processor_SelectableTypeaheadAlertFormComponentsBase extends PoP_Module_Processor_HiddenInputAlertFormComponentsBase
{
    public function getJsmethods(array $module, array &$props)
    {
        $ret = parent::getJsmethods($module, $props);
        $this->addJsmethod($ret, 'selectableTypeaheadTrigger');
        return $ret;
    }

    public function getAlertClass(array $module, array &$props)
    {
        return 'alert-success alert-sm';
    }

    public function showCloseButton(array $module, array &$props)
    {
        return true;
    }
}
