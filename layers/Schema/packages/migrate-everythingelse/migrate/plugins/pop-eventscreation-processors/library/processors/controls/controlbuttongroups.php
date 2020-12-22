<?php

class PoP_EventsCreation_Module_Processor_CustomControlButtonGroups extends PoP_Module_Processor_ControlButtonGroupsBase
{
    public const MODULE_CONTROLBUTTONGROUP_ADDEVENT = 'customcontrolbuttongroup-addevent';
    public const MODULE_CONTROLBUTTONGROUP_EVENTLINKS = 'customcontrolbuttongroup-eventlinks';
    public const MODULE_CONTROLBUTTONGROUP_AUTHOREVENTLINKS = 'customcontrolbuttongroup-authoreventlinks';
    public const MODULE_CONTROLBUTTONGROUP_TAGEVENTLINKS = 'customcontrolbuttongroup-tageventlinks';
    public const MODULE_CONTROLBUTTONGROUP_MYEVENTLINKS = 'customcontrolbuttongroup-myeventlinks';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_CONTROLBUTTONGROUP_ADDEVENT],
            [self::class, self::MODULE_CONTROLBUTTONGROUP_EVENTLINKS],
            [self::class, self::MODULE_CONTROLBUTTONGROUP_AUTHOREVENTLINKS],
            [self::class, self::MODULE_CONTROLBUTTONGROUP_TAGEVENTLINKS],
            [self::class, self::MODULE_CONTROLBUTTONGROUP_MYEVENTLINKS],
        );
    }

    public function getSubmodules(array $module): array
    {
        $ret = parent::getSubmodules($module);
    
        switch ($module[1]) {
            case self::MODULE_CONTROLBUTTONGROUP_ADDEVENT:
                $ret[] = [PoP_EventsCreation_Module_Processor_CustomAnchorControls::class, PoP_EventsCreation_Module_Processor_CustomAnchorControls::MODULE_CUSTOMANCHORCONTROL_ADDEVENT];
                if (defined('POP_EVENTLINKSCREATIONPROCESSORS_INITIALIZED')) {
                    $ret[] = [PoP_EventsCreation_Module_Processor_CustomAnchorControls::class, PoP_EventsCreation_Module_Processor_CustomAnchorControls::MODULE_CUSTOMANCHORCONTROL_ADDEVENTLINK];
                }
                break;
        
            case self::MODULE_CONTROLBUTTONGROUP_EVENTLINKS:
                $ret[] = [PoP_Events_Module_Processor_CustomAnchorControls::class, PoP_Events_Module_Processor_CustomAnchorControls::MODULE_CUSTOMANCHORCONTROL_PASTEVENTS];
                break;
        
            case self::MODULE_CONTROLBUTTONGROUP_AUTHOREVENTLINKS:
                $ret[] = [PoP_Events_Module_Processor_CustomAnchorControls::class, PoP_Events_Module_Processor_CustomAnchorControls::MODULE_CUSTOMANCHORCONTROL_AUTHORPASTEVENTS];
                break;
        
            case self::MODULE_CONTROLBUTTONGROUP_TAGEVENTLINKS:
                $ret[] = [PoP_Events_Module_Processor_CustomAnchorControls::class, PoP_Events_Module_Processor_CustomAnchorControls::MODULE_CUSTOMANCHORCONTROL_TAGPASTEVENTS];
                break;
        
            case self::MODULE_CONTROLBUTTONGROUP_MYEVENTLINKS:
                $ret[] = [PoP_EventsCreation_Module_Processor_CustomAnchorControls::class, PoP_EventsCreation_Module_Processor_CustomAnchorControls::MODULE_CUSTOMANCHORCONTROL_MYPASTEVENTS];
                break;
        }
        
        return $ret;
    }
}


