<?php

class PoP_Module_Processor_DropdownMenuLayouts extends PoP_Module_Processor_DropdownMenuLayoutsBase
{
    public const MODULE_LAYOUT_MENU_DROPDOWN = 'layout-menu-dropdown';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_MENU_DROPDOWN],
        );
    }
}



