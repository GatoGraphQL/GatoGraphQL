<?php

class GD_Wassup_Module_Processor_DropdownButtonControls extends PoP_Module_Processor_DropdownButtonControlsBase
{
    public final const MODULE_DROPDOWNBUTTONCONTROL_CLOSETOGGLE = 'dropdownbuttoncontrol-closetoggle';
    public final const MODULE_DROPDOWNBUTTONCONTROL_QUICKVIEWCLOSETOGGLE = 'dropdownbuttoncontrol-quickviewclosetoggle';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DROPDOWNBUTTONCONTROL_CLOSETOGGLE],
            [self::class, self::MODULE_DROPDOWNBUTTONCONTROL_QUICKVIEWCLOSETOGGLE],
        );
    }

    public function getSubmodules(array $module): array
    {
        $ret = parent::getSubmodules($module);
    
        switch ($module[1]) {
            case self::MODULE_DROPDOWNBUTTONCONTROL_CLOSETOGGLE:
                $ret[] = [GD_Wassup_Module_Processor_AnchorControls::class, GD_Wassup_Module_Processor_AnchorControls::MODULE_ANCHORCONTROL_TOGGLESIDEINFO];
                $ret[] = [GD_Wassup_Module_Processor_AnchorControls::class, GD_Wassup_Module_Processor_AnchorControls::MODULE_ANCHORCONTROL_TOGGLESIDEINFOXS];
                $ret[] = [PoP_Module_Processor_AnchorControls::class, PoP_Module_Processor_AnchorControls::MODULE_ANCHORCONTROL_CLOSEPAGE];
                break;

            case self::MODULE_DROPDOWNBUTTONCONTROL_QUICKVIEWCLOSETOGGLE:
                $ret[] = [GD_Wassup_Module_Processor_AnchorControls::class, GD_Wassup_Module_Processor_AnchorControls::MODULE_ANCHORCONTROL_TOGGLEQUICKVIEWINFO];
                $ret[] = [PoP_Module_Processor_AnchorControls::class, PoP_Module_Processor_AnchorControls::MODULE_ANCHORCONTROL_CLOSEPAGE];
                break;
        }
        
        return $ret;
    }

    public function getBtnClass(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_DROPDOWNBUTTONCONTROL_CLOSETOGGLE:
            case self::MODULE_DROPDOWNBUTTONCONTROL_QUICKVIEWCLOSETOGGLE:
                return 'btn btn-link';
        }
        
        return parent::getBtnClass($module);
    }

    public function getFontawesome(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_DROPDOWNBUTTONCONTROL_CLOSETOGGLE:
            case self::MODULE_DROPDOWNBUTTONCONTROL_QUICKVIEWCLOSETOGGLE:
                return 'fa-ellipsis-v';
        }

        return parent::getFontawesome($module, $props);
    }
}


