<?php

class GD_Wassup_Module_Processor_DropdownButtonControls extends PoP_Module_Processor_DropdownButtonControlsBase
{
    public final const COMPONENT_DROPDOWNBUTTONCONTROL_CLOSETOGGLE = 'dropdownbuttoncontrol-closetoggle';
    public final const COMPONENT_DROPDOWNBUTTONCONTROL_QUICKVIEWCLOSETOGGLE = 'dropdownbuttoncontrol-quickviewclosetoggle';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_DROPDOWNBUTTONCONTROL_CLOSETOGGLE,
            self::COMPONENT_DROPDOWNBUTTONCONTROL_QUICKVIEWCLOSETOGGLE,
        );
    }

    /**
     * @return \PoP\ComponentModel\Component\Component[]
     */
    public function getSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getSubcomponents($component);
    
        switch ($component->name) {
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

    public function getBtnClass(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_DROPDOWNBUTTONCONTROL_CLOSETOGGLE:
            case self::COMPONENT_DROPDOWNBUTTONCONTROL_QUICKVIEWCLOSETOGGLE:
                return 'btn btn-link';
        }
        
        return parent::getBtnClass($component);
    }

    public function getFontawesome(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_DROPDOWNBUTTONCONTROL_CLOSETOGGLE:
            case self::COMPONENT_DROPDOWNBUTTONCONTROL_QUICKVIEWCLOSETOGGLE:
                return 'fa-ellipsis-v';
        }

        return parent::getFontawesome($component, $props);
    }
}


