<?php

abstract class PoP_Module_Processor_CommentsScriptFrameLayoutsBase extends PoP_Module_Processor_ScriptFrameLayoutsBase
{
    public function doAppend(\PoP\ComponentModel\Component\Component $component)
    {
        return true;
    }

    public function getScriptSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        return $this->doAppend($component) ? 
        	[PoP_Module_Processor_ScriptsLayouts::class, PoP_Module_Processor_ScriptsLayouts::COMPONENT_SCRIPT_COMMENTS] : 
        	[PoP_Module_Processor_ScriptsLayouts::class, PoP_Module_Processor_ScriptsLayouts::COMPONENT_SCRIPT_COMMENTSEMPTY];
    }
}
