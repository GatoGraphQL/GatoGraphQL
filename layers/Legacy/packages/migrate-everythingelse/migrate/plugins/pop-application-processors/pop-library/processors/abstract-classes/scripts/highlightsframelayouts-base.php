<?php

abstract class PoP_Module_Processor_HighlightReferencesScriptFrameLayoutsBase extends PoP_Module_Processor_ScriptFrameLayoutsBase
{
    public function doAppend(array $module)
    {
        return true;
    }

    public function getScriptSubmodule(array $module)
    {
        return $this->doAppend($module) ? 
        	[Wassup_Module_Processor_ScriptsLayouts::class, Wassup_Module_Processor_ScriptsLayouts::MODULE_SCRIPT_HIGHLIGHTS] : 
        	[Wassup_Module_Processor_ScriptsLayouts::class, Wassup_Module_Processor_ScriptsLayouts::MODULE_SCRIPT_HIGHLIGHTSEMPTY];
    }
}
