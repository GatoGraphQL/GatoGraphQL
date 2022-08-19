<?php

class PoP_Module_Processor_IndentMenus extends PoP_Module_Processor_IndentMenusBase
{
    public final const COMPONENT_INDENTMENU = 'indentmenu';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_INDENTMENU,
        );
    }
}


