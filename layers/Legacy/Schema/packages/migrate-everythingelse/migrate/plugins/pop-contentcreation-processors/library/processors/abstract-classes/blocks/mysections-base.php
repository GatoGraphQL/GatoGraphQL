<?php

abstract class PoP_Module_Processor_MySectionBlocksBase extends PoP_Module_Processor_SectionBlocksBase
{
    protected function showDisabledLayerIfCheckpointFailed(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return true;
    }
}
