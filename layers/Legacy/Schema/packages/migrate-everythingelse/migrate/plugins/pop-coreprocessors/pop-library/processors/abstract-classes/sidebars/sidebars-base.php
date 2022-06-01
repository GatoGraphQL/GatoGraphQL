<?php

abstract class PoP_Module_Processor_SidebarsBase extends PoP_Module_Processor_ContentsBase
{
    public function addFetchedData(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return false;
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        $this->appendProp($component, $props, 'class', 'sidebar');
        parent::initModelProps($component, $props);
    }
}
