<?php

class PoP_Events_Module_Processor_CustomControlGroups extends PoP_Module_Processor_ControlGroupsBase
{
    public final const COMPONENT_CONTROLGROUP_EVENTLIST = 'controlgroup-eventlist';
    public final const COMPONENT_CONTROLGROUP_BLOCKEVENTLIST = 'controlgroup-blockeventlist';
    public final const COMPONENT_CONTROLGROUP_BLOCKAUTHOREVENTLIST = 'controlgroup-blockauthoreventlist';
    public final const COMPONENT_CONTROLGROUP_BLOCKTAGEVENTLIST = 'controlgroup-blocktageventlist';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_CONTROLGROUP_EVENTLIST],
            [self::class, self::COMPONENT_CONTROLGROUP_BLOCKEVENTLIST],
            [self::class, self::COMPONENT_CONTROLGROUP_BLOCKAUTHOREVENTLIST],
            [self::class, self::COMPONENT_CONTROLGROUP_BLOCKTAGEVENTLIST],
        );
    }

    public function getSubcomponents(array $component): array
    {
        $ret = parent::getSubcomponents($component);

        switch ($component[1]) {
            case self::COMPONENT_CONTROLGROUP_EVENTLIST:
                $ret[] = [PoP_EventsCreation_Module_Processor_CustomControlButtonGroups::class, PoP_EventsCreation_Module_Processor_CustomControlButtonGroups::COMPONENT_CONTROLBUTTONGROUP_EVENTLINKS];
                $ret[] = [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::COMPONENT_CONTROLBUTTONGROUP_RELOADBLOCKGROUP];
                $ret[] = [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::COMPONENT_CONTROLBUTTONGROUP_FILTER];
                $ret[] = [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::COMPONENT_CONTROLBUTTONGROUP_RESULTSSHARE];
                break;
                
            case self::COMPONENT_CONTROLGROUP_BLOCKEVENTLIST:
                $ret[] = [PoP_EventsCreation_Module_Processor_CustomControlButtonGroups::class, PoP_EventsCreation_Module_Processor_CustomControlButtonGroups::COMPONENT_CONTROLBUTTONGROUP_EVENTLINKS];
                $ret[] = [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::COMPONENT_CONTROLBUTTONGROUP_RELOADBLOCK];
                $ret[] = [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::COMPONENT_CONTROLBUTTONGROUP_FILTER];
                $ret[] = [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::COMPONENT_CONTROLBUTTONGROUP_RESULTSSHARE];
                break;
                
            case self::COMPONENT_CONTROLGROUP_BLOCKAUTHOREVENTLIST:
                // Allow URE to add the Switch Organization/Organization+Members if the author is an organization
                $layouts = \PoP\Root\App::applyFilters(
                    'GD_EM_Module_Processor_CustomControlGroups:blockauthoreventlist:layouts',
                    array(
                        [PoP_EventsCreation_Module_Processor_CustomControlButtonGroups::class, PoP_EventsCreation_Module_Processor_CustomControlButtonGroups::COMPONENT_CONTROLBUTTONGROUP_AUTHOREVENTLINKS],
                        [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::COMPONENT_CONTROLBUTTONGROUP_RELOADBLOCK],
                        [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::COMPONENT_CONTROLBUTTONGROUP_FILTER],
                        [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::COMPONENT_CONTROLBUTTONGROUP_RESULTSSHARE]
                    )
                );
                if ($layouts) {
                    $ret = array_merge(
                        $ret,
                        $layouts
                    );
                }
                break;
                
            case self::COMPONENT_CONTROLGROUP_BLOCKTAGEVENTLIST:
                $ret[] = [PoP_EventsCreation_Module_Processor_CustomControlButtonGroups::class, PoP_EventsCreation_Module_Processor_CustomControlButtonGroups::COMPONENT_CONTROLBUTTONGROUP_TAGEVENTLINKS];
                $ret[] = [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::COMPONENT_CONTROLBUTTONGROUP_RELOADBLOCK];
                $ret[] = [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::COMPONENT_CONTROLBUTTONGROUP_FILTER];
                $ret[] = [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::COMPONENT_CONTROLBUTTONGROUP_RESULTSSHARE];
                break;
        }

        return $ret;
    }
}


