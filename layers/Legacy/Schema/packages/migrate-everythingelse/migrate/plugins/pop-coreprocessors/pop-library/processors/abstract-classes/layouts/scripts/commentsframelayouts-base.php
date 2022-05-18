<?php

abstract class PoP_Module_Processor_CommentsScriptFrameLayoutsBase extends PoP_Module_Processor_ScriptFrameLayoutsBase
{
    public function doAppend(array $component)
    {
        return true;
    }

    public function getScriptSubmodule(array $component)
    {
        return $this->doAppend($component) ? 
        	[PoP_Module_Processor_ScriptsLayouts::class, PoP_Module_Processor_ScriptsLayouts::COMPONENT_SCRIPT_COMMENTS] : 
        	[PoP_Module_Processor_ScriptsLayouts::class, PoP_Module_Processor_ScriptsLayouts::COMPONENT_SCRIPT_COMMENTSEMPTY];
    }
}
