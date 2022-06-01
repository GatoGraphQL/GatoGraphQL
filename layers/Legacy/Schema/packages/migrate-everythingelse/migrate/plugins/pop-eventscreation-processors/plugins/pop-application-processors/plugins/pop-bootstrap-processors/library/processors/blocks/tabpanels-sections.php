<?php

class PoP_EventsCreation_Module_Processor_SectionTabPanelBlocks extends PoP_Module_Processor_TabPanelSectionBlocksBase
{
    public final const COMPONENT_BLOCK_TABPANEL_MYEVENTS = 'block-myevents-tabpanel';
    public final const COMPONENT_BLOCK_TABPANEL_MYPASTEVENTS = 'block-mypastevents-tabpanel';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_BLOCK_TABPANEL_MYEVENTS,
            self::COMPONENT_BLOCK_TABPANEL_MYPASTEVENTS,
        );
    }

    protected function getInnerSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getInnerSubcomponents($component);

        $inners = array(
            self::COMPONENT_BLOCK_TABPANEL_MYEVENTS => [PoP_EventsCreation_Module_Processor_SectionTabPanelComponents::class, PoP_EventsCreation_Module_Processor_SectionTabPanelComponents::COMPONENT_TABPANEL_MYEVENTS],
            self::COMPONENT_BLOCK_TABPANEL_MYPASTEVENTS => [PoP_EventsCreation_Module_Processor_SectionTabPanelComponents::class, PoP_EventsCreation_Module_Processor_SectionTabPanelComponents::COMPONENT_TABPANEL_MYPASTEVENTS],
        );
        if ($inner = $inners[$component->name] ?? null) {
            $ret[] = $inner;
        }

        return $ret;
    }

    protected function getControlgroupTopSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_BLOCK_TABPANEL_MYEVENTS:
                return [PoP_EventsCreation_Module_Processor_CustomControlGroups::class, PoP_EventsCreation_Module_Processor_CustomControlGroups::COMPONENT_CONTROLGROUP_MYEVENTLIST];

            case self::COMPONENT_BLOCK_TABPANEL_MYPASTEVENTS:
                return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::COMPONENT_CONTROLGROUP_MYCUSTOMPOSTLIST];
        }

        return parent::getControlgroupTopSubcomponent($component);
    }

    public function getDelegatorfilterSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_BLOCK_TABPANEL_MYEVENTS:
            case self::COMPONENT_BLOCK_TABPANEL_MYPASTEVENTS:
                return [PoP_EventsCreation_Module_Processor_CustomFilters::class, PoP_EventsCreation_Module_Processor_CustomFilters::COMPONENT_FILTER_MYEVENTS];
        }

        return parent::getDelegatorfilterSubcomponent($component);
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        if ($filter_component = $this->getDelegatorfilterSubcomponent($component)) {
            // Events: choose to only select past/future
            switch ($component->name) {
                case self::COMPONENT_BLOCK_TABPANEL_MYPASTEVENTS:
                    $daterange_class = 'daterange-past opens-right';
                    break;

                case self::COMPONENT_BLOCK_TABPANEL_MYEVENTS:
                    $daterange_class = 'daterange-future opens-right';
                    break;
            }
            if ($daterange_class) {
                $this->setProp([PoP_Events_Module_Processor_DateRangeComponentFilterInputs::class, PoP_Events_Module_Processor_DateRangeComponentFilterInputs::COMPONENT_FILTERINPUT_EVENTSCOPE], $props, 'daterange-class', $daterange_class, [$filter_component]);
            }
        }

        parent::initModelProps($component, $props);
    }
}


