<?php

class PoP_Module_Processor_DropdownButtonControls extends PoP_Module_Processor_DropdownButtonControlsBase
{
    public final const MODULE_DROPDOWNBUTTONCONTROL_SHARE = 'dropdownbuttoncontrol-share';
    public final const MODULE_DROPDOWNBUTTONCONTROL_RESULTSSHARE = 'dropdownbuttoncontrol-resultsshare';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DROPDOWNBUTTONCONTROL_SHARE],
            [self::class, self::MODULE_DROPDOWNBUTTONCONTROL_RESULTSSHARE],
        );
    }

    public function getSubComponentVariations(array $componentVariation): array
    {
        $ret = parent::getSubComponentVariations($componentVariation);
    
        switch ($componentVariation[1]) {
            case self::MODULE_DROPDOWNBUTTONCONTROL_SHARE:
            case self::MODULE_DROPDOWNBUTTONCONTROL_RESULTSSHARE:
                $modules = array();
                $modules[] = [PoP_Module_Processor_AnchorControls::class, PoP_Module_Processor_AnchorControls::MODULE_ANCHORCONTROL_PRINT];

                // Allow PoP Generic Forms Processors to add modules
                $modules = \PoP\Root\App::applyFilters(
                    'PoP_Module_Processor_DropdownButtonControls:modules:share',
                    $modules,
                    $componentVariation
                );
                $ret = array_merge(
                    $ret,
                    $modules
                );
                break;
        }
        
        return $ret;
    }

    public function getBtnClass(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_DROPDOWNBUTTONCONTROL_SHARE:
            case self::MODULE_DROPDOWNBUTTONCONTROL_RESULTSSHARE:
                return 'btn btn-compact btn-link';
        }
        
        return parent::getBtnClass($componentVariation);
    }

    public function getFontawesome(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_DROPDOWNBUTTONCONTROL_SHARE:
            case self::MODULE_DROPDOWNBUTTONCONTROL_RESULTSSHARE:
                return 'fa-share';
        }

        return parent::getFontawesome($componentVariation, $props);
    }
}


