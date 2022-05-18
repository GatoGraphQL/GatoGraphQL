<?php

class GD_EM_Module_Processor_DropdownButtonQuicklinks extends PoP_Module_Processor_DropdownButtonControlsBase
{
    public final const MODULE_EM_DROPDOWNBUTTONQUICKLINK_DOWNLOADLINKS = 'em-dropdownbuttonquicklink-downloadlinks';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_EM_DROPDOWNBUTTONQUICKLINK_DOWNLOADLINKS],
        );
    }

    public function getSubComponents(array $component): array
    {
        $ret = parent::getSubComponents($component);

        switch ($component[1]) {
            case self::COMPONENT_EM_DROPDOWNBUTTONQUICKLINK_DOWNLOADLINKS:
                $ret[] = [GD_EM_Module_Processor_Buttons::class, GD_EM_Module_Processor_Buttons::COMPONENT_EM_BUTTON_GOOGLECALENDAR];
                $ret[] = [GD_EM_Module_Processor_Buttons::class, GD_EM_Module_Processor_Buttons::COMPONENT_EM_BUTTON_ICAL];
                break;
        }
        
        return $ret;
    }

    public function getBtnClass(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_EM_DROPDOWNBUTTONQUICKLINK_DOWNLOADLINKS:
                return 'btn btn-compact btn-link';
        }
        
        return parent::getBtnClass($component);
    }

    public function getFontawesome(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_EM_DROPDOWNBUTTONQUICKLINK_DOWNLOADLINKS:
                return 'fa-thumb-tack';
        }

        return parent::getFontawesome($component, $props);
    }
}


