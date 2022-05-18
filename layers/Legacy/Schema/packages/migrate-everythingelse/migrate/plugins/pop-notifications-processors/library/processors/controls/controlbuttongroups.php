<?php

class AAL_PoPProcessors_Module_Processor_ControlButtonGroups extends PoP_Module_Processor_ControlButtonGroupsBase
{
    public final const MODULE_AAL_CONTROLBUTTONGROUP_NOTIFICATIONLIST = 'controlbuttongroup-notificationlist';
    public final const MODULE_AAL_CONTROLBUTTONGROUP_NOTIFICATIONS_MARKALLASREAD = 'controlbuttongroup-notifications-markallasread';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_AAL_CONTROLBUTTONGROUP_NOTIFICATIONLIST],
            [self::class, self::MODULE_AAL_CONTROLBUTTONGROUP_NOTIFICATIONS_MARKALLASREAD],
        );
    }

    public function getSubComponentVariations(array $module): array
    {
        $ret = parent::getSubComponentVariations($module);
    
        switch ($module[1]) {
            case self::MODULE_AAL_CONTROLBUTTONGROUP_NOTIFICATIONLIST:
                $ret[] = [AAL_PoPProcessors_Module_Processor_AnchorControls::class, AAL_PoPProcessors_Module_Processor_AnchorControls::MODULE_AAL_ANCHORCONTROL_NOTIFICATIONS];
                break;
        
            case self::MODULE_AAL_CONTROLBUTTONGROUP_NOTIFICATIONS_MARKALLASREAD:
                $ret[] = [AAL_PoPProcessors_Module_Processor_AnchorControls::class, AAL_PoPProcessors_Module_Processor_AnchorControls::MODULE_AAL_ANCHORCONTROL_NOTIFICATIONS_MARKALLASREAD];
                break;
        }
        
        return $ret;
    }
}


