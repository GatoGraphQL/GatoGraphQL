<?php
abstract class PoP_Module_Processor_HighlightedPostSubcomponentLayoutsBase extends PoP_Module_Processor_SubcomponentLayoutsBase
{
    public function getSubcomponentFieldNode(\PoP\ComponentModel\Component\Component $component)
    {
        return 'highlightedpost';
    }
}
