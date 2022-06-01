<?php

class GD_EM_Module_Processor_TagSectionTabPanelBlocks extends PoP_Module_Processor_TagTabPanelSectionBlocksBase
{
    public final const COMPONENT_BLOCK_TABPANEL_TAGEVENTS = 'block-tabpanel-tagevents';
    public final const COMPONENT_BLOCK_TABPANEL_TAGPASTEVENTS = 'block-tabpanel-tagpastevents';
    public final const COMPONENT_BLOCK_TABPANEL_TAGEVENTSCALENDAR = 'block-tabpanel-tageventscalendar';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_BLOCK_TABPANEL_TAGEVENTS],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_TAGPASTEVENTS],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_TAGEVENTSCALENDAR],
        );
    }

    protected function getInnerSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getInnerSubcomponents($component);

        $inners = array(
            self::COMPONENT_BLOCK_TABPANEL_TAGEVENTS => [GD_EM_Module_Processor_TagSectionTabPanelComponents::class, GD_EM_Module_Processor_TagSectionTabPanelComponents::COMPONENT_TABPANEL_TAGEVENTS],
            self::COMPONENT_BLOCK_TABPANEL_TAGPASTEVENTS => [GD_EM_Module_Processor_TagSectionTabPanelComponents::class, GD_EM_Module_Processor_TagSectionTabPanelComponents::COMPONENT_TABPANEL_TAGPASTEVENTS],
            self::COMPONENT_BLOCK_TABPANEL_TAGEVENTSCALENDAR => [GD_EM_Module_Processor_TagSectionTabPanelComponents::class, GD_EM_Module_Processor_TagSectionTabPanelComponents::COMPONENT_TABPANEL_TAGEVENTSCALENDAR],
        );
        if ($inner = $inners[$component[1]] ?? null) {
            $ret[] = $inner;
        }

        return $ret;
    }

    public function getDelegatorfilterSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_BLOCK_TABPANEL_TAGEVENTS:
            case self::COMPONENT_BLOCK_TABPANEL_TAGPASTEVENTS:
                return [PoP_Events_Module_Processor_CustomFilters::class, PoP_Events_Module_Processor_CustomFilters::COMPONENT_FILTER_TAGEVENTS];

            case self::COMPONENT_BLOCK_TABPANEL_TAGEVENTSCALENDAR:
                return [PoP_Events_Module_Processor_CustomFilters::class, PoP_Events_Module_Processor_CustomFilters::COMPONENT_FILTER_TAGEVENTSCALENDAR];
        }

        return parent::getDelegatorfilterSubcomponent($component);
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        if ($filter_component = $this->getDelegatorfilterSubcomponent($component)) {
            // Events: choose to only select past/future
            switch ($component[1]) {
                case self::COMPONENT_BLOCK_TABPANEL_TAGPASTEVENTS:
                    $daterange_class = 'daterange-past opens-right';
                    break;

                case self::COMPONENT_BLOCK_TABPANEL_TAGEVENTS:
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


