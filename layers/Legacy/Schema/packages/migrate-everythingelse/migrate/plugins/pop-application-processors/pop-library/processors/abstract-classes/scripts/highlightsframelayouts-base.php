<?php

abstract class PoP_Module_Processor_HighlightReferencesScriptFrameLayoutsBase extends PoP_Module_Processor_ScriptFrameLayoutsBase
{
    public function doAppend(\PoP\ComponentModel\Component\Component $component)
    {
        return true;
    }

    public function getScriptSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        return $this->doAppend($component) ? 
        	[Wassup_Module_Processor_ScriptsLayouts::class, Wassup_Module_Processor_ScriptsLayouts::COMPONENT_SCRIPT_HIGHLIGHTS] : 
        	[Wassup_Module_Processor_ScriptsLayouts::class, Wassup_Module_Processor_ScriptsLayouts::COMPONENT_SCRIPT_HIGHLIGHTSEMPTY];
    }
}
