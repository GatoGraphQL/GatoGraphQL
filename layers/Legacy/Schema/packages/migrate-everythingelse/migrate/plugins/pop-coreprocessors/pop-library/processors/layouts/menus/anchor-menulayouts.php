<?php

class PoP_Module_Processor_AnchorMenuLayouts extends PoP_Module_Processor_AnchorMenuLayoutsBase
{
    public const MODULE_LAYOUT_MENU_BUTTON = 'layout-menu-button';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_MENU_BUTTON],
        );
    }

    public function getItemClass(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_MENU_BUTTON:
                return 'btn btn-default btn-block';
        }
    
        return parent::getItemClass($module, $props);
    }
}


