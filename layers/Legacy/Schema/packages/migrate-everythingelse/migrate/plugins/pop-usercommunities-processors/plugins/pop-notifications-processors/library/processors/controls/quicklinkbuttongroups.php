<?php

class GD_URE_AAL_Module_Processor_QuicklinkButtonGroups extends PoP_Module_Processor_ControlButtonGroupsBase
{
    public final const COMPONENT_UREAAL_QUICKLINKBUTTONGROUP_EDITUSERMEMBERSHIP = 'ure-aal-quicklinkbuttongroup-editusermembership';
    public final const COMPONENT_UREAAL_QUICKLINKBUTTONGROUP_VIEWALLMEMBERS = 'ure-aal-quicklinkbuttongroup-viewallmembers';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_UREAAL_QUICKLINKBUTTONGROUP_EDITUSERMEMBERSHIP,
            self::COMPONENT_UREAAL_QUICKLINKBUTTONGROUP_VIEWALLMEMBERS,
        );
    }

    public function getSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getSubcomponents($component);
    
        switch ($component->name) {
            case self::COMPONENT_UREAAL_QUICKLINKBUTTONGROUP_EDITUSERMEMBERSHIP:
                $ret[] = [Custom_URE_AAL_PoPProcessors_Module_Processor_ButtonWrappers::class, Custom_URE_AAL_PoPProcessors_Module_Processor_ButtonWrappers::COMPONENT_UREAAL_BUTTONWRAPPER_EDITMEMBERSHIP];
                break;

            case self::COMPONENT_UREAAL_QUICKLINKBUTTONGROUP_VIEWALLMEMBERS:
                $ret[] = [Custom_URE_AAL_PoPProcessors_Module_Processor_ButtonWrappers::class, Custom_URE_AAL_PoPProcessors_Module_Processor_ButtonWrappers::COMPONENT_UREAAL_BUTTONWRAPPER_VIEWALLMEMBERS];
                break;
        }
        
        return $ret;
    }
}


