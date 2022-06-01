<?php

class GD_EM_Module_Processor_DropdownButtonQuicklinks extends PoP_Module_Processor_DropdownButtonControlsBase
{
    public final const COMPONENT_EM_DROPDOWNBUTTONQUICKLINK_DOWNLOADLINKS = 'em-dropdownbuttonquicklink-downloadlinks';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_EM_DROPDOWNBUTTONQUICKLINK_DOWNLOADLINKS,
        );
    }

    /**
     * @return \PoP\ComponentModel\Component\Component[]
     */
    public function getSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getSubcomponents($component);

        switch ($component->name) {
            case self::COMPONENT_EM_DROPDOWNBUTTONQUICKLINK_DOWNLOADLINKS:
                $ret[] = [GD_EM_Module_Processor_Buttons::class, GD_EM_Module_Processor_Buttons::COMPONENT_EM_BUTTON_GOOGLECALENDAR];
                $ret[] = [GD_EM_Module_Processor_Buttons::class, GD_EM_Module_Processor_Buttons::COMPONENT_EM_BUTTON_ICAL];
                break;
        }
        
        return $ret;
    }

    public function getBtnClass(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_EM_DROPDOWNBUTTONQUICKLINK_DOWNLOADLINKS:
                return 'btn btn-compact btn-link';
        }
        
        return parent::getBtnClass($component);
    }

    public function getFontawesome(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_EM_DROPDOWNBUTTONQUICKLINK_DOWNLOADLINKS:
                return 'fa-thumb-tack';
        }

        return parent::getFontawesome($component, $props);
    }
}


