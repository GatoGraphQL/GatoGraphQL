<?php

class PoP_Module_Processor_DropdownButtonControls extends PoP_Module_Processor_DropdownButtonControlsBase
{
    public final const COMPONENT_DROPDOWNBUTTONCONTROL_SHARE = 'dropdownbuttoncontrol-share';
    public final const COMPONENT_DROPDOWNBUTTONCONTROL_RESULTSSHARE = 'dropdownbuttoncontrol-resultsshare';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_DROPDOWNBUTTONCONTROL_SHARE,
            self::COMPONENT_DROPDOWNBUTTONCONTROL_RESULTSSHARE,
        );
    }

    public function getSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getSubcomponents($component);
    
        switch ($component->name) {
            case self::COMPONENT_DROPDOWNBUTTONCONTROL_SHARE:
            case self::COMPONENT_DROPDOWNBUTTONCONTROL_RESULTSSHARE:
                $components = array();
                $components[] = [PoP_Module_Processor_AnchorControls::class, PoP_Module_Processor_AnchorControls::COMPONENT_ANCHORCONTROL_PRINT];

                // Allow PoP Generic Forms Processors to add modules
                $components = \PoP\Root\App::applyFilters(
                    'PoP_Module_Processor_DropdownButtonControls:components:share',
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

    public function getBtnClass(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_DROPDOWNBUTTONCONTROL_SHARE:
            case self::COMPONENT_DROPDOWNBUTTONCONTROL_RESULTSSHARE:
                return 'btn btn-compact btn-link';
        }
        
        return parent::getBtnClass($component);
    }

    public function getFontawesome(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_DROPDOWNBUTTONCONTROL_SHARE:
            case self::COMPONENT_DROPDOWNBUTTONCONTROL_RESULTSSHARE:
                return 'fa-share';
        }

        return parent::getFontawesome($component, $props);
    }
}


