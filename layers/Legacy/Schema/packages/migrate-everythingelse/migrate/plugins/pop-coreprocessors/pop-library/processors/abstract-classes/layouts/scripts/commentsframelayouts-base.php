<?php

abstract class PoP_Module_Processor_CommentsScriptFrameLayoutsBase extends PoP_Module_Processor_ScriptFrameLayoutsBase
{
    public function doAppend(array $module)
    {
        return true;
    }

    public function getScriptSubmodule(array $module)
    {
        return $this->doAppend($module) ? 
        	[PoP_Module_Processor_ScriptsLayouts::class, PoP_Module_Processor_ScriptsLayouts::MODULE_SCRIPT_COMMENTS] : 
        	[PoP_Module_Processor_ScriptsLayouts::class, PoP_Module_Processor_ScriptsLayouts::MODULE_SCRIPT_COMMENTSEMPTY];
    }
}
