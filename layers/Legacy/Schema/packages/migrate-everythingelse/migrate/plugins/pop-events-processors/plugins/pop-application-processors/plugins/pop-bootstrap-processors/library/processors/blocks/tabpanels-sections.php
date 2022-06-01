<?php

class GD_EM_Module_Processor_SectionTabPanelBlocks extends PoP_Module_Processor_TabPanelSectionBlocksBase
{
    public final const COMPONENT_BLOCK_TABPANEL_EVENTS = 'block-events-tabpanel';
    public final const COMPONENT_BLOCK_TABPANEL_PASTEVENTS = 'block-pastevents-tabpanel';
    public final const COMPONENT_BLOCK_TABPANEL_EVENTSCALENDAR = 'block-eventscalendar-tabpanel';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_BLOCK_TABPANEL_EVENTS,
            self::COMPONENT_BLOCK_TABPANEL_PASTEVENTS,
            self::COMPONENT_BLOCK_TABPANEL_EVENTSCALENDAR,
        );
    }

    protected function getInnerSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getInnerSubcomponents($component);

        $inners = array(
            self::COMPONENT_BLOCK_TABPANEL_EVENTS => [GD_EM_Module_Processor_SectionTabPanelComponents::class, GD_EM_Module_Processor_SectionTabPanelComponents::COMPONENT_TABPANEL_EVENTS],
            self::COMPONENT_BLOCK_TABPANEL_PASTEVENTS => [GD_EM_Module_Processor_SectionTabPanelComponents::class, GD_EM_Module_Processor_SectionTabPanelComponents::COMPONENT_TABPANEL_PASTEVENTS],
            self::COMPONENT_BLOCK_TABPANEL_EVENTSCALENDAR => [GD_EM_Module_Processor_SectionTabPanelComponents::class, GD_EM_Module_Processor_SectionTabPanelComponents::COMPONENT_TABPANEL_EVENTSCALENDAR],
        );
        if ($inner = $inners[$component->name] ?? null) {
            $ret[] = $inner;
        }

        return $ret;
    }

    protected function getControlgroupTopSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_BLOCK_TABPANEL_EVENTS:
                return [PoP_Events_Module_Processor_CustomControlGroups::class, PoP_Events_Module_Processor_CustomControlGroups::COMPONENT_CONTROLGROUP_EVENTLIST];

            case self::COMPONENT_BLOCK_TABPANEL_PASTEVENTS:
            case self::COMPONENT_BLOCK_TABPANEL_EVENTSCALENDAR:
                return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::COMPONENT_CONTROLGROUP_POSTLIST];
        }

        return parent::getControlgroupTopSubcomponent($component);
    }

    public function getDelegatorfilterSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_BLOCK_TABPANEL_EVENTS:
            case self::COMPONENT_BLOCK_TABPANEL_PASTEVENTS:
                return [PoP_Events_Module_Processor_CustomFilters::class, PoP_Events_Module_Processor_CustomFilters::COMPONENT_FILTER_EVENTS];

            case self::COMPONENT_BLOCK_TABPANEL_EVENTSCALENDAR:
                return [PoP_Events_Module_Processor_CustomFilters::class, PoP_Events_Module_Processor_CustomFilters::COMPONENT_FILTER_EVENTSCALENDAR];
        }

        return parent::getDelegatorfilterSubcomponent($component);
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        if ($filter_component = $this->getDelegatorfilterSubcomponent($component)) {
            // Events: choose to only select past/future
            switch ($component->name) {
                case self::COMPONENT_BLOCK_TABPANEL_PASTEVENTS:
                    $daterange_class = 'daterange-past opens-right';
                    break;

                case self::COMPONENT_BLOCK_TABPANEL_EVENTS:
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


