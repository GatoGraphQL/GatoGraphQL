<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_DropdownButtonMenuLayouts extends PoP_Module_Processor_DropdownButtonMenuLayoutsBase
{
    public final const MODULE_LAYOUT_MENU_DROPDOWNBUTTON_TOP = 'layout-menu-dropdownbutton-top';
    public final const MODULE_LAYOUT_MENU_DROPDOWNBUTTON_SIDE = 'layout-menu-dropdownbutton-side';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_MENU_DROPDOWNBUTTON_TOP],
            [self::class, self::MODULE_LAYOUT_MENU_DROPDOWNBUTTON_SIDE],
        );
    }

    public function getBtnClass(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_MENU_DROPDOWNBUTTON_TOP:
                return 'btn btn-warning';

            case self::MODULE_LAYOUT_MENU_DROPDOWNBUTTON_SIDE:
                return 'btn btn-warning btn-block btn-addnew-side';
        }
    
        return parent::getBtnClass($module, $props);
    }

    public function getBtnTitle(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_MENU_DROPDOWNBUTTON_TOP:
                return '<i class="fa fa-fw fa-plus"></i>';
            
            case self::MODULE_LAYOUT_MENU_DROPDOWNBUTTON_SIDE:
                return '<i class="fa fa-fw fa-plus"></i>'.TranslationAPIFacade::getInstance()->__('Add new', 'pop-coreprocessors').' <span class="caret"></span>';
        }
    
        return parent::getBtnTitle($module, $props);
    }

    public function getDropdownbtnClass(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_MENU_DROPDOWNBUTTON_TOP:
                return 'dropdown';
        }
    
        return parent::getDropdownbtnClass($module, $props);
    }

    public function innerList(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_MENU_DROPDOWNBUTTON_TOP:
                return true;
        }
    
        return parent::innerList($module, $props);
    }
}


