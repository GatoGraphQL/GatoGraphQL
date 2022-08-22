<?php

use PoP\ComponentModel\State\ApplicationState;

class GD_EM_Module_Processor_AuthorSectionTabPanelBlocks extends PoP_Module_Processor_AuthorTabPanelSectionBlocksBase
{
    public final const COMPONENT_BLOCK_TABPANEL_AUTHOREVENTS = 'block-tabpanel-authorevents';
    public final const COMPONENT_BLOCK_TABPANEL_AUTHORPASTEVENTS = 'block-tabpanel-authorpastevents';
    public final const COMPONENT_BLOCK_TABPANEL_AUTHOREVENTSCALENDAR = 'block-tabpanel-authoreventscalendar';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_BLOCK_TABPANEL_AUTHOREVENTS,
            self::COMPONENT_BLOCK_TABPANEL_AUTHORPASTEVENTS,
            self::COMPONENT_BLOCK_TABPANEL_AUTHOREVENTSCALENDAR,
        );
    }

    protected function getInnerSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getInnerSubcomponents($component);

        if (defined('POP_USERCOMMUNITIESPROCESSORS_INITIALIZED')) {
            $author = \PoP\Root\App::getState(['routing', 'queried-object-id']);
            if (gdUreIsCommunity($author)) {
                switch ($component->name) {
                    case self::COMPONENT_BLOCK_TABPANEL_AUTHOREVENTS:
                    case self::COMPONENT_BLOCK_TABPANEL_AUTHORPASTEVENTS:
                    case self::COMPONENT_BLOCK_TABPANEL_AUTHOREVENTSCALENDAR:
                        $ret[] = [GD_URE_Module_Processor_ControlGroups::class, GD_URE_Module_Processor_ControlGroups::COMPONENT_URE_CONTROLGROUP_CONTENTSOURCE];
                        break;
                }
            }
        }

        $inners = array(
            self::COMPONENT_BLOCK_TABPANEL_AUTHOREVENTS => [GD_EM_Module_Processor_AuthorSectionTabPanelComponents::class, GD_EM_Module_Processor_AuthorSectionTabPanelComponents::COMPONENT_TABPANEL_AUTHOREVENTS],
            self::COMPONENT_BLOCK_TABPANEL_AUTHORPASTEVENTS => [GD_EM_Module_Processor_AuthorSectionTabPanelComponents::class, GD_EM_Module_Processor_AuthorSectionTabPanelComponents::COMPONENT_TABPANEL_AUTHORPASTEVENTS],
            self::COMPONENT_BLOCK_TABPANEL_AUTHOREVENTSCALENDAR => [GD_EM_Module_Processor_AuthorSectionTabPanelComponents::class, GD_EM_Module_Processor_AuthorSectionTabPanelComponents::COMPONENT_TABPANEL_AUTHOREVENTSCALENDAR],
        );
        if ($inner = $inners[$component->name] ?? null) {
            $ret[] = $inner;
        }

        return $ret;
    }

    public function getDelegatorfilterSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_BLOCK_TABPANEL_AUTHOREVENTS:
            case self::COMPONENT_BLOCK_TABPANEL_AUTHORPASTEVENTS:
                return [PoP_Events_Module_Processor_CustomFilters::class, PoP_Events_Module_Processor_CustomFilters::COMPONENT_FILTER_AUTHOREVENTS];

            case self::COMPONENT_BLOCK_TABPANEL_AUTHOREVENTSCALENDAR:
                return [PoP_Events_Module_Processor_CustomFilters::class, PoP_Events_Module_Processor_CustomFilters::COMPONENT_FILTER_AUTHOREVENTSCALENDAR];
        }

        return parent::getDelegatorfilterSubcomponent($component);
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        if ($filter_component = $this->getDelegatorfilterSubcomponent($component)) {
            // Events: choose to only select past/future
            switch ($component->name) {
                case self::COMPONENT_BLOCK_TABPANEL_AUTHORPASTEVENTS:
                    $daterange_class = 'daterange-past opens-right';
                    break;

                case self::COMPONENT_BLOCK_TABPANEL_AUTHOREVENTS:
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


