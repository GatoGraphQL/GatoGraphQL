<?php

abstract class PoP_Module_Processor_DropdownMenusBase extends PoP_Module_Processor_ContentsBase
{
    public function getInnerSubmodule(array $componentVariation)
    {
        return [PoP_Module_Processor_MenuContentInners::class, PoP_Module_Processor_MenuContentInners::MODULE_CONTENTINNER_MENU_DROPDOWN];
    }
}
