<?php
abstract class PoP_Module_Processor_HighlightedPostSubcomponentLayoutsBase extends PoP_Module_Processor_SubcomponentLayoutsBase
{
    public function getSubcomponentField(array $module)
    {
        return 'highlightedpost';
    }
}
