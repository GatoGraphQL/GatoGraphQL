<?php

class GD_EM_Module_Processor_SectionTabPanelBlocks extends PoP_Module_Processor_TabPanelSectionBlocksBase
{
    public final const MODULE_BLOCK_TABPANEL_EVENTS = 'block-events-tabpanel';
    public final const MODULE_BLOCK_TABPANEL_PASTEVENTS = 'block-pastevents-tabpanel';
    public final const MODULE_BLOCK_TABPANEL_EVENTSCALENDAR = 'block-eventscalendar-tabpanel';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BLOCK_TABPANEL_EVENTS],
            [self::class, self::MODULE_BLOCK_TABPANEL_PASTEVENTS],
            [self::class, self::MODULE_BLOCK_TABPANEL_EVENTSCALENDAR],
        );
    }

    protected function getInnerSubmodules(array $module): array
    {
        $ret = parent::getInnerSubmodules($module);

        $inners = array(
            self::MODULE_BLOCK_TABPANEL_EVENTS => [GD_EM_Module_Processor_SectionTabPanelComponents::class, GD_EM_Module_Processor_SectionTabPanelComponents::MODULE_TABPANEL_EVENTS],
            self::MODULE_BLOCK_TABPANEL_PASTEVENTS => [GD_EM_Module_Processor_SectionTabPanelComponents::class, GD_EM_Module_Processor_SectionTabPanelComponents::MODULE_TABPANEL_PASTEVENTS],
            self::MODULE_BLOCK_TABPANEL_EVENTSCALENDAR => [GD_EM_Module_Processor_SectionTabPanelComponents::class, GD_EM_Module_Processor_SectionTabPanelComponents::MODULE_TABPANEL_EVENTSCALENDAR],
        );
        if ($inner = $inners[$module[1]] ?? null) {
            $ret[] = $inner;
        }

        return $ret;
    }

    protected function getControlgroupTopSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_BLOCK_TABPANEL_EVENTS:
                return [PoP_Events_Module_Processor_CustomControlGroups::class, PoP_Events_Module_Processor_CustomControlGroups::MODULE_CONTROLGROUP_EVENTLIST];

            case self::MODULE_BLOCK_TABPANEL_PASTEVENTS:
            case self::MODULE_BLOCK_TABPANEL_EVENTSCALENDAR:
                return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::MODULE_CONTROLGROUP_POSTLIST];
        }

        return parent::getControlgroupTopSubmodule($module);
    }

    public function getDelegatorfilterSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_BLOCK_TABPANEL_EVENTS:
            case self::MODULE_BLOCK_TABPANEL_PASTEVENTS:
                return [PoP_Events_Module_Processor_CustomFilters::class, PoP_Events_Module_Processor_CustomFilters::MODULE_FILTER_EVENTS];

            case self::MODULE_BLOCK_TABPANEL_EVENTSCALENDAR:
                return [PoP_Events_Module_Processor_CustomFilters::class, PoP_Events_Module_Processor_CustomFilters::MODULE_FILTER_EVENTSCALENDAR];
        }

        return parent::getDelegatorfilterSubmodule($module);
    }

    public function initModelProps(array $module, array &$props): void
    {
        if ($filter_module = $this->getDelegatorfilterSubmodule($module)) {
            // Events: choose to only select past/future
            switch ($module[1]) {
                case self::MODULE_BLOCK_TABPANEL_PASTEVENTS:
                    $daterange_class = 'daterange-past opens-right';
                    break;

                case self::MODULE_BLOCK_TABPANEL_EVENTS:
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


