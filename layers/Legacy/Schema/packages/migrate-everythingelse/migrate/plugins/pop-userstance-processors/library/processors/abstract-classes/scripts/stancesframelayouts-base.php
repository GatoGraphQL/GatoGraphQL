<?php

abstract class PoP_Module_Processor_StanceReferencesScriptFrameLayoutsBase extends PoP_Module_Processor_ScriptFrameLayoutsBase
{
    public function doAppend(array $componentVariation)
    {
        return true;
    }

    public function getScriptSubmodule(array $componentVariation)
    {
        return $this->doAppend($componentVariation) ? 
        	[UserStance_Module_Processor_ScriptsLayouts::class, UserStance_Module_Processor_ScriptsLayouts::MODULE_SCRIPT_STANCES] : 
        	[UserStance_Module_Processor_ScriptsLayouts::class, UserStance_Module_Processor_ScriptsLayouts::MODULE_SCRIPT_STANCESEMPTY];
    }
}
