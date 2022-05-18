<?php

class PoP_EventsCreation_Module_Processor_CustomControlGroups extends PoP_Module_Processor_ControlGroupsBase
{
    public final const COMPONENT_CONTROLGROUP_MYEVENTLIST = 'controlgroup-myeventlist';
    public final const COMPONENT_CONTROLGROUP_MYBLOCKEVENTLIST = 'controlgroup-myblockeventlist';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_CONTROLGROUP_MYEVENTLIST],
            [self::class, self::COMPONENT_CONTROLGROUP_MYBLOCKEVENTLIST],
        );
    }

    public function getSubComponents(array $component): array
    {
        $ret = parent::getSubComponents($component);

        switch ($component[1]) {
            case self::COMPONENT_CONTROLGROUP_MYEVENTLIST:
                $ret[] = [PoP_EventsCreation_Module_Processor_CustomControlButtonGroups::class, PoP_EventsCreation_Module_Processor_CustomControlButtonGroups::COMPONENT_CONTROLBUTTONGROUP_ADDEVENT];
                $ret[] = [PoP_EventsCreation_Module_Processor_CustomControlButtonGroups::class, PoP_EventsCreation_Module_Processor_CustomControlButtonGroups::COMPONENT_CONTROLBUTTONGROUP_MYEVENTLINKS];
                $ret[] = [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::COMPONENT_CONTROLBUTTONGROUP_RELOADBLOCKGROUP];
                $ret[] = [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::COMPONENT_CONTROLBUTTONGROUP_FILTER];
                break;
                
            case self::COMPONENT_CONTROLGROUP_MYBLOCKEVENTLIST:
                $ret[] = [PoP_EventsCreation_Module_Processor_CustomControlButtonGroups::class, PoP_EventsCreation_Module_Processor_CustomControlButtonGroups::COMPONENT_CONTROLBUTTONGROUP_ADDEVENT];
                $ret[] = [PoP_EventsCreation_Module_Processor_CustomControlButtonGroups::class, PoP_EventsCreation_Module_Processor_CustomControlButtonGroups::COMPONENT_CONTROLBUTTONGROUP_MYEVENTLINKS];
                $ret[] = [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::COMPONENT_CONTROLBUTTONGROUP_RELOADBLOCK];
                $ret[] = [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::COMPONENT_CONTROLBUTTONGROUP_FILTER];
                break;
        }

        return $ret;
    }
}


