<?php

abstract class PoP_Module_Processor_SingleCommentScriptFrameLayoutsBase extends PoP_Module_Processor_ScriptFrameLayoutsBase
{
    public function getScriptSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        return [PoP_Module_Processor_ScriptsLayouts::class, PoP_Module_Processor_ScriptsLayouts::COMPONENT_SCRIPT_SINGLECOMMENT];
    }
}
