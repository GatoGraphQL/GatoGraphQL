<?php

abstract class PoP_Module_Processor_ReferencesScriptFrameLayoutsBase extends PoP_Module_Processor_ScriptFrameLayoutsBase
{
    public function doAppend(array $componentVariation)
    {
        return true;
    }

    public function getScriptSubmodule(array $componentVariation)
    {
        return $this->doAppend($componentVariation) ? 
        	[PoP_Module_Processor_ScriptsLayouts::class, PoP_Module_Processor_ScriptsLayouts::MODULE_SCRIPT_REFERENCES] : 
        	[PoP_Module_Processor_ScriptsLayouts::class, PoP_Module_Processor_ScriptsLayouts::MODULE_SCRIPT_REFERENCESEMPTY];
    }
}
