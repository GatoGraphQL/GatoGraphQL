<?php

class GD_ContentCreation_Module_Processor_QuicklinkButtonGroups extends PoP_Module_Processor_ControlButtonGroupsBase
{
    public final const COMPONENT_QUICKLINKBUTTONGROUP_POSTEDIT = 'quicklinkbuttongroup-postedit';
    public final const COMPONENT_QUICKLINKBUTTONGROUP_POSTVIEW = 'quicklinkbuttongroup-postview';
    public final const COMPONENT_QUICKLINKBUTTONGROUP_POSTPREVIEW = 'quicklinkbuttongroup-postpreview';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_QUICKLINKBUTTONGROUP_POSTEDIT,
            self::COMPONENT_QUICKLINKBUTTONGROUP_POSTVIEW,
            self::COMPONENT_QUICKLINKBUTTONGROUP_POSTPREVIEW,
        );
    }

    public function getSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getSubcomponents($component);
    
        switch ($component->name) {
            case self::COMPONENT_QUICKLINKBUTTONGROUP_POSTEDIT:
                $ret[] = [GD_ContentCreation_Module_Processor_Buttons::class, GD_ContentCreation_Module_Processor_Buttons::COMPONENT_BUTTON_POSTEDIT];
                break;

            case self::COMPONENT_QUICKLINKBUTTONGROUP_POSTVIEW:
                $ret[] = [GD_ContentCreation_Module_Processor_ButtonWrappers::class, GD_ContentCreation_Module_Processor_ButtonWrappers::COMPONENT_BUTTONWRAPPER_POSTVIEW];
                break;

            case self::COMPONENT_QUICKLINKBUTTONGROUP_POSTPREVIEW:
                $ret[] = [GD_ContentCreation_Module_Processor_ButtonWrappers::class, GD_ContentCreation_Module_Processor_ButtonWrappers::COMPONENT_BUTTONWRAPPER_POSTPREVIEW];
                break;
        }
        
        return $ret;
    }
}


