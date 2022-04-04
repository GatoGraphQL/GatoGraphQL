<?php

class PoP_Module_Processor_IndentMenus extends PoP_Module_Processor_IndentMenusBase
{
    public final const MODULE_INDENTMENU = 'indentmenu';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_INDENTMENU],
        );
    }
}


