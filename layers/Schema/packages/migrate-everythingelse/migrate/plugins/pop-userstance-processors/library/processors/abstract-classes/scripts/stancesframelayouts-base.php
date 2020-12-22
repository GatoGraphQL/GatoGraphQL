<?php

abstract class PoP_Module_Processor_StanceReferencesScriptFrameLayoutsBase extends PoP_Module_Processor_ScriptFrameLayoutsBase
{
    public function doAppend(array $module)
    {
        return true;
    }

    public function getScriptSubmodule(array $module)
    {
        return $this->doAppend($module) ? 
        	[UserStance_Module_Processor_ScriptsLayouts::class, UserStance_Module_Processor_ScriptsLayouts::MODULE_SCRIPT_STANCES] : 
        	[UserStance_Module_Processor_ScriptsLayouts::class, UserStance_Module_Processor_ScriptsLayouts::MODULE_SCRIPT_STANCESEMPTY];
    }
}
