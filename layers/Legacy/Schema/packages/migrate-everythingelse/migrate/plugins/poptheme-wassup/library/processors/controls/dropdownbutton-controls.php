<?php

class GD_Wassup_Module_Processor_DropdownButtonControls extends PoP_Module_Processor_DropdownButtonControlsBase
{
    public final const MODULE_DROPDOWNBUTTONCONTROL_CLOSETOGGLE = 'dropdownbuttoncontrol-closetoggle';
    public final const MODULE_DROPDOWNBUTTONCONTROL_QUICKVIEWCLOSETOGGLE = 'dropdownbuttoncontrol-quickviewclosetoggle';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_DROPDOWNBUTTONCONTROL_CLOSETOGGLE],
            [self::class, self::COMPONENT_DROPDOWNBUTTONCONTROL_QUICKVIEWCLOSETOGGLE],
        );
    }

    public function getSubComponents(array $component): array
    {
        $ret = parent::getSubComponents($component);
    
        switch ($component[1]) {
            case self::COMPONENT_DROPDOWNBUTTONCONTROL_CLOSETOGGLE:
                $ret[] = [GD_Wassup_Module_Processor_AnchorControls::class, GD_Wassup_Module_Processor_AnchorControls::COMPONENT_ANCHORCONTROL_TOGGLESIDEINFO];
                $ret[] = [GD_Wassup_Module_Processor_AnchorControls::class, GD_Wassup_Module_Processor_AnchorControls::COMPONENT_ANCHORCONTROL_TOGGLESIDEINFOXS];
                $ret[] = [PoP_Module_Processor_AnchorControls::class, PoP_Module_Processor_AnchorControls::COMPONENT_ANCHORCONTROL_CLOSEPAGE];
                break;

            case self::COMPONENT_DROPDOWNBUTTONCONTROL_QUICKVIEWCLOSETOGGLE:
                $ret[] = [GD_Wassup_Module_Processor_AnchorControls::class, GD_Wassup_Module_Processor_AnchorControls::COMPONENT_ANCHORCONTROL_TOGGLEQUICKVIEWINFO];
                $ret[] = [PoP_Module_Processor_AnchorControls::class, PoP_Module_Processor_AnchorControls::COMPONENT_ANCHORCONTROL_CLOSEPAGE];
                break;
        }
        
        return $ret;
    }

    public function getBtnClass(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_DROPDOWNBUTTONCONTROL_CLOSETOGGLE:
            case self::COMPONENT_DROPDOWNBUTTONCONTROL_QUICKVIEWCLOSETOGGLE:
                return 'btn btn-link';
        }
        
        return parent::getBtnClass($component);
    }

    public function getFontawesome(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_DROPDOWNBUTTONCONTROL_CLOSETOGGLE:
            case self::COMPONENT_DROPDOWNBUTTONCONTROL_QUICKVIEWCLOSETOGGLE:
                return 'fa-ellipsis-v';
        }

        return parent::getFontawesome($component, $props);
    }
}


