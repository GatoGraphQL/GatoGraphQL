<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_DropdownButtonMenuLayouts extends PoP_Module_Processor_DropdownButtonMenuLayoutsBase
{
    public final const COMPONENT_LAYOUT_MENU_DROPDOWNBUTTON_TOP = 'layout-menu-dropdownbutton-top';
    public final const COMPONENT_LAYOUT_MENU_DROPDOWNBUTTON_SIDE = 'layout-menu-dropdownbutton-side';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_LAYOUT_MENU_DROPDOWNBUTTON_TOP,
            self::COMPONENT_LAYOUT_MENU_DROPDOWNBUTTON_SIDE,
        );
    }

    public function getBtnClass(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_LAYOUT_MENU_DROPDOWNBUTTON_TOP:
                return 'btn btn-warning';

            case self::COMPONENT_LAYOUT_MENU_DROPDOWNBUTTON_SIDE:
                return 'btn btn-warning btn-block btn-addnew-side';
        }
    
        return parent::getBtnClass($component, $props);
    }

    public function getBtnTitle(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_LAYOUT_MENU_DROPDOWNBUTTON_TOP:
                return '<i class="fa fa-fw fa-plus"></i>';
            
            case self::COMPONENT_LAYOUT_MENU_DROPDOWNBUTTON_SIDE:
                return '<i class="fa fa-fw fa-plus"></i>'.TranslationAPIFacade::getInstance()->__('Add new', 'pop-coreprocessors').' <span class="caret"></span>';
        }
    
        return parent::getBtnTitle($component, $props);
    }

    public function getDropdownbtnClass(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_LAYOUT_MENU_DROPDOWNBUTTON_TOP:
                return 'dropdown';
        }
    
        return parent::getDropdownbtnClass($component, $props);
    }

    public function innerList(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_LAYOUT_MENU_DROPDOWNBUTTON_TOP:
                return true;
        }
    
        return parent::innerList($component, $props);
    }
}


