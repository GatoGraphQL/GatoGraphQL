<?php

abstract class PoP_Module_Processor_ReferencesScriptFrameLayoutsBase extends PoP_Module_Processor_ScriptFrameLayoutsBase
{
    public function doAppend(array $component)
    {
        return true;
    }

    public function getScriptSubmodule(array $component)
    {
        return $this->doAppend($component) ? 
        	[PoP_Module_Processor_ScriptsLayouts::class, PoP_Module_Processor_ScriptsLayouts::MODULE_SCRIPT_REFERENCES] : 
        	[PoP_Module_Processor_ScriptsLayouts::class, PoP_Module_Processor_ScriptsLayouts::MODULE_SCRIPT_REFERENCESEMPTY];
    }
}
