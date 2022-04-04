<?php

class GD_EM_Module_Processor_DropdownButtonQuicklinks extends PoP_Module_Processor_DropdownButtonControlsBase
{
    public final const MODULE_EM_DROPDOWNBUTTONQUICKLINK_DOWNLOADLINKS = 'em-dropdownbuttonquicklink-downloadlinks';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_EM_DROPDOWNBUTTONQUICKLINK_DOWNLOADLINKS],
        );
    }

    public function getSubmodules(array $module): array
    {
        $ret = parent::getSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_EM_DROPDOWNBUTTONQUICKLINK_DOWNLOADLINKS:
                $ret[] = [GD_EM_Module_Processor_Buttons::class, GD_EM_Module_Processor_Buttons::MODULE_EM_BUTTON_GOOGLECALENDAR];
                $ret[] = [GD_EM_Module_Processor_Buttons::class, GD_EM_Module_Processor_Buttons::MODULE_EM_BUTTON_ICAL];
                break;
        }
        
        return $ret;
    }

    public function getBtnClass(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_EM_DROPDOWNBUTTONQUICKLINK_DOWNLOADLINKS:
                return 'btn btn-compact btn-link';
        }
        
        return parent::getBtnClass($module);
    }

    public function getFontawesome(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_EM_DROPDOWNBUTTONQUICKLINK_DOWNLOADLINKS:
                return 'fa-thumb-tack';
        }

        return parent::getFontawesome($module, $props);
    }
}


