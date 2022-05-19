<?php

class GD_AAL_Module_Processor_QuicklinkButtonGroups extends PoP_Module_Processor_ControlButtonGroupsBase
{
    public final const COMPONENT_AAL_QUICKLINKBUTTONGROUP_VIEWUSER = 'notifications-quicklinkbuttongroup-viewuser';
    public final const COMPONENT_AAL_QUICKLINKBUTTONGROUP_NOTIFICATION_MARKASREADUNREAD = 'notifications-quicklinkbuttongroup-notification-markasreadunread';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_AAL_QUICKLINKBUTTONGROUP_VIEWUSER],
            [self::class, self::COMPONENT_AAL_QUICKLINKBUTTONGROUP_NOTIFICATION_MARKASREADUNREAD],
        );
    }

    public function getSubcomponents(array $component): array
    {
        $ret = parent::getSubcomponents($component);
    
        switch ($component[1]) {
            case self::COMPONENT_AAL_QUICKLINKBUTTONGROUP_VIEWUSER:
                $ret[] = [AAL_PoPProcessors_Module_Processor_Buttons::class, AAL_PoPProcessors_Module_Processor_Buttons::COMPONENT_AAL_BUTTON_USERVIEW];
                break;

            case self::COMPONENT_AAL_QUICKLINKBUTTONGROUP_NOTIFICATION_MARKASREADUNREAD:
                $ret[] = [AAL_PoPProcessors_Module_Processor_Buttons::class, AAL_PoPProcessors_Module_Processor_Buttons::COMPONENT_AAL_BUTTON_NOTIFICATION_MARKASREAD];
                $ret[] = [AAL_PoPProcessors_Module_Processor_Buttons::class, AAL_PoPProcessors_Module_Processor_Buttons::COMPONENT_AAL_BUTTON_NOTIFICATION_MARKASUNREAD];
                break;
        }
        
        return $ret;
    }
}


