<?php

class PoP_EventsCreation_Module_Processor_CustomControlButtonGroups extends PoP_Module_Processor_ControlButtonGroupsBase
{
    public final const COMPONENT_CONTROLBUTTONGROUP_ADDEVENT = 'customcontrolbuttongroup-addevent';
    public final const COMPONENT_CONTROLBUTTONGROUP_EVENTLINKS = 'customcontrolbuttongroup-eventlinks';
    public final const COMPONENT_CONTROLBUTTONGROUP_AUTHOREVENTLINKS = 'customcontrolbuttongroup-authoreventlinks';
    public final const COMPONENT_CONTROLBUTTONGROUP_TAGEVENTLINKS = 'customcontrolbuttongroup-tageventlinks';
    public final const COMPONENT_CONTROLBUTTONGROUP_MYEVENTLINKS = 'customcontrolbuttongroup-myeventlinks';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_CONTROLBUTTONGROUP_ADDEVENT,
            self::COMPONENT_CONTROLBUTTONGROUP_EVENTLINKS,
            self::COMPONENT_CONTROLBUTTONGROUP_AUTHOREVENTLINKS,
            self::COMPONENT_CONTROLBUTTONGROUP_TAGEVENTLINKS,
            self::COMPONENT_CONTROLBUTTONGROUP_MYEVENTLINKS,
        );
    }

    /**
     * @return \PoP\ComponentModel\Component\Component[]
     */
    public function getSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getSubcomponents($component);
    
        switch ($component->name) {
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


