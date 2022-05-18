<?php

class PoP_Module_Processor_AnchorMenuLayouts extends PoP_Module_Processor_AnchorMenuLayoutsBase
{
    public final const MODULE_LAYOUT_MENU_BUTTON = 'layout-menu-button';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_MENU_BUTTON],
        );
    }

    public function getItemClass(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_LAYOUT_MENU_BUTTON:
                return 'btn btn-default btn-block';
        }
    
        return parent::getItemClass($componentVariation, $props);
    }
}


