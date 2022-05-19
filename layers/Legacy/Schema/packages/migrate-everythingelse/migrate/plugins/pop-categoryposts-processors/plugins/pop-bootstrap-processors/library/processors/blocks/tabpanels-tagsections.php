<?php

class CPP_Module_Processor_TagTabPanelSectionBlocks extends PoP_Module_Processor_TagTabPanelSectionBlocksBase
{
    public final const COMPONENT_BLOCK_TABPANEL_TAGCATEGORYPOSTS00 = 'block-tabpanel-tagcategoryposts00';
    public final const COMPONENT_BLOCK_TABPANEL_TAGCATEGORYPOSTS01 = 'block-tabpanel-tagcategoryposts01';
    public final const COMPONENT_BLOCK_TABPANEL_TAGCATEGORYPOSTS02 = 'block-tabpanel-tagcategoryposts02';
    public final const COMPONENT_BLOCK_TABPANEL_TAGCATEGORYPOSTS03 = 'block-tabpanel-tagcategoryposts03';
    public final const COMPONENT_BLOCK_TABPANEL_TAGCATEGORYPOSTS04 = 'block-tabpanel-tagcategoryposts04';
    public final const COMPONENT_BLOCK_TABPANEL_TAGCATEGORYPOSTS05 = 'block-tabpanel-tagcategoryposts05';
    public final const COMPONENT_BLOCK_TABPANEL_TAGCATEGORYPOSTS06 = 'block-tabpanel-tagcategoryposts06';
    public final const COMPONENT_BLOCK_TABPANEL_TAGCATEGORYPOSTS07 = 'block-tabpanel-tagcategoryposts07';
    public final const COMPONENT_BLOCK_TABPANEL_TAGCATEGORYPOSTS08 = 'block-tabpanel-tagcategoryposts08';
    public final const COMPONENT_BLOCK_TABPANEL_TAGCATEGORYPOSTS09 = 'block-tabpanel-tagcategoryposts09';
    public final const COMPONENT_BLOCK_TABPANEL_TAGCATEGORYPOSTS10 = 'block-tabpanel-tagcategoryposts10';
    public final const COMPONENT_BLOCK_TABPANEL_TAGCATEGORYPOSTS11 = 'block-tabpanel-tagcategoryposts11';
    public final const COMPONENT_BLOCK_TABPANEL_TAGCATEGORYPOSTS12 = 'block-tabpanel-tagcategoryposts12';
    public final const COMPONENT_BLOCK_TABPANEL_TAGCATEGORYPOSTS13 = 'block-tabpanel-tagcategoryposts13';
    public final const COMPONENT_BLOCK_TABPANEL_TAGCATEGORYPOSTS14 = 'block-tabpanel-tagcategoryposts14';
    public final const COMPONENT_BLOCK_TABPANEL_TAGCATEGORYPOSTS15 = 'block-tabpanel-tagcategoryposts15';
    public final const COMPONENT_BLOCK_TABPANEL_TAGCATEGORYPOSTS16 = 'block-tabpanel-tagcategoryposts16';
    public final const COMPONENT_BLOCK_TABPANEL_TAGCATEGORYPOSTS17 = 'block-tabpanel-tagcategoryposts17';
    public final const COMPONENT_BLOCK_TABPANEL_TAGCATEGORYPOSTS18 = 'block-tabpanel-tagcategoryposts18';
    public final const COMPONENT_BLOCK_TABPANEL_TAGCATEGORYPOSTS19 = 'block-tabpanel-tagcategoryposts19';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_BLOCK_TABPANEL_TAGCATEGORYPOSTS00],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_TAGCATEGORYPOSTS01],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_TAGCATEGORYPOSTS02],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_TAGCATEGORYPOSTS03],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_TAGCATEGORYPOSTS04],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_TAGCATEGORYPOSTS05],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_TAGCATEGORYPOSTS06],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_TAGCATEGORYPOSTS07],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_TAGCATEGORYPOSTS08],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_TAGCATEGORYPOSTS09],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_TAGCATEGORYPOSTS10],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_TAGCATEGORYPOSTS11],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_TAGCATEGORYPOSTS12],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_TAGCATEGORYPOSTS13],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_TAGCATEGORYPOSTS14],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_TAGCATEGORYPOSTS15],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_TAGCATEGORYPOSTS16],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_TAGCATEGORYPOSTS17],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_TAGCATEGORYPOSTS18],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_TAGCATEGORYPOSTS19],
        );
    }

    public function getRelevantRoute(array $component, array &$props): ?string
    {
        return match($component[1]) {
            self::COMPONENT_BLOCK_TABPANEL_TAGCATEGORYPOSTS00 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS00,
            self::COMPONENT_BLOCK_TABPANEL_TAGCATEGORYPOSTS01 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS01,
            self::COMPONENT_BLOCK_TABPANEL_TAGCATEGORYPOSTS02 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS02,
            self::COMPONENT_BLOCK_TABPANEL_TAGCATEGORYPOSTS03 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS03,
            self::COMPONENT_BLOCK_TABPANEL_TAGCATEGORYPOSTS04 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS04,
            self::COMPONENT_BLOCK_TABPANEL_TAGCATEGORYPOSTS05 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS05,
            self::COMPONENT_BLOCK_TABPANEL_TAGCATEGORYPOSTS06 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS06,
            self::COMPONENT_BLOCK_TABPANEL_TAGCATEGORYPOSTS07 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS07,
            self::COMPONENT_BLOCK_TABPANEL_TAGCATEGORYPOSTS08 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS08,
            self::COMPONENT_BLOCK_TABPANEL_TAGCATEGORYPOSTS09 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS09,
            self::COMPONENT_BLOCK_TABPANEL_TAGCATEGORYPOSTS10 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS10,
            self::COMPONENT_BLOCK_TABPANEL_TAGCATEGORYPOSTS11 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS11,
            self::COMPONENT_BLOCK_TABPANEL_TAGCATEGORYPOSTS12 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS12,
            self::COMPONENT_BLOCK_TABPANEL_TAGCATEGORYPOSTS13 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS13,
            self::COMPONENT_BLOCK_TABPANEL_TAGCATEGORYPOSTS14 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS14,
            self::COMPONENT_BLOCK_TABPANEL_TAGCATEGORYPOSTS15 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS15,
            self::COMPONENT_BLOCK_TABPANEL_TAGCATEGORYPOSTS16 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS16,
            self::COMPONENT_BLOCK_TABPANEL_TAGCATEGORYPOSTS17 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS17,
            self::COMPONENT_BLOCK_TABPANEL_TAGCATEGORYPOSTS18 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS18,
            self::COMPONENT_BLOCK_TABPANEL_TAGCATEGORYPOSTS19 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS19,
            default => parent::getRelevantRoute($component, $props),
        };
    }

    public function getInnerSubcomponents(array $component): array
    {
        $ret = parent::getInnerSubcomponents($component);

        $inners = array(
            self::COMPONENT_BLOCK_TABPANEL_TAGCATEGORYPOSTS00 => [CPP_Module_Processor_TagSectionTabPanelComponents::class, CPP_Module_Processor_TagSectionTabPanelComponents::COMPONENT_TABPANEL_TAGCATEGORYPOSTS00],
            self::COMPONENT_BLOCK_TABPANEL_TAGCATEGORYPOSTS01 => [CPP_Module_Processor_TagSectionTabPanelComponents::class, CPP_Module_Processor_TagSectionTabPanelComponents::COMPONENT_TABPANEL_TAGCATEGORYPOSTS01],
            self::COMPONENT_BLOCK_TABPANEL_TAGCATEGORYPOSTS02 => [CPP_Module_Processor_TagSectionTabPanelComponents::class, CPP_Module_Processor_TagSectionTabPanelComponents::COMPONENT_TABPANEL_TAGCATEGORYPOSTS02],
            self::COMPONENT_BLOCK_TABPANEL_TAGCATEGORYPOSTS03 => [CPP_Module_Processor_TagSectionTabPanelComponents::class, CPP_Module_Processor_TagSectionTabPanelComponents::COMPONENT_TABPANEL_TAGCATEGORYPOSTS03],
            self::COMPONENT_BLOCK_TABPANEL_TAGCATEGORYPOSTS04 => [CPP_Module_Processor_TagSectionTabPanelComponents::class, CPP_Module_Processor_TagSectionTabPanelComponents::COMPONENT_TABPANEL_TAGCATEGORYPOSTS04],
            self::COMPONENT_BLOCK_TABPANEL_TAGCATEGORYPOSTS05 => [CPP_Module_Processor_TagSectionTabPanelComponents::class, CPP_Module_Processor_TagSectionTabPanelComponents::COMPONENT_TABPANEL_TAGCATEGORYPOSTS05],
            self::COMPONENT_BLOCK_TABPANEL_TAGCATEGORYPOSTS06 => [CPP_Module_Processor_TagSectionTabPanelComponents::class, CPP_Module_Processor_TagSectionTabPanelComponents::COMPONENT_TABPANEL_TAGCATEGORYPOSTS06],
            self::COMPONENT_BLOCK_TABPANEL_TAGCATEGORYPOSTS07 => [CPP_Module_Processor_TagSectionTabPanelComponents::class, CPP_Module_Processor_TagSectionTabPanelComponents::COMPONENT_TABPANEL_TAGCATEGORYPOSTS07],
            self::COMPONENT_BLOCK_TABPANEL_TAGCATEGORYPOSTS08 => [CPP_Module_Processor_TagSectionTabPanelComponents::class, CPP_Module_Processor_TagSectionTabPanelComponents::COMPONENT_TABPANEL_TAGCATEGORYPOSTS08],
            self::COMPONENT_BLOCK_TABPANEL_TAGCATEGORYPOSTS09 => [CPP_Module_Processor_TagSectionTabPanelComponents::class, CPP_Module_Processor_TagSectionTabPanelComponents::COMPONENT_TABPANEL_TAGCATEGORYPOSTS09],
            self::COMPONENT_BLOCK_TABPANEL_TAGCATEGORYPOSTS10 => [CPP_Module_Processor_TagSectionTabPanelComponents::class, CPP_Module_Processor_TagSectionTabPanelComponents::COMPONENT_TABPANEL_TAGCATEGORYPOSTS10],
            self::COMPONENT_BLOCK_TABPANEL_TAGCATEGORYPOSTS11 => [CPP_Module_Processor_TagSectionTabPanelComponents::class, CPP_Module_Processor_TagSectionTabPanelComponents::COMPONENT_TABPANEL_TAGCATEGORYPOSTS11],
            self::COMPONENT_BLOCK_TABPANEL_TAGCATEGORYPOSTS12 => [CPP_Module_Processor_TagSectionTabPanelComponents::class, CPP_Module_Processor_TagSectionTabPanelComponents::COMPONENT_TABPANEL_TAGCATEGORYPOSTS12],
            self::COMPONENT_BLOCK_TABPANEL_TAGCATEGORYPOSTS13 => [CPP_Module_Processor_TagSectionTabPanelComponents::class, CPP_Module_Processor_TagSectionTabPanelComponents::COMPONENT_TABPANEL_TAGCATEGORYPOSTS13],
            self::COMPONENT_BLOCK_TABPANEL_TAGCATEGORYPOSTS14 => [CPP_Module_Processor_TagSectionTabPanelComponents::class, CPP_Module_Processor_TagSectionTabPanelComponents::COMPONENT_TABPANEL_TAGCATEGORYPOSTS14],
            self::COMPONENT_BLOCK_TABPANEL_TAGCATEGORYPOSTS15 => [CPP_Module_Processor_TagSectionTabPanelComponents::class, CPP_Module_Processor_TagSectionTabPanelComponents::COMPONENT_TABPANEL_TAGCATEGORYPOSTS15],
            self::COMPONENT_BLOCK_TABPANEL_TAGCATEGORYPOSTS16 => [CPP_Module_Processor_TagSectionTabPanelComponents::class, CPP_Module_Processor_TagSectionTabPanelComponents::COMPONENT_TABPANEL_TAGCATEGORYPOSTS16],
            self::COMPONENT_BLOCK_TABPANEL_TAGCATEGORYPOSTS17 => [CPP_Module_Processor_TagSectionTabPanelComponents::class, CPP_Module_Processor_TagSectionTabPanelComponents::COMPONENT_TABPANEL_TAGCATEGORYPOSTS17],
            self::COMPONENT_BLOCK_TABPANEL_TAGCATEGORYPOSTS18 => [CPP_Module_Processor_TagSectionTabPanelComponents::class, CPP_Module_Processor_TagSectionTabPanelComponents::COMPONENT_TABPANEL_TAGCATEGORYPOSTS18],
            self::COMPONENT_BLOCK_TABPANEL_TAGCATEGORYPOSTS19 => [CPP_Module_Processor_TagSectionTabPanelComponents::class, CPP_Module_Processor_TagSectionTabPanelComponents::COMPONENT_TABPANEL_TAGCATEGORYPOSTS19],
        );
        if ($inner = $inners[$component[1]] ?? null) {
            $ret[] = $inner;
        }

        return $ret;
    }

    public function getDelegatorfilterSubcomponent(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_BLOCK_TABPANEL_TAGCATEGORYPOSTS00:
            case self::COMPONENT_BLOCK_TABPANEL_TAGCATEGORYPOSTS01:
            case self::COMPONENT_BLOCK_TABPANEL_TAGCATEGORYPOSTS02:
            case self::COMPONENT_BLOCK_TABPANEL_TAGCATEGORYPOSTS03:
            case self::COMPONENT_BLOCK_TABPANEL_TAGCATEGORYPOSTS04:
            case self::COMPONENT_BLOCK_TABPANEL_TAGCATEGORYPOSTS05:
            case self::COMPONENT_BLOCK_TABPANEL_TAGCATEGORYPOSTS06:
            case self::COMPONENT_BLOCK_TABPANEL_TAGCATEGORYPOSTS07:
            case self::COMPONENT_BLOCK_TABPANEL_TAGCATEGORYPOSTS08:
            case self::COMPONENT_BLOCK_TABPANEL_TAGCATEGORYPOSTS09:
            case self::COMPONENT_BLOCK_TABPANEL_TAGCATEGORYPOSTS10:
            case self::COMPONENT_BLOCK_TABPANEL_TAGCATEGORYPOSTS11:
            case self::COMPONENT_BLOCK_TABPANEL_TAGCATEGORYPOSTS12:
            case self::COMPONENT_BLOCK_TABPANEL_TAGCATEGORYPOSTS13:
            case self::COMPONENT_BLOCK_TABPANEL_TAGCATEGORYPOSTS14:
            case self::COMPONENT_BLOCK_TABPANEL_TAGCATEGORYPOSTS15:
            case self::COMPONENT_BLOCK_TABPANEL_TAGCATEGORYPOSTS16:
            case self::COMPONENT_BLOCK_TABPANEL_TAGCATEGORYPOSTS17:
            case self::COMPONENT_BLOCK_TABPANEL_TAGCATEGORYPOSTS18:
            case self::COMPONENT_BLOCK_TABPANEL_TAGCATEGORYPOSTS19:
                return [PoP_Module_Processor_CustomFilters::class, PoP_Module_Processor_CustomFilters::COMPONENT_FILTER_TAGCONTENT];
        }

        return parent::getDelegatorfilterSubcomponent($component);
    }
}


