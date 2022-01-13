<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

class PoP_Module_Processor_DropdownButtonControls extends PoP_Module_Processor_DropdownButtonControlsBase
{
    public const MODULE_DROPDOWNBUTTONCONTROL_SHARE = 'dropdownbuttoncontrol-share';
    public const MODULE_DROPDOWNBUTTONCONTROL_RESULTSSHARE = 'dropdownbuttoncontrol-resultsshare';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DROPDOWNBUTTONCONTROL_SHARE],
            [self::class, self::MODULE_DROPDOWNBUTTONCONTROL_RESULTSSHARE],
        );
    }

    public function getSubmodules(array $module): array
    {
        $ret = parent::getSubmodules($module);
    
        switch ($module[1]) {
            case self::MODULE_DROPDOWNBUTTONCONTROL_SHARE:
            case self::MODULE_DROPDOWNBUTTONCONTROL_RESULTSSHARE:
                $modules = array();
                $modules[] = [PoP_Module_Processor_AnchorControls::class, PoP_Module_Processor_AnchorControls::MODULE_ANCHORCONTROL_PRINT];

                // Allow PoP Generic Forms Processors to add modules
                $modules = \PoP\Root\App::getHookManager()->applyFilters(
                    'PoP_Module_Processor_DropdownButtonControls:modules:share',
                    $modules,
                    $module
                );
                $ret = array_merge(
                    $ret,
                    $modules
                );
                break;
        }
        
        return $ret;
    }

    public function getBtnClass(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_DROPDOWNBUTTONCONTROL_SHARE:
            case self::MODULE_DROPDOWNBUTTONCONTROL_RESULTSSHARE:
                return 'btn btn-compact btn-link';
        }
        
        return parent::getBtnClass($module);
    }

    public function getFontawesome(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_DROPDOWNBUTTONCONTROL_SHARE:
            case self::MODULE_DROPDOWNBUTTONCONTROL_RESULTSSHARE:
                return 'fa-share';
        }

        return parent::getFontawesome($module, $props);
    }
}


