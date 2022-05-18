<?php

class PoP_Module_Processor_DropdownButtonControls extends PoP_Module_Processor_DropdownButtonControlsBase
{
    public final const MODULE_DROPDOWNBUTTONCONTROL_SHARE = 'dropdownbuttoncontrol-share';
    public final const MODULE_DROPDOWNBUTTONCONTROL_RESULTSSHARE = 'dropdownbuttoncontrol-resultsshare';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_DROPDOWNBUTTONCONTROL_SHARE],
            [self::class, self::COMPONENT_DROPDOWNBUTTONCONTROL_RESULTSSHARE],
        );
    }

    public function getSubComponents(array $component): array
    {
        $ret = parent::getSubComponents($component);
    
        switch ($component[1]) {
            case self::COMPONENT_DROPDOWNBUTTONCONTROL_SHARE:
            case self::COMPONENT_DROPDOWNBUTTONCONTROL_RESULTSSHARE:
                $components = array();
                $components[] = [PoP_Module_Processor_AnchorControls::class, PoP_Module_Processor_AnchorControls::COMPONENT_ANCHORCONTROL_PRINT];

                // Allow PoP Generic Forms Processors to add modules
                $components = \PoP\Root\App::applyFilters(
                    'PoP_Module_Processor_DropdownButtonControls:modules:share',
                    $components,
                    $component
                );
                $ret = array_merge(
                    $ret,
                    $components
                );
                break;
        }
        
        return $ret;
    }

    public function getBtnClass(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_DROPDOWNBUTTONCONTROL_SHARE:
            case self::COMPONENT_DROPDOWNBUTTONCONTROL_RESULTSSHARE:
                return 'btn btn-compact btn-link';
        }
        
        return parent::getBtnClass($component);
    }

    public function getFontawesome(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_DROPDOWNBUTTONCONTROL_SHARE:
            case self::COMPONENT_DROPDOWNBUTTONCONTROL_RESULTSSHARE:
                return 'fa-share';
        }

        return parent::getFontawesome($component, $props);
    }
}


