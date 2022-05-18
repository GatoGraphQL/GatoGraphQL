<?php

class GD_URE_AAL_Module_Processor_QuicklinkButtonGroups extends PoP_Module_Processor_ControlButtonGroupsBase
{
    public final const MODULE_UREAAL_QUICKLINKBUTTONGROUP_EDITUSERMEMBERSHIP = 'ure-aal-quicklinkbuttongroup-editusermembership';
    public final const MODULE_UREAAL_QUICKLINKBUTTONGROUP_VIEWALLMEMBERS = 'ure-aal-quicklinkbuttongroup-viewallmembers';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_UREAAL_QUICKLINKBUTTONGROUP_EDITUSERMEMBERSHIP],
            [self::class, self::MODULE_UREAAL_QUICKLINKBUTTONGROUP_VIEWALLMEMBERS],
        );
    }

    public function getSubComponentVariations(array $module): array
    {
        $ret = parent::getSubComponentVariations($module);
    
        switch ($module[1]) {
            case self::MODULE_UREAAL_QUICKLINKBUTTONGROUP_EDITUSERMEMBERSHIP:
                $ret[] = [Custom_URE_AAL_PoPProcessors_Module_Processor_ButtonWrappers::class, Custom_URE_AAL_PoPProcessors_Module_Processor_ButtonWrappers::MODULE_UREAAL_BUTTONWRAPPER_EDITMEMBERSHIP];
                break;

            case self::MODULE_UREAAL_QUICKLINKBUTTONGROUP_VIEWALLMEMBERS:
                $ret[] = [Custom_URE_AAL_PoPProcessors_Module_Processor_ButtonWrappers::class, Custom_URE_AAL_PoPProcessors_Module_Processor_ButtonWrappers::MODULE_UREAAL_BUTTONWRAPPER_VIEWALLMEMBERS];
                break;
        }
        
        return $ret;
    }
}


