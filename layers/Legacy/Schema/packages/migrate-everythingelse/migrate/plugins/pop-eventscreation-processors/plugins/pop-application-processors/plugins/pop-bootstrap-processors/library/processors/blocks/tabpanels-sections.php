<?php

class PoP_EventsCreation_Module_Processor_SectionTabPanelBlocks extends PoP_Module_Processor_TabPanelSectionBlocksBase
{
    public final const MODULE_BLOCK_TABPANEL_MYEVENTS = 'block-myevents-tabpanel';
    public final const MODULE_BLOCK_TABPANEL_MYPASTEVENTS = 'block-mypastevents-tabpanel';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BLOCK_TABPANEL_MYEVENTS],
            [self::class, self::MODULE_BLOCK_TABPANEL_MYPASTEVENTS],
        );
    }

    protected function getInnerSubmodules(array $module): array
    {
        $ret = parent::getInnerSubmodules($module);

        $inners = array(
            self::MODULE_BLOCK_TABPANEL_MYEVENTS => [PoP_EventsCreation_Module_Processor_SectionTabPanelComponents::class, PoP_EventsCreation_Module_Processor_SectionTabPanelComponents::MODULE_TABPANEL_MYEVENTS],
            self::MODULE_BLOCK_TABPANEL_MYPASTEVENTS => [PoP_EventsCreation_Module_Processor_SectionTabPanelComponents::class, PoP_EventsCreation_Module_Processor_SectionTabPanelComponents::MODULE_TABPANEL_MYPASTEVENTS],
        );
        if ($inner = $inners[$module[1]] ?? null) {
            $ret[] = $inner;
        }

        return $ret;
    }

    protected function getControlgroupTopSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_BLOCK_TABPANEL_MYEVENTS:
                return [PoP_EventsCreation_Module_Processor_CustomControlGroups::class, PoP_EventsCreation_Module_Processor_CustomControlGroups::MODULE_CONTROLGROUP_MYEVENTLIST];

            case self::MODULE_BLOCK_TABPANEL_MYPASTEVENTS:
                return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::MODULE_CONTROLGROUP_MYCUSTOMPOSTLIST];
        }

        return parent::getControlgroupTopSubmodule($module);
    }

    public function getDelegatorfilterSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_BLOCK_TABPANEL_MYEVENTS:
            case self::MODULE_BLOCK_TABPANEL_MYPASTEVENTS:
                return [PoP_EventsCreation_Module_Processor_CustomFilters::class, PoP_EventsCreation_Module_Processor_CustomFilters::MODULE_FILTER_MYEVENTS];
        }

        return parent::getDelegatorfilterSubmodule($module);
    }

    public function initModelProps(array $module, array &$props): void
    {
        if ($filter_module = $this->getDelegatorfilterSubmodule($module)) {
            // Events: choose to only select past/future
            switch ($module[1]) {
                case self::MODULE_BLOCK_TABPANEL_MYPASTEVENTS:
                    $daterange_class = 'daterange-past opens-right';
                    break;

                case self::MODULE_BLOCK_TABPANEL_MYEVENTS:
                    $daterange_class = 'daterange-future opens-right';
                    break;
            }
            if ($daterange_class) {
                $this->setProp([PoP_Events_Module_Processor_DateRangeComponentFilterInputs::class, PoP_Events_Module_Processor_DateRangeComponentFilterInputs::MODULE_FILTERINPUT_EVENTSCOPE], $props, 'daterange-class', $daterange_class, [$filter_module]);
            }
        }

        parent::initModelProps($module, $props);
    }
}


