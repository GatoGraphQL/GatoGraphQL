<?php

abstract class PoP_Module_Processor_StanceReferencesScriptFrameLayoutsBase extends PoP_Module_Processor_ScriptFrameLayoutsBase
{
    public function doAppend(array $component)
    {
        return true;
    }

    public function getScriptSubmodule(array $component)
    {
        return $this->doAppend($component) ? 
        	[UserStance_Module_Processor_ScriptsLayouts::class, UserStance_Module_Processor_ScriptsLayouts::COMPONENT_SCRIPT_STANCES] : 
        	[UserStance_Module_Processor_ScriptsLayouts::class, UserStance_Module_Processor_ScriptsLayouts::COMPONENT_SCRIPT_STANCESEMPTY];
    }
}
