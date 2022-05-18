<?php

abstract class PoP_Module_Processor_IndentMenusBase extends PoP_Module_Processor_ContentsBase
{
    public function getInnerSubmodule(array $component)
    {
        return [PoP_Module_Processor_MenuContentInners::class, PoP_Module_Processor_MenuContentInners::COMPONENT_CONTENTINNER_MENU_INDENT];
    }
}
