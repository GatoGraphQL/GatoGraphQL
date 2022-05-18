<?php

class PoP_Module_Processor_IndentMenuLayouts extends PoP_Module_Processor_IndentMenuLayoutsBase
{
    public final const COMPONENT_LAYOUT_MENU_INDENT = 'layout-menu-indent';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_LAYOUT_MENU_INDENT],
        );
    }
}



