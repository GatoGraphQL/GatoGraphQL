<?php

class GD_EM_Module_Processor_TagSectionTabPanelBlocks extends PoP_Module_Processor_TagTabPanelSectionBlocksBase
{
    public final const MODULE_BLOCK_TABPANEL_TAGEVENTS = 'block-tabpanel-tagevents';
    public final const MODULE_BLOCK_TABPANEL_TAGPASTEVENTS = 'block-tabpanel-tagpastevents';
    public final const MODULE_BLOCK_TABPANEL_TAGEVENTSCALENDAR = 'block-tabpanel-tageventscalendar';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BLOCK_TABPANEL_TAGEVENTS],
            [self::class, self::MODULE_BLOCK_TABPANEL_TAGPASTEVENTS],
            [self::class, self::MODULE_BLOCK_TABPANEL_TAGEVENTSCALENDAR],
        );
    }

    protected function getInnerSubmodules(array $component): array
    {
        $ret = parent::getInnerSubmodules($component);

        $inners = array(
            self::MODULE_BLOCK_TABPANEL_TAGEVENTS => [GD_EM_Module_Processor_TagSectionTabPanelComponents::class, GD_EM_Module_Processor_TagSectionTabPanelComponents::MODULE_TABPANEL_TAGEVENTS],
            self::MODULE_BLOCK_TABPANEL_TAGPASTEVENTS => [GD_EM_Module_Processor_TagSectionTabPanelComponents::class, GD_EM_Module_Processor_TagSectionTabPanelComponents::MODULE_TABPANEL_TAGPASTEVENTS],
            self::MODULE_BLOCK_TABPANEL_TAGEVENTSCALENDAR => [GD_EM_Module_Processor_TagSectionTabPanelComponents::class, GD_EM_Module_Processor_TagSectionTabPanelComponents::MODULE_TABPANEL_TAGEVENTSCALENDAR],
        );
        if ($inner = $inners[$component[1]] ?? null) {
            $ret[] = $inner;
        }

        return $ret;
    }

    public function getDelegatorfilterSubmodule(array $component)
    {
        switch ($component[1]) {
            case self::MODULE_BLOCK_TABPANEL_TAGEVENTS:
            case self::MODULE_BLOCK_TABPANEL_TAGPASTEVENTS:
                return [PoP_Events_Module_Processor_CustomFilters::class, PoP_Events_Module_Processor_CustomFilters::MODULE_FILTER_TAGEVENTS];

            case self::MODULE_BLOCK_TABPANEL_TAGEVENTSCALENDAR:
                return [PoP_Events_Module_Processor_CustomFilters::class, PoP_Events_Module_Processor_CustomFilters::MODULE_FILTER_TAGEVENTSCALENDAR];
        }

        return parent::getDelegatorfilterSubmodule($component);
    }

    public function initModelProps(array $component, array &$props): void
    {
        if ($filter_component = $this->getDelegatorfilterSubmodule($component)) {
            // Events: choose to only select past/future
            switch ($component[1]) {
                case self::MODULE_BLOCK_TABPANEL_TAGPASTEVENTS:
                    $daterange_class = 'daterange-past opens-right';
                    break;

                case self::MODULE_BLOCK_TABPANEL_TAGEVENTS:
                    $daterange_class = 'daterange-future opens-right';
                    break;
            }
            if ($daterange_class) {
                $this->setProp([PoP_Events_Module_Processor_DateRangeComponentFilterInputs::class, PoP_Events_Module_Processor_DateRangeComponentFilterInputs::MODULE_FILTERINPUT_EVENTSCOPE], $props, 'daterange-class', $daterange_class, [$filter_component]);
            }
        }

        parent::initModelProps($component, $props);
    }
}


