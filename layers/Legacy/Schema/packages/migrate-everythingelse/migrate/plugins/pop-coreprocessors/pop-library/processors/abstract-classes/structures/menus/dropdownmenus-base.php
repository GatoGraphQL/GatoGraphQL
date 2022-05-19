<?php

abstract class PoP_Module_Processor_DropdownMenusBase extends PoP_Module_Processor_ContentsBase
{
    public function getInnerSubcomponent(array $component)
    {
        return [PoP_Module_Processor_MenuContentInners::class, PoP_Module_Processor_MenuContentInners::COMPONENT_CONTENTINNER_MENU_DROPDOWN];
    }
}
