<?php

abstract class PoP_Module_Processor_CreateOrUpdateStanceButtonScriptFrameLayoutsBase extends PoP_Module_Processor_ScriptFrameLayoutsBase
{
    public function getScriptSubmodule(array $component)
    {
        return [PoP_Module_Processor_StanceScriptsLayouts::class, PoP_Module_Processor_StanceScriptsLayouts::MODULE_SCRIPT_CREATEORUPDATESTANCEBUTTON];
    }
}
