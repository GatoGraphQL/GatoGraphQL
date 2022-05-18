<?php

abstract class PoP_Module_Processor_SelectableTypeaheadAlertFormComponentsBase extends PoP_Module_Processor_HiddenInputAlertFormComponentsBase
{
    public function getJsmethods(array $componentVariation, array &$props)
    {
        $ret = parent::getJsmethods($componentVariation, $props);
        $this->addJsmethod($ret, 'selectableTypeaheadTrigger');
        return $ret;
    }

    public function getAlertClass(array $componentVariation, array &$props)
    {
        return 'alert-success alert-sm';
    }

    public function showCloseButton(array $componentVariation, array &$props)
    {
        return true;
    }
}
