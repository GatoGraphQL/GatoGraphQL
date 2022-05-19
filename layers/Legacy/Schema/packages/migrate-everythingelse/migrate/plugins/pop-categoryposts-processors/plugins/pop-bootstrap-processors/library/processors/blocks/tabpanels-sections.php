<?php

class CPP_Module_Processor_TabPanelSectionBlocks extends PoP_Module_Processor_TabPanelSectionBlocksBase
{
    public final const COMPONENT_BLOCK_TABPANEL_CATEGORYPOSTS00 = 'block-categoryposts00-tabpanel';
    public final const COMPONENT_BLOCK_TABPANEL_CATEGORYPOSTS01 = 'block-categoryposts01-tabpanel';
    public final const COMPONENT_BLOCK_TABPANEL_CATEGORYPOSTS02 = 'block-categoryposts02-tabpanel';
    public final const COMPONENT_BLOCK_TABPANEL_CATEGORYPOSTS03 = 'block-categoryposts03-tabpanel';
    public final const COMPONENT_BLOCK_TABPANEL_CATEGORYPOSTS04 = 'block-categoryposts04-tabpanel';
    public final const COMPONENT_BLOCK_TABPANEL_CATEGORYPOSTS05 = 'block-categoryposts05-tabpanel';
    public final const COMPONENT_BLOCK_TABPANEL_CATEGORYPOSTS06 = 'block-categoryposts06-tabpanel';
    public final const COMPONENT_BLOCK_TABPANEL_CATEGORYPOSTS07 = 'block-categoryposts07-tabpanel';
    public final const COMPONENT_BLOCK_TABPANEL_CATEGORYPOSTS08 = 'block-categoryposts08-tabpanel';
    public final const COMPONENT_BLOCK_TABPANEL_CATEGORYPOSTS09 = 'block-categoryposts09-tabpanel';
    public final const COMPONENT_BLOCK_TABPANEL_CATEGORYPOSTS10 = 'block-categoryposts10-tabpanel';
    public final const COMPONENT_BLOCK_TABPANEL_CATEGORYPOSTS11 = 'block-categoryposts11-tabpanel';
    public final const COMPONENT_BLOCK_TABPANEL_CATEGORYPOSTS12 = 'block-categoryposts12-tabpanel';
    public final const COMPONENT_BLOCK_TABPANEL_CATEGORYPOSTS13 = 'block-categoryposts13-tabpanel';
    public final const COMPONENT_BLOCK_TABPANEL_CATEGORYPOSTS14 = 'block-categoryposts14-tabpanel';
    public final const COMPONENT_BLOCK_TABPANEL_CATEGORYPOSTS15 = 'block-categoryposts15-tabpanel';
    public final const COMPONENT_BLOCK_TABPANEL_CATEGORYPOSTS16 = 'block-categoryposts16-tabpanel';
    public final const COMPONENT_BLOCK_TABPANEL_CATEGORYPOSTS17 = 'block-categoryposts17-tabpanel';
    public final const COMPONENT_BLOCK_TABPANEL_CATEGORYPOSTS18 = 'block-categoryposts18-tabpanel';
    public final const COMPONENT_BLOCK_TABPANEL_CATEGORYPOSTS19 = 'block-categoryposts19-tabpanel';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_BLOCK_TABPANEL_CATEGORYPOSTS00],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_CATEGORYPOSTS01],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_CATEGORYPOSTS02],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_CATEGORYPOSTS03],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_CATEGORYPOSTS04],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_CATEGORYPOSTS05],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_CATEGORYPOSTS06],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_CATEGORYPOSTS07],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_CATEGORYPOSTS08],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_CATEGORYPOSTS09],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_CATEGORYPOSTS10],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_CATEGORYPOSTS11],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_CATEGORYPOSTS12],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_CATEGORYPOSTS13],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_CATEGORYPOSTS14],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_CATEGORYPOSTS15],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_CATEGORYPOSTS16],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_CATEGORYPOSTS17],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_CATEGORYPOSTS18],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_CATEGORYPOSTS19],
        );
    }

    public function getRelevantRoute(array $component, array &$props): ?string
    {
        return match($component[1]) {
            self::COMPONENT_BLOCK_TABPANEL_CATEGORYPOSTS00 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS00,
            self::COMPONENT_BLOCK_TABPANEL_CATEGORYPOSTS01 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS01,
            self::COMPONENT_BLOCK_TABPANEL_CATEGORYPOSTS02 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS02,
            self::COMPONENT_BLOCK_TABPANEL_CATEGORYPOSTS03 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS03,
            self::COMPONENT_BLOCK_TABPANEL_CATEGORYPOSTS04 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS04,
            self::COMPONENT_BLOCK_TABPANEL_CATEGORYPOSTS05 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS05,
            self::COMPONENT_BLOCK_TABPANEL_CATEGORYPOSTS06 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS06,
            self::COMPONENT_BLOCK_TABPANEL_CATEGORYPOSTS07 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS07,
            self::COMPONENT_BLOCK_TABPANEL_CATEGORYPOSTS08 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS08,
            self::COMPONENT_BLOCK_TABPANEL_CATEGORYPOSTS09 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS09,
            self::COMPONENT_BLOCK_TABPANEL_CATEGORYPOSTS10 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS10,
            self::COMPONENT_BLOCK_TABPANEL_CATEGORYPOSTS11 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS11,
            self::COMPONENT_BLOCK_TABPANEL_CATEGORYPOSTS12 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS12,
            self::COMPONENT_BLOCK_TABPANEL_CATEGORYPOSTS13 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS13,
            self::COMPONENT_BLOCK_TABPANEL_CATEGORYPOSTS14 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS14,
            self::COMPONENT_BLOCK_TABPANEL_CATEGORYPOSTS15 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS15,
            self::COMPONENT_BLOCK_TABPANEL_CATEGORYPOSTS16 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS16,
            self::COMPONENT_BLOCK_TABPANEL_CATEGORYPOSTS17 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS17,
            self::COMPONENT_BLOCK_TABPANEL_CATEGORYPOSTS18 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS18,
            self::COMPONENT_BLOCK_TABPANEL_CATEGORYPOSTS19 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS19,
            default => parent::getRelevantRoute($component, $props),
        };
    }

    public function getInnerSubcomponents(array $component): array
    {
        $ret = parent::getInnerSubcomponents($component);

        $inners = array(
            self::COMPONENT_BLOCK_TABPANEL_CATEGORYPOSTS00 => [CPP_Module_Processor_SectionTabPanelComponents::class, CPP_Module_Processor_SectionTabPanelComponents::COMPONENT_TABPANEL_CATEGORYPOSTS00],
            self::COMPONENT_BLOCK_TABPANEL_CATEGORYPOSTS01 => [CPP_Module_Processor_SectionTabPanelComponents::class, CPP_Module_Processor_SectionTabPanelComponents::COMPONENT_TABPANEL_CATEGORYPOSTS01],
            self::COMPONENT_BLOCK_TABPANEL_CATEGORYPOSTS02 => [CPP_Module_Processor_SectionTabPanelComponents::class, CPP_Module_Processor_SectionTabPanelComponents::COMPONENT_TABPANEL_CATEGORYPOSTS02],
            self::COMPONENT_BLOCK_TABPANEL_CATEGORYPOSTS03 => [CPP_Module_Processor_SectionTabPanelComponents::class, CPP_Module_Processor_SectionTabPanelComponents::COMPONENT_TABPANEL_CATEGORYPOSTS03],
            self::COMPONENT_BLOCK_TABPANEL_CATEGORYPOSTS04 => [CPP_Module_Processor_SectionTabPanelComponents::class, CPP_Module_Processor_SectionTabPanelComponents::COMPONENT_TABPANEL_CATEGORYPOSTS04],
            self::COMPONENT_BLOCK_TABPANEL_CATEGORYPOSTS05 => [CPP_Module_Processor_SectionTabPanelComponents::class, CPP_Module_Processor_SectionTabPanelComponents::COMPONENT_TABPANEL_CATEGORYPOSTS05],
            self::COMPONENT_BLOCK_TABPANEL_CATEGORYPOSTS06 => [CPP_Module_Processor_SectionTabPanelComponents::class, CPP_Module_Processor_SectionTabPanelComponents::COMPONENT_TABPANEL_CATEGORYPOSTS06],
            self::COMPONENT_BLOCK_TABPANEL_CATEGORYPOSTS07 => [CPP_Module_Processor_SectionTabPanelComponents::class, CPP_Module_Processor_SectionTabPanelComponents::COMPONENT_TABPANEL_CATEGORYPOSTS07],
            self::COMPONENT_BLOCK_TABPANEL_CATEGORYPOSTS08 => [CPP_Module_Processor_SectionTabPanelComponents::class, CPP_Module_Processor_SectionTabPanelComponents::COMPONENT_TABPANEL_CATEGORYPOSTS08],
            self::COMPONENT_BLOCK_TABPANEL_CATEGORYPOSTS09 => [CPP_Module_Processor_SectionTabPanelComponents::class, CPP_Module_Processor_SectionTabPanelComponents::COMPONENT_TABPANEL_CATEGORYPOSTS09],
            self::COMPONENT_BLOCK_TABPANEL_CATEGORYPOSTS10 => [CPP_Module_Processor_SectionTabPanelComponents::class, CPP_Module_Processor_SectionTabPanelComponents::COMPONENT_TABPANEL_CATEGORYPOSTS10],
            self::COMPONENT_BLOCK_TABPANEL_CATEGORYPOSTS11 => [CPP_Module_Processor_SectionTabPanelComponents::class, CPP_Module_Processor_SectionTabPanelComponents::COMPONENT_TABPANEL_CATEGORYPOSTS11],
            self::COMPONENT_BLOCK_TABPANEL_CATEGORYPOSTS12 => [CPP_Module_Processor_SectionTabPanelComponents::class, CPP_Module_Processor_SectionTabPanelComponents::COMPONENT_TABPANEL_CATEGORYPOSTS12],
            self::COMPONENT_BLOCK_TABPANEL_CATEGORYPOSTS13 => [CPP_Module_Processor_SectionTabPanelComponents::class, CPP_Module_Processor_SectionTabPanelComponents::COMPONENT_TABPANEL_CATEGORYPOSTS13],
            self::COMPONENT_BLOCK_TABPANEL_CATEGORYPOSTS14 => [CPP_Module_Processor_SectionTabPanelComponents::class, CPP_Module_Processor_SectionTabPanelComponents::COMPONENT_TABPANEL_CATEGORYPOSTS14],
            self::COMPONENT_BLOCK_TABPANEL_CATEGORYPOSTS15 => [CPP_Module_Processor_SectionTabPanelComponents::class, CPP_Module_Processor_SectionTabPanelComponents::COMPONENT_TABPANEL_CATEGORYPOSTS15],
            self::COMPONENT_BLOCK_TABPANEL_CATEGORYPOSTS16 => [CPP_Module_Processor_SectionTabPanelComponents::class, CPP_Module_Processor_SectionTabPanelComponents::COMPONENT_TABPANEL_CATEGORYPOSTS16],
            self::COMPONENT_BLOCK_TABPANEL_CATEGORYPOSTS17 => [CPP_Module_Processor_SectionTabPanelComponents::class, CPP_Module_Processor_SectionTabPanelComponents::COMPONENT_TABPANEL_CATEGORYPOSTS17],
            self::COMPONENT_BLOCK_TABPANEL_CATEGORYPOSTS18 => [CPP_Module_Processor_SectionTabPanelComponents::class, CPP_Module_Processor_SectionTabPanelComponents::COMPONENT_TABPANEL_CATEGORYPOSTS18],
            self::COMPONENT_BLOCK_TABPANEL_CATEGORYPOSTS19 => [CPP_Module_Processor_SectionTabPanelComponents::class, CPP_Module_Processor_SectionTabPanelComponents::COMPONENT_TABPANEL_CATEGORYPOSTS19],
        );
        if ($inner = $inners[$component[1]] ?? null) {
            $ret[] = $inner;
        }

        return $ret;
    }

    protected function getControlgroupTopSubcomponent(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_BLOCK_TABPANEL_CATEGORYPOSTS00:
            case self::COMPONENT_BLOCK_TABPANEL_CATEGORYPOSTS01:
            case self::COMPONENT_BLOCK_TABPANEL_CATEGORYPOSTS02:
            case self::COMPONENT_BLOCK_TABPANEL_CATEGORYPOSTS03:
            case self::COMPONENT_BLOCK_TABPANEL_CATEGORYPOSTS04:
            case self::COMPONENT_BLOCK_TABPANEL_CATEGORYPOSTS05:
            case self::COMPONENT_BLOCK_TABPANEL_CATEGORYPOSTS06:
            case self::COMPONENT_BLOCK_TABPANEL_CATEGORYPOSTS07:
            case self::COMPONENT_BLOCK_TABPANEL_CATEGORYPOSTS08:
            case self::COMPONENT_BLOCK_TABPANEL_CATEGORYPOSTS09:
            case self::COMPONENT_BLOCK_TABPANEL_CATEGORYPOSTS10:
            case self::COMPONENT_BLOCK_TABPANEL_CATEGORYPOSTS11:
            case self::COMPONENT_BLOCK_TABPANEL_CATEGORYPOSTS12:
            case self::COMPONENT_BLOCK_TABPANEL_CATEGORYPOSTS13:
            case self::COMPONENT_BLOCK_TABPANEL_CATEGORYPOSTS14:
            case self::COMPONENT_BLOCK_TABPANEL_CATEGORYPOSTS15:
            case self::COMPONENT_BLOCK_TABPANEL_CATEGORYPOSTS16:
            case self::COMPONENT_BLOCK_TABPANEL_CATEGORYPOSTS17:
            case self::COMPONENT_BLOCK_TABPANEL_CATEGORYPOSTS18:
            case self::COMPONENT_BLOCK_TABPANEL_CATEGORYPOSTS19:
                return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::COMPONENT_CONTROLGROUP_POSTLIST];
        }

        return parent::getControlgroupTopSubcomponent($component);
    }

    public function getDelegatorfilterSubcomponent(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_BLOCK_TABPANEL_CATEGORYPOSTS00:
            case self::COMPONENT_BLOCK_TABPANEL_CATEGORYPOSTS01:
            case self::COMPONENT_BLOCK_TABPANEL_CATEGORYPOSTS02:
            case self::COMPONENT_BLOCK_TABPANEL_CATEGORYPOSTS03:
            case self::COMPONENT_BLOCK_TABPANEL_CATEGORYPOSTS04:
            case self::COMPONENT_BLOCK_TABPANEL_CATEGORYPOSTS05:
            case self::COMPONENT_BLOCK_TABPANEL_CATEGORYPOSTS06:
            case self::COMPONENT_BLOCK_TABPANEL_CATEGORYPOSTS07:
            case self::COMPONENT_BLOCK_TABPANEL_CATEGORYPOSTS08:
            case self::COMPONENT_BLOCK_TABPANEL_CATEGORYPOSTS09:
            case self::COMPONENT_BLOCK_TABPANEL_CATEGORYPOSTS10:
            case self::COMPONENT_BLOCK_TABPANEL_CATEGORYPOSTS11:
            case self::COMPONENT_BLOCK_TABPANEL_CATEGORYPOSTS12:
            case self::COMPONENT_BLOCK_TABPANEL_CATEGORYPOSTS13:
            case self::COMPONENT_BLOCK_TABPANEL_CATEGORYPOSTS14:
            case self::COMPONENT_BLOCK_TABPANEL_CATEGORYPOSTS15:
            case self::COMPONENT_BLOCK_TABPANEL_CATEGORYPOSTS16:
            case self::COMPONENT_BLOCK_TABPANEL_CATEGORYPOSTS17:
            case self::COMPONENT_BLOCK_TABPANEL_CATEGORYPOSTS18:
            case self::COMPONENT_BLOCK_TABPANEL_CATEGORYPOSTS19:
                return [PoP_Module_Processor_CustomFilters::class, PoP_Module_Processor_CustomFilters::COMPONENT_FILTER_CATEGORYPOSTS];
        }

        return parent::getDelegatorfilterSubcomponent($component);
    }
}


