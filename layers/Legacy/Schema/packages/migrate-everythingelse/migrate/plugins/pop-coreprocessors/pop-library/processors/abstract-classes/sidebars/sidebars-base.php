<?php

abstract class PoP_Module_Processor_SidebarsBase extends PoP_Module_Processor_ContentsBase
{
    public function addFetchedData(array $componentVariation, array &$props)
    {
        return false;
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        $this->appendProp($componentVariation, $props, 'class', 'sidebar');
        parent::initModelProps($componentVariation, $props);
    }
}
