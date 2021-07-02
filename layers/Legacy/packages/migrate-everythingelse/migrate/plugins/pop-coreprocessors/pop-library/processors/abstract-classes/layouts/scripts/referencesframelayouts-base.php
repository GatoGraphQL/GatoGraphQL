<?php

abstract class PoP_Module_Processor_ReferencesScriptFrameLayoutsBase extends PoP_Module_Processor_ScriptFrameLayoutsBase
{
    public function doAppend(array $module)
    {
        return true;
    }

    public function getScriptSubmodule(array $module)
    {
        return $this->doAppend($module) ? 
        	[PoP_Module_Processor_ScriptsLayouts::class, PoP_Module_Processor_ScriptsLayouts::MODULE_SCRIPT_REFERENCES] : 
        	[PoP_Module_Processor_ScriptsLayouts::class, PoP_Module_Processor_ScriptsLayouts::MODULE_SCRIPT_REFERENCESEMPTY];
    }
}
