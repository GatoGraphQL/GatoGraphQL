<?php

class PoP_Module_Processor_DropdownMenus extends PoP_Module_Processor_DropdownMenusBase
{
    public final const COMPONENT_DROPDOWNMENU = 'dropdownmenu';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_DROPDOWNMENU,
        );
    }
}


