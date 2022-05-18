<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_DropdownButtonMenuLayouts extends PoP_Module_Processor_DropdownButtonMenuLayoutsBase
{
    public final const MODULE_LAYOUT_MENU_DROPDOWNBUTTON_TOP = 'layout-menu-dropdownbutton-top';
    public final const MODULE_LAYOUT_MENU_DROPDOWNBUTTON_SIDE = 'layout-menu-dropdownbutton-side';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_LAYOUT_MENU_DROPDOWNBUTTON_TOP],
            [self::class, self::COMPONENT_LAYOUT_MENU_DROPDOWNBUTTON_SIDE],
        );
    }

    public function getBtnClass(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_MENU_DROPDOWNBUTTON_TOP:
                return 'btn btn-warning';

            case self::COMPONENT_LAYOUT_MENU_DROPDOWNBUTTON_SIDE:
                return 'btn btn-warning btn-block btn-addnew-side';
        }
    
        return parent::getBtnClass($component, $props);
    }

    public function getBtnTitle(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_MENU_DROPDOWNBUTTON_TOP:
                return '<i class="fa fa-fw fa-plus"></i>';
            
            case self::COMPONENT_LAYOUT_MENU_DROPDOWNBUTTON_SIDE:
                return '<i class="fa fa-fw fa-plus"></i>'.TranslationAPIFacade::getInstance()->__('Add new', 'pop-coreprocessors').' <span class="caret"></span>';
        }
    
        return parent::getBtnTitle($component, $props);
    }

    public function getDropdownbtnClass(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_MENU_DROPDOWNBUTTON_TOP:
                return 'dropdown';
        }
    
        return parent::getDropdownbtnClass($component, $props);
    }

    public function innerList(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_MENU_DROPDOWNBUTTON_TOP:
                return true;
        }
    
        return parent::innerList($component, $props);
    }
}


