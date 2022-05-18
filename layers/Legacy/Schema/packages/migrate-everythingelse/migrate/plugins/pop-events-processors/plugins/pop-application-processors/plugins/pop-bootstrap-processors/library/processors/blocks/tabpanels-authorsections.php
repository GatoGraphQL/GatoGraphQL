<?php

use PoP\ComponentModel\State\ApplicationState;

class GD_EM_Module_Processor_AuthorSectionTabPanelBlocks extends PoP_Module_Processor_AuthorTabPanelSectionBlocksBase
{
    public final const MODULE_BLOCK_TABPANEL_AUTHOREVENTS = 'block-tabpanel-authorevents';
    public final const MODULE_BLOCK_TABPANEL_AUTHORPASTEVENTS = 'block-tabpanel-authorpastevents';
    public final const MODULE_BLOCK_TABPANEL_AUTHOREVENTSCALENDAR = 'block-tabpanel-authoreventscalendar';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BLOCK_TABPANEL_AUTHOREVENTS],
            [self::class, self::MODULE_BLOCK_TABPANEL_AUTHORPASTEVENTS],
            [self::class, self::MODULE_BLOCK_TABPANEL_AUTHOREVENTSCALENDAR],
        );
    }

    protected function getInnerSubmodules(array $component): array
    {
        $ret = parent::getInnerSubmodules($component);

        if (defined('POP_USERCOMMUNITIESPROCESSORS_INITIALIZED')) {
            $author = \PoP\Root\App::getState(['routing', 'queried-object-id']);
            if (gdUreIsCommunity($author)) {
                switch ($component[1]) {
                    case self::MODULE_BLOCK_TABPANEL_AUTHOREVENTS:
                    case self::MODULE_BLOCK_TABPANEL_AUTHORPASTEVENTS:
                    case self::MODULE_BLOCK_TABPANEL_AUTHOREVENTSCALENDAR:
                        $ret[] = [GD_URE_Module_Processor_ControlGroups::class, GD_URE_Module_Processor_ControlGroups::MODULE_URE_CONTROLGROUP_CONTENTSOURCE];
                        break;
                }
            }
        }

        $inners = array(
            self::MODULE_BLOCK_TABPANEL_AUTHOREVENTS => [GD_EM_Module_Processor_AuthorSectionTabPanelComponents::class, GD_EM_Module_Processor_AuthorSectionTabPanelComponents::MODULE_TABPANEL_AUTHOREVENTS],
            self::MODULE_BLOCK_TABPANEL_AUTHORPASTEVENTS => [GD_EM_Module_Processor_AuthorSectionTabPanelComponents::class, GD_EM_Module_Processor_AuthorSectionTabPanelComponents::MODULE_TABPANEL_AUTHORPASTEVENTS],
            self::MODULE_BLOCK_TABPANEL_AUTHOREVENTSCALENDAR => [GD_EM_Module_Processor_AuthorSectionTabPanelComponents::class, GD_EM_Module_Processor_AuthorSectionTabPanelComponents::MODULE_TABPANEL_AUTHOREVENTSCALENDAR],
        );
        if ($inner = $inners[$component[1]] ?? null) {
            $ret[] = $inner;
        }

        return $ret;
    }

    public function getDelegatorfilterSubmodule(array $component)
    {
        switch ($component[1]) {
            case self::MODULE_BLOCK_TABPANEL_AUTHOREVENTS:
            case self::MODULE_BLOCK_TABPANEL_AUTHORPASTEVENTS:
                return [PoP_Events_Module_Processor_CustomFilters::class, PoP_Events_Module_Processor_CustomFilters::MODULE_FILTER_AUTHOREVENTS];

            case self::MODULE_BLOCK_TABPANEL_AUTHOREVENTSCALENDAR:
                return [PoP_Events_Module_Processor_CustomFilters::class, PoP_Events_Module_Processor_CustomFilters::MODULE_FILTER_AUTHOREVENTSCALENDAR];
        }

        return parent::getDelegatorfilterSubmodule($component);
    }

    public function initModelProps(array $component, array &$props): void
    {
        if ($filter_component = $this->getDelegatorfilterSubmodule($component)) {
            // Events: choose to only select past/future
            switch ($component[1]) {
                case self::MODULE_BLOCK_TABPANEL_AUTHORPASTEVENTS:
                    $daterange_class = 'daterange-past opens-right';
                    break;

                case self::MODULE_BLOCK_TABPANEL_AUTHOREVENTS:
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


