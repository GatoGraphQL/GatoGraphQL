<?php

class PoP_EventsCreation_Module_Processor_MySectionBlocks extends PoP_Module_Processor_MySectionBlocksBase
{
    public final const COMPONENT_BLOCK_MYEVENTS_TABLE_EDIT = 'block-myevents-table-edit';
    public final const COMPONENT_BLOCK_MYPASTEVENTS_TABLE_EDIT = 'block-mypastevents-table-edit';
    public final const COMPONENT_BLOCK_MYEVENTS_SCROLL_SIMPLEVIEWPREVIEW = 'block-myevents-scroll-simpleviewpreview';
    public final const COMPONENT_BLOCK_MYPASTEVENTS_SCROLL_SIMPLEVIEWPREVIEW = 'block-mypastevents-scroll-simpleviewpreview';
    public final const COMPONENT_BLOCK_MYEVENTS_SCROLL_FULLVIEWPREVIEW = 'block-myevents-scroll-fullviewpreview';
    public final const COMPONENT_BLOCK_MYPASTEVENTS_SCROLL_FULLVIEWPREVIEW = 'block-mypastevents-scroll-fullviewpreview';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_BLOCK_MYEVENTS_TABLE_EDIT],
            [self::class, self::COMPONENT_BLOCK_MYPASTEVENTS_TABLE_EDIT],
            [self::class, self::COMPONENT_BLOCK_MYEVENTS_SCROLL_SIMPLEVIEWPREVIEW],
            [self::class, self::COMPONENT_BLOCK_MYPASTEVENTS_SCROLL_SIMPLEVIEWPREVIEW],
            [self::class, self::COMPONENT_BLOCK_MYEVENTS_SCROLL_FULLVIEWPREVIEW],
            [self::class, self::COMPONENT_BLOCK_MYPASTEVENTS_SCROLL_FULLVIEWPREVIEW],
        );
    }

    public function getRelevantRoute(array $component, array &$props): ?string
    {
        return match($component[1]) {
            self::COMPONENT_BLOCK_MYEVENTS_SCROLL_FULLVIEWPREVIEW => POP_EVENTSCREATION_ROUTE_MYEVENTS,
            self::COMPONENT_BLOCK_MYEVENTS_SCROLL_SIMPLEVIEWPREVIEW => POP_EVENTSCREATION_ROUTE_MYEVENTS,
            self::COMPONENT_BLOCK_MYEVENTS_TABLE_EDIT => POP_EVENTSCREATION_ROUTE_MYEVENTS,
            self::COMPONENT_BLOCK_MYPASTEVENTS_SCROLL_FULLVIEWPREVIEW => POP_EVENTSCREATION_ROUTE_MYPASTEVENTS,
            self::COMPONENT_BLOCK_MYPASTEVENTS_SCROLL_SIMPLEVIEWPREVIEW => POP_EVENTSCREATION_ROUTE_MYPASTEVENTS,
            self::COMPONENT_BLOCK_MYPASTEVENTS_TABLE_EDIT => POP_EVENTSCREATION_ROUTE_MYPASTEVENTS,
            default => parent::getRelevantRoute($component, $props),
        };
    }

    protected function getInnerSubcomponent(array $component)
    {
        $inner_components = array(
            self::COMPONENT_BLOCK_MYEVENTS_TABLE_EDIT => [PoP_EventsCreation_Module_Processor_MySectionDataloads::class, PoP_EventsCreation_Module_Processor_MySectionDataloads::COMPONENT_DATALOAD_MYEVENTS_TABLE_EDIT],
            self::COMPONENT_BLOCK_MYPASTEVENTS_TABLE_EDIT => [PoP_EventsCreation_Module_Processor_MySectionDataloads::class, PoP_EventsCreation_Module_Processor_MySectionDataloads::COMPONENT_DATALOAD_MYPASTEVENTS_TABLE_EDIT],
            self::COMPONENT_BLOCK_MYEVENTS_SCROLL_SIMPLEVIEWPREVIEW => [PoP_EventsCreation_Module_Processor_MySectionDataloads::class, PoP_EventsCreation_Module_Processor_MySectionDataloads::COMPONENT_DATALOAD_MYEVENTS_SCROLL_SIMPLEVIEWPREVIEW],
            self::COMPONENT_BLOCK_MYPASTEVENTS_SCROLL_SIMPLEVIEWPREVIEW => [PoP_EventsCreation_Module_Processor_MySectionDataloads::class, PoP_EventsCreation_Module_Processor_MySectionDataloads::COMPONENT_DATALOAD_MYPASTEVENTS_SCROLL_SIMPLEVIEWPREVIEW],
            self::COMPONENT_BLOCK_MYEVENTS_SCROLL_FULLVIEWPREVIEW => [PoP_EventsCreation_Module_Processor_MySectionDataloads::class, PoP_EventsCreation_Module_Processor_MySectionDataloads::COMPONENT_DATALOAD_MYEVENTS_SCROLL_FULLVIEWPREVIEW],
            self::COMPONENT_BLOCK_MYPASTEVENTS_SCROLL_FULLVIEWPREVIEW => [PoP_EventsCreation_Module_Processor_MySectionDataloads::class, PoP_EventsCreation_Module_Processor_MySectionDataloads::COMPONENT_DATALOAD_MYPASTEVENTS_SCROLL_FULLVIEWPREVIEW],
        );

        return $inner_components[$component[1]] ?? null;
    }

    protected function getControlgroupTopSubcomponent(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_BLOCK_MYEVENTS_TABLE_EDIT:
            case self::COMPONENT_BLOCK_MYEVENTS_SCROLL_SIMPLEVIEWPREVIEW:
            case self::COMPONENT_BLOCK_MYEVENTS_SCROLL_FULLVIEWPREVIEW:
                return [PoP_EventsCreation_Module_Processor_CustomControlGroups::class, PoP_EventsCreation_Module_Processor_CustomControlGroups::COMPONENT_CONTROLGROUP_MYBLOCKEVENTLIST];

            case self::COMPONENT_BLOCK_MYPASTEVENTS_TABLE_EDIT:
            case self::COMPONENT_BLOCK_MYPASTEVENTS_SCROLL_SIMPLEVIEWPREVIEW:
            case self::COMPONENT_BLOCK_MYPASTEVENTS_SCROLL_FULLVIEWPREVIEW:
                return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::COMPONENT_CONTROLGROUP_MYBLOCKCUSTOMPOSTLIST];
        }

        return parent::getControlgroupTopSubcomponent($component);
    }
}



