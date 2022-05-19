<?php

class PoP_Module_Processor_DropdownMenus extends PoP_Module_Processor_DropdownMenusBase
{
    public final const COMPONENT_DROPDOWNMENU = 'dropdownmenu';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_DROPDOWNMENU],
        );
    }
}


