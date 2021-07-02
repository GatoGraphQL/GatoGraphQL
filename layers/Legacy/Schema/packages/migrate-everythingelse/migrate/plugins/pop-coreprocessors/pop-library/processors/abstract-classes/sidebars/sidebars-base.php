<?php

abstract class PoP_Module_Processor_SidebarsBase extends PoP_Module_Processor_ContentsBase
{
    public function addFetchedData(array $module, array &$props)
    {
        return false;
    }

    public function initModelProps(array $module, array &$props): void
    {
        $this->appendProp($module, $props, 'class', 'sidebar');
        parent::initModelProps($module, $props);
    }
}
