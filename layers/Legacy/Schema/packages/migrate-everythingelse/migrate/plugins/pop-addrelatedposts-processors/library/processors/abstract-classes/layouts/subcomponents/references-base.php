<?php
abstract class PoP_Module_Processor_ReferencesLayoutsBase extends PoP_Module_Processor_SubcomponentLayoutsBase
{
    public function getSubcomponentFieldNode(\PoP\ComponentModel\Component\Component $component)
    {
        return 'references';
    }
}
