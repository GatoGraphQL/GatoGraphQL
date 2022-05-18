<?php

class CPP_Module_Processor_TabPanelSectionBlocks extends PoP_Module_Processor_TabPanelSectionBlocksBase
{
    public final const MODULE_BLOCK_TABPANEL_CATEGORYPOSTS00 = 'block-categoryposts00-tabpanel';
    public final const MODULE_BLOCK_TABPANEL_CATEGORYPOSTS01 = 'block-categoryposts01-tabpanel';
    public final const MODULE_BLOCK_TABPANEL_CATEGORYPOSTS02 = 'block-categoryposts02-tabpanel';
    public final const MODULE_BLOCK_TABPANEL_CATEGORYPOSTS03 = 'block-categoryposts03-tabpanel';
    public final const MODULE_BLOCK_TABPANEL_CATEGORYPOSTS04 = 'block-categoryposts04-tabpanel';
    public final const MODULE_BLOCK_TABPANEL_CATEGORYPOSTS05 = 'block-categoryposts05-tabpanel';
    public final const MODULE_BLOCK_TABPANEL_CATEGORYPOSTS06 = 'block-categoryposts06-tabpanel';
    public final const MODULE_BLOCK_TABPANEL_CATEGORYPOSTS07 = 'block-categoryposts07-tabpanel';
    public final const MODULE_BLOCK_TABPANEL_CATEGORYPOSTS08 = 'block-categoryposts08-tabpanel';
    public final const MODULE_BLOCK_TABPANEL_CATEGORYPOSTS09 = 'block-categoryposts09-tabpanel';
    public final const MODULE_BLOCK_TABPANEL_CATEGORYPOSTS10 = 'block-categoryposts10-tabpanel';
    public final const MODULE_BLOCK_TABPANEL_CATEGORYPOSTS11 = 'block-categoryposts11-tabpanel';
    public final const MODULE_BLOCK_TABPANEL_CATEGORYPOSTS12 = 'block-categoryposts12-tabpanel';
    public final const MODULE_BLOCK_TABPANEL_CATEGORYPOSTS13 = 'block-categoryposts13-tabpanel';
    public final const MODULE_BLOCK_TABPANEL_CATEGORYPOSTS14 = 'block-categoryposts14-tabpanel';
    public final const MODULE_BLOCK_TABPANEL_CATEGORYPOSTS15 = 'block-categoryposts15-tabpanel';
    public final const MODULE_BLOCK_TABPANEL_CATEGORYPOSTS16 = 'block-categoryposts16-tabpanel';
    public final const MODULE_BLOCK_TABPANEL_CATEGORYPOSTS17 = 'block-categoryposts17-tabpanel';
    public final const MODULE_BLOCK_TABPANEL_CATEGORYPOSTS18 = 'block-categoryposts18-tabpanel';
    public final const MODULE_BLOCK_TABPANEL_CATEGORYPOSTS19 = 'block-categoryposts19-tabpanel';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BLOCK_TABPANEL_CATEGORYPOSTS00],
            [self::class, self::MODULE_BLOCK_TABPANEL_CATEGORYPOSTS01],
            [self::class, self::MODULE_BLOCK_TABPANEL_CATEGORYPOSTS02],
            [self::class, self::MODULE_BLOCK_TABPANEL_CATEGORYPOSTS03],
            [self::class, self::MODULE_BLOCK_TABPANEL_CATEGORYPOSTS04],
            [self::class, self::MODULE_BLOCK_TABPANEL_CATEGORYPOSTS05],
            [self::class, self::MODULE_BLOCK_TABPANEL_CATEGORYPOSTS06],
            [self::class, self::MODULE_BLOCK_TABPANEL_CATEGORYPOSTS07],
            [self::class, self::MODULE_BLOCK_TABPANEL_CATEGORYPOSTS08],
            [self::class, self::MODULE_BLOCK_TABPANEL_CATEGORYPOSTS09],
            [self::class, self::MODULE_BLOCK_TABPANEL_CATEGORYPOSTS10],
            [self::class, self::MODULE_BLOCK_TABPANEL_CATEGORYPOSTS11],
            [self::class, self::MODULE_BLOCK_TABPANEL_CATEGORYPOSTS12],
            [self::class, self::MODULE_BLOCK_TABPANEL_CATEGORYPOSTS13],
            [self::class, self::MODULE_BLOCK_TABPANEL_CATEGORYPOSTS14],
            [self::class, self::MODULE_BLOCK_TABPANEL_CATEGORYPOSTS15],
            [self::class, self::MODULE_BLOCK_TABPANEL_CATEGORYPOSTS16],
            [self::class, self::MODULE_BLOCK_TABPANEL_CATEGORYPOSTS17],
            [self::class, self::MODULE_BLOCK_TABPANEL_CATEGORYPOSTS18],
            [self::class, self::MODULE_BLOCK_TABPANEL_CATEGORYPOSTS19],
        );
    }

    public function getRelevantRoute(array $componentVariation, array &$props): ?string
    {
        return match($componentVariation[1]) {
            self::MODULE_BLOCK_TABPANEL_CATEGORYPOSTS00 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS00,
            self::MODULE_BLOCK_TABPANEL_CATEGORYPOSTS01 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS01,
            self::MODULE_BLOCK_TABPANEL_CATEGORYPOSTS02 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS02,
            self::MODULE_BLOCK_TABPANEL_CATEGORYPOSTS03 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS03,
            self::MODULE_BLOCK_TABPANEL_CATEGORYPOSTS04 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS04,
            self::MODULE_BLOCK_TABPANEL_CATEGORYPOSTS05 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS05,
            self::MODULE_BLOCK_TABPANEL_CATEGORYPOSTS06 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS06,
            self::MODULE_BLOCK_TABPANEL_CATEGORYPOSTS07 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS07,
            self::MODULE_BLOCK_TABPANEL_CATEGORYPOSTS08 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS08,
            self::MODULE_BLOCK_TABPANEL_CATEGORYPOSTS09 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS09,
            self::MODULE_BLOCK_TABPANEL_CATEGORYPOSTS10 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS10,
            self::MODULE_BLOCK_TABPANEL_CATEGORYPOSTS11 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS11,
            self::MODULE_BLOCK_TABPANEL_CATEGORYPOSTS12 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS12,
            self::MODULE_BLOCK_TABPANEL_CATEGORYPOSTS13 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS13,
            self::MODULE_BLOCK_TABPANEL_CATEGORYPOSTS14 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS14,
            self::MODULE_BLOCK_TABPANEL_CATEGORYPOSTS15 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS15,
            self::MODULE_BLOCK_TABPANEL_CATEGORYPOSTS16 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS16,
            self::MODULE_BLOCK_TABPANEL_CATEGORYPOSTS17 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS17,
            self::MODULE_BLOCK_TABPANEL_CATEGORYPOSTS18 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS18,
            self::MODULE_BLOCK_TABPANEL_CATEGORYPOSTS19 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS19,
            default => parent::getRelevantRoute($componentVariation, $props),
        };
    }

    public function getInnerSubmodules(array $componentVariation): array
    {
        $ret = parent::getInnerSubmodules($componentVariation);

        $inners = array(
            self::MODULE_BLOCK_TABPANEL_CATEGORYPOSTS00 => [CPP_Module_Processor_SectionTabPanelComponents::class, CPP_Module_Processor_SectionTabPanelComponents::MODULE_TABPANEL_CATEGORYPOSTS00],
            self::MODULE_BLOCK_TABPANEL_CATEGORYPOSTS01 => [CPP_Module_Processor_SectionTabPanelComponents::class, CPP_Module_Processor_SectionTabPanelComponents::MODULE_TABPANEL_CATEGORYPOSTS01],
            self::MODULE_BLOCK_TABPANEL_CATEGORYPOSTS02 => [CPP_Module_Processor_SectionTabPanelComponents::class, CPP_Module_Processor_SectionTabPanelComponents::MODULE_TABPANEL_CATEGORYPOSTS02],
            self::MODULE_BLOCK_TABPANEL_CATEGORYPOSTS03 => [CPP_Module_Processor_SectionTabPanelComponents::class, CPP_Module_Processor_SectionTabPanelComponents::MODULE_TABPANEL_CATEGORYPOSTS03],
            self::MODULE_BLOCK_TABPANEL_CATEGORYPOSTS04 => [CPP_Module_Processor_SectionTabPanelComponents::class, CPP_Module_Processor_SectionTabPanelComponents::MODULE_TABPANEL_CATEGORYPOSTS04],
            self::MODULE_BLOCK_TABPANEL_CATEGORYPOSTS05 => [CPP_Module_Processor_SectionTabPanelComponents::class, CPP_Module_Processor_SectionTabPanelComponents::MODULE_TABPANEL_CATEGORYPOSTS05],
            self::MODULE_BLOCK_TABPANEL_CATEGORYPOSTS06 => [CPP_Module_Processor_SectionTabPanelComponents::class, CPP_Module_Processor_SectionTabPanelComponents::MODULE_TABPANEL_CATEGORYPOSTS06],
            self::MODULE_BLOCK_TABPANEL_CATEGORYPOSTS07 => [CPP_Module_Processor_SectionTabPanelComponents::class, CPP_Module_Processor_SectionTabPanelComponents::MODULE_TABPANEL_CATEGORYPOSTS07],
            self::MODULE_BLOCK_TABPANEL_CATEGORYPOSTS08 => [CPP_Module_Processor_SectionTabPanelComponents::class, CPP_Module_Processor_SectionTabPanelComponents::MODULE_TABPANEL_CATEGORYPOSTS08],
            self::MODULE_BLOCK_TABPANEL_CATEGORYPOSTS09 => [CPP_Module_Processor_SectionTabPanelComponents::class, CPP_Module_Processor_SectionTabPanelComponents::MODULE_TABPANEL_CATEGORYPOSTS09],
            self::MODULE_BLOCK_TABPANEL_CATEGORYPOSTS10 => [CPP_Module_Processor_SectionTabPanelComponents::class, CPP_Module_Processor_SectionTabPanelComponents::MODULE_TABPANEL_CATEGORYPOSTS10],
            self::MODULE_BLOCK_TABPANEL_CATEGORYPOSTS11 => [CPP_Module_Processor_SectionTabPanelComponents::class, CPP_Module_Processor_SectionTabPanelComponents::MODULE_TABPANEL_CATEGORYPOSTS11],
            self::MODULE_BLOCK_TABPANEL_CATEGORYPOSTS12 => [CPP_Module_Processor_SectionTabPanelComponents::class, CPP_Module_Processor_SectionTabPanelComponents::MODULE_TABPANEL_CATEGORYPOSTS12],
            self::MODULE_BLOCK_TABPANEL_CATEGORYPOSTS13 => [CPP_Module_Processor_SectionTabPanelComponents::class, CPP_Module_Processor_SectionTabPanelComponents::MODULE_TABPANEL_CATEGORYPOSTS13],
            self::MODULE_BLOCK_TABPANEL_CATEGORYPOSTS14 => [CPP_Module_Processor_SectionTabPanelComponents::class, CPP_Module_Processor_SectionTabPanelComponents::MODULE_TABPANEL_CATEGORYPOSTS14],
            self::MODULE_BLOCK_TABPANEL_CATEGORYPOSTS15 => [CPP_Module_Processor_SectionTabPanelComponents::class, CPP_Module_Processor_SectionTabPanelComponents::MODULE_TABPANEL_CATEGORYPOSTS15],
            self::MODULE_BLOCK_TABPANEL_CATEGORYPOSTS16 => [CPP_Module_Processor_SectionTabPanelComponents::class, CPP_Module_Processor_SectionTabPanelComponents::MODULE_TABPANEL_CATEGORYPOSTS16],
            self::MODULE_BLOCK_TABPANEL_CATEGORYPOSTS17 => [CPP_Module_Processor_SectionTabPanelComponents::class, CPP_Module_Processor_SectionTabPanelComponents::MODULE_TABPANEL_CATEGORYPOSTS17],
            self::MODULE_BLOCK_TABPANEL_CATEGORYPOSTS18 => [CPP_Module_Processor_SectionTabPanelComponents::class, CPP_Module_Processor_SectionTabPanelComponents::MODULE_TABPANEL_CATEGORYPOSTS18],
            self::MODULE_BLOCK_TABPANEL_CATEGORYPOSTS19 => [CPP_Module_Processor_SectionTabPanelComponents::class, CPP_Module_Processor_SectionTabPanelComponents::MODULE_TABPANEL_CATEGORYPOSTS19],
        );
        if ($inner = $inners[$componentVariation[1]] ?? null) {
            $ret[] = $inner;
        }

        return $ret;
    }

    protected function getControlgroupTopSubmodule(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_BLOCK_TABPANEL_CATEGORYPOSTS00:
            case self::MODULE_BLOCK_TABPANEL_CATEGORYPOSTS01:
            case self::MODULE_BLOCK_TABPANEL_CATEGORYPOSTS02:
            case self::MODULE_BLOCK_TABPANEL_CATEGORYPOSTS03:
            case self::MODULE_BLOCK_TABPANEL_CATEGORYPOSTS04:
            case self::MODULE_BLOCK_TABPANEL_CATEGORYPOSTS05:
            case self::MODULE_BLOCK_TABPANEL_CATEGORYPOSTS06:
            case self::MODULE_BLOCK_TABPANEL_CATEGORYPOSTS07:
            case self::MODULE_BLOCK_TABPANEL_CATEGORYPOSTS08:
            case self::MODULE_BLOCK_TABPANEL_CATEGORYPOSTS09:
            case self::MODULE_BLOCK_TABPANEL_CATEGORYPOSTS10:
            case self::MODULE_BLOCK_TABPANEL_CATEGORYPOSTS11:
            case self::MODULE_BLOCK_TABPANEL_CATEGORYPOSTS12:
            case self::MODULE_BLOCK_TABPANEL_CATEGORYPOSTS13:
            case self::MODULE_BLOCK_TABPANEL_CATEGORYPOSTS14:
            case self::MODULE_BLOCK_TABPANEL_CATEGORYPOSTS15:
            case self::MODULE_BLOCK_TABPANEL_CATEGORYPOSTS16:
            case self::MODULE_BLOCK_TABPANEL_CATEGORYPOSTS17:
            case self::MODULE_BLOCK_TABPANEL_CATEGORYPOSTS18:
            case self::MODULE_BLOCK_TABPANEL_CATEGORYPOSTS19:
                return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::MODULE_CONTROLGROUP_POSTLIST];
        }

        return parent::getControlgroupTopSubmodule($componentVariation);
    }

    public function getDelegatorfilterSubmodule(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_BLOCK_TABPANEL_CATEGORYPOSTS00:
            case self::MODULE_BLOCK_TABPANEL_CATEGORYPOSTS01:
            case self::MODULE_BLOCK_TABPANEL_CATEGORYPOSTS02:
            case self::MODULE_BLOCK_TABPANEL_CATEGORYPOSTS03:
            case self::MODULE_BLOCK_TABPANEL_CATEGORYPOSTS04:
            case self::MODULE_BLOCK_TABPANEL_CATEGORYPOSTS05:
            case self::MODULE_BLOCK_TABPANEL_CATEGORYPOSTS06:
            case self::MODULE_BLOCK_TABPANEL_CATEGORYPOSTS07:
            case self::MODULE_BLOCK_TABPANEL_CATEGORYPOSTS08:
            case self::MODULE_BLOCK_TABPANEL_CATEGORYPOSTS09:
            case self::MODULE_BLOCK_TABPANEL_CATEGORYPOSTS10:
            case self::MODULE_BLOCK_TABPANEL_CATEGORYPOSTS11:
            case self::MODULE_BLOCK_TABPANEL_CATEGORYPOSTS12:
            case self::MODULE_BLOCK_TABPANEL_CATEGORYPOSTS13:
            case self::MODULE_BLOCK_TABPANEL_CATEGORYPOSTS14:
            case self::MODULE_BLOCK_TABPANEL_CATEGORYPOSTS15:
            case self::MODULE_BLOCK_TABPANEL_CATEGORYPOSTS16:
            case self::MODULE_BLOCK_TABPANEL_CATEGORYPOSTS17:
            case self::MODULE_BLOCK_TABPANEL_CATEGORYPOSTS18:
            case self::MODULE_BLOCK_TABPANEL_CATEGORYPOSTS19:
                return [PoP_Module_Processor_CustomFilters::class, PoP_Module_Processor_CustomFilters::MODULE_FILTER_CATEGORYPOSTS];
        }

        return parent::getDelegatorfilterSubmodule($componentVariation);
    }
}


