<?php

class PoP_Module_Processor_IndentMenuLayouts extends PoP_Module_Processor_IndentMenuLayoutsBase
{
    public final const MODULE_LAYOUT_MENU_INDENT = 'layout-menu-indent';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_MENU_INDENT],
        );
    }
}



