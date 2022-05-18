<?php

class GD_AAL_Module_Processor_QuicklinkButtonGroups extends PoP_Module_Processor_ControlButtonGroupsBase
{
    public final const MODULE_AAL_QUICKLINKBUTTONGROUP_VIEWUSER = 'notifications-quicklinkbuttongroup-viewuser';
    public final const MODULE_AAL_QUICKLINKBUTTONGROUP_NOTIFICATION_MARKASREADUNREAD = 'notifications-quicklinkbuttongroup-notification-markasreadunread';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_AAL_QUICKLINKBUTTONGROUP_VIEWUSER],
            [self::class, self::MODULE_AAL_QUICKLINKBUTTONGROUP_NOTIFICATION_MARKASREADUNREAD],
        );
    }

    public function getSubmodules(array $module): array
    {
        $ret = parent::getSubmodules($module);
    
        switch ($module[1]) {
            case self::MODULE_AAL_QUICKLINKBUTTONGROUP_VIEWUSER:
                $ret[] = [AAL_PoPProcessors_Module_Processor_Buttons::class, AAL_PoPProcessors_Module_Processor_Buttons::MODULE_AAL_BUTTON_USERVIEW];
                break;

            case self::MODULE_AAL_QUICKLINKBUTTONGROUP_NOTIFICATION_MARKASREADUNREAD:
                $ret[] = [AAL_PoPProcessors_Module_Processor_Buttons::class, AAL_PoPProcessors_Module_Processor_Buttons::MODULE_AAL_BUTTON_NOTIFICATION_MARKASREAD];
                $ret[] = [AAL_PoPProcessors_Module_Processor_Buttons::class, AAL_PoPProcessors_Module_Processor_Buttons::MODULE_AAL_BUTTON_NOTIFICATION_MARKASUNREAD];
                break;
        }
        
        return $ret;
    }
}


