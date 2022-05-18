<?php

class GD_URE_AAL_Module_Processor_QuicklinkButtonGroups extends PoP_Module_Processor_ControlButtonGroupsBase
{
    public final const MODULE_UREAAL_QUICKLINKBUTTONGROUP_EDITUSERMEMBERSHIP = 'ure-aal-quicklinkbuttongroup-editusermembership';
    public final const MODULE_UREAAL_QUICKLINKBUTTONGROUP_VIEWALLMEMBERS = 'ure-aal-quicklinkbuttongroup-viewallmembers';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_UREAAL_QUICKLINKBUTTONGROUP_EDITUSERMEMBERSHIP],
            [self::class, self::COMPONENT_UREAAL_QUICKLINKBUTTONGROUP_VIEWALLMEMBERS],
        );
    }

    public function getSubComponents(array $component): array
    {
        $ret = parent::getSubComponents($component);
    
        switch ($component[1]) {
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


