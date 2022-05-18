<?php

class PoP_Module_Processor_DropdownMenus extends PoP_Module_Processor_DropdownMenusBase
{
    public final const MODULE_DROPDOWNMENU = 'dropdownmenu';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DROPDOWNMENU],
        );
    }
}


