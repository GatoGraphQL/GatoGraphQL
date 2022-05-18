<?php

class PoP_EventsCreation_Module_Processor_CustomControlButtonGroups extends PoP_Module_Processor_ControlButtonGroupsBase
{
    public final const COMPONENT_CONTROLBUTTONGROUP_ADDEVENT = 'customcontrolbuttongroup-addevent';
    public final const COMPONENT_CONTROLBUTTONGROUP_EVENTLINKS = 'customcontrolbuttongroup-eventlinks';
    public final const COMPONENT_CONTROLBUTTONGROUP_AUTHOREVENTLINKS = 'customcontrolbuttongroup-authoreventlinks';
    public final const COMPONENT_CONTROLBUTTONGROUP_TAGEVENTLINKS = 'customcontrolbuttongroup-tageventlinks';
    public final const COMPONENT_CONTROLBUTTONGROUP_MYEVENTLINKS = 'customcontrolbuttongroup-myeventlinks';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_CONTROLBUTTONGROUP_ADDEVENT],
            [self::class, self::COMPONENT_CONTROLBUTTONGROUP_EVENTLINKS],
            [self::class, self::COMPONENT_CONTROLBUTTONGROUP_AUTHOREVENTLINKS],
            [self::class, self::COMPONENT_CONTROLBUTTONGROUP_TAGEVENTLINKS],
            [self::class, self::COMPONENT_CONTROLBUTTONGROUP_MYEVENTLINKS],
        );
    }

    public function getSubComponents(array $component): array
    {
        $ret = parent::getSubComponents($component);
    
        switch ($component[1]) {
            case self::COMPONENT_CONTROLBUTTONGROUP_ADDEVENT:
                $ret[] = [PoP_EventsCreation_Module_Processor_CustomAnchorControls::class, PoP_EventsCreation_Module_Processor_CustomAnchorControls::COMPONENT_CUSTOMANCHORCONTROL_ADDEVENT];
                if (defined('POP_EVENTLINKSCREATIONPROCESSORS_INITIALIZED')) {
                    $ret[] = [PoP_EventsCreation_Module_Processor_CustomAnchorControls::class, PoP_EventsCreation_Module_Processor_CustomAnchorControls::COMPONENT_CUSTOMANCHORCONTROL_ADDEVENTLINK];
                }
                break;
        
            case self::COMPONENT_CONTROLBUTTONGROUP_EVENTLINKS:
                $ret[] = [PoP_Events_Module_Processor_CustomAnchorControls::class, PoP_Events_Module_Processor_CustomAnchorControls::COMPONENT_CUSTOMANCHORCONTROL_PASTEVENTS];
                break;
        
            case self::COMPONENT_CONTROLBUTTONGROUP_AUTHOREVENTLINKS:
                $ret[] = [PoP_Events_Module_Processor_CustomAnchorControls::class, PoP_Events_Module_Processor_CustomAnchorControls::COMPONENT_CUSTOMANCHORCONTROL_AUTHORPASTEVENTS];
                break;
        
            case self::COMPONENT_CONTROLBUTTONGROUP_TAGEVENTLINKS:
                $ret[] = [PoP_Events_Module_Processor_CustomAnchorControls::class, PoP_Events_Module_Processor_CustomAnchorControls::COMPONENT_CUSTOMANCHORCONTROL_TAGPASTEVENTS];
                break;
        
            case self::COMPONENT_CONTROLBUTTONGROUP_MYEVENTLINKS:
                $ret[] = [PoP_EventsCreation_Module_Processor_CustomAnchorControls::class, PoP_EventsCreation_Module_Processor_CustomAnchorControls::COMPONENT_CUSTOMANCHORCONTROL_MYPASTEVENTS];
                break;
        }
        
        return $ret;
    }
}


