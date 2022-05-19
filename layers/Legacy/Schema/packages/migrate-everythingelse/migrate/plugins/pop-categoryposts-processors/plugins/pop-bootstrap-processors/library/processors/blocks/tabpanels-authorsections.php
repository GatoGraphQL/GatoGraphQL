<?php

use PoP\ComponentModel\State\ApplicationState;

class CPP_Module_Processor_AuthorTabPanelSectionBlocks extends PoP_Module_Processor_AuthorTabPanelSectionBlocksBase
{
    public final const COMPONENT_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS00 = 'block-tabpanel-authorcategoryposts00';
    public final const COMPONENT_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS01 = 'block-tabpanel-authorcategoryposts01';
    public final const COMPONENT_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS02 = 'block-tabpanel-authorcategoryposts02';
    public final const COMPONENT_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS03 = 'block-tabpanel-authorcategoryposts03';
    public final const COMPONENT_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS04 = 'block-tabpanel-authorcategoryposts04';
    public final const COMPONENT_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS05 = 'block-tabpanel-authorcategoryposts05';
    public final const COMPONENT_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS06 = 'block-tabpanel-authorcategoryposts06';
    public final const COMPONENT_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS07 = 'block-tabpanel-authorcategoryposts07';
    public final const COMPONENT_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS08 = 'block-tabpanel-authorcategoryposts08';
    public final const COMPONENT_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS09 = 'block-tabpanel-authorcategoryposts09';
    public final const COMPONENT_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS10 = 'block-tabpanel-authorcategoryposts10';
    public final const COMPONENT_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS11 = 'block-tabpanel-authorcategoryposts11';
    public final const COMPONENT_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS12 = 'block-tabpanel-authorcategoryposts12';
    public final const COMPONENT_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS13 = 'block-tabpanel-authorcategoryposts13';
    public final const COMPONENT_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS14 = 'block-tabpanel-authorcategoryposts14';
    public final const COMPONENT_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS15 = 'block-tabpanel-authorcategoryposts15';
    public final const COMPONENT_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS16 = 'block-tabpanel-authorcategoryposts16';
    public final const COMPONENT_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS17 = 'block-tabpanel-authorcategoryposts17';
    public final const COMPONENT_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS18 = 'block-tabpanel-authorcategoryposts18';
    public final const COMPONENT_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS19 = 'block-tabpanel-authorcategoryposts19';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS00],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS01],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS02],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS03],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS04],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS05],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS06],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS07],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS08],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS09],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS10],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS11],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS12],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS13],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS14],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS15],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS16],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS17],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS18],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS19],
        );
    }

    public function getRelevantRoute(array $component, array &$props): ?string
    {
        return match($component[1]) {
            self::COMPONENT_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS00 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS00,
            self::COMPONENT_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS01 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS01,
            self::COMPONENT_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS02 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS02,
            self::COMPONENT_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS03 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS03,
            self::COMPONENT_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS04 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS04,
            self::COMPONENT_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS05 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS05,
            self::COMPONENT_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS06 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS06,
            self::COMPONENT_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS07 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS07,
            self::COMPONENT_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS08 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS08,
            self::COMPONENT_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS09 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS09,
            self::COMPONENT_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS10 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS10,
            self::COMPONENT_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS11 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS11,
            self::COMPONENT_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS12 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS12,
            self::COMPONENT_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS13 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS13,
            self::COMPONENT_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS14 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS14,
            self::COMPONENT_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS15 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS15,
            self::COMPONENT_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS16 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS16,
            self::COMPONENT_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS17 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS17,
            self::COMPONENT_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS18 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS18,
            self::COMPONENT_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS19 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS19,
            default => parent::getRelevantRoute($component, $props),
        };
    }

    protected function getInnerSubcomponents(array $component): array
    {
        $ret = parent::getInnerSubcomponents($component);

        if (defined('POP_USERCOMMUNITIESPROCESSORS_INITIALIZED')) {
            $author = \PoP\Root\App::getState(['routing', 'queried-object-id']);
            if (gdUreIsCommunity($author)) {
                switch ($component[1]) {
                    case self::COMPONENT_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS00:
                    case self::COMPONENT_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS01:
                    case self::COMPONENT_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS02:
                    case self::COMPONENT_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS03:
                    case self::COMPONENT_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS04:
                    case self::COMPONENT_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS05:
                    case self::COMPONENT_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS06:
                    case self::COMPONENT_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS07:
                    case self::COMPONENT_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS08:
                    case self::COMPONENT_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS09:
                    case self::COMPONENT_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS10:
                    case self::COMPONENT_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS11:
                    case self::COMPONENT_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS12:
                    case self::COMPONENT_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS13:
                    case self::COMPONENT_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS14:
                    case self::COMPONENT_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS15:
                    case self::COMPONENT_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS16:
                    case self::COMPONENT_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS17:
                    case self::COMPONENT_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS18:
                    case self::COMPONENT_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS19:
                        $ret[] = [GD_URE_Module_Processor_ControlGroups::class, GD_URE_Module_Processor_ControlGroups::COMPONENT_URE_CONTROLGROUP_CONTENTSOURCE];
                        break;
                }
            }
        }

        $inners = array(
            self::COMPONENT_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS00 => [CPP_Module_Processor_AuthorSectionTabPanelComponents::class, CPP_Module_Processor_AuthorSectionTabPanelComponents::COMPONENT_TABPANEL_AUTHORCATEGORYPOSTS00],
            self::COMPONENT_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS01 => [CPP_Module_Processor_AuthorSectionTabPanelComponents::class, CPP_Module_Processor_AuthorSectionTabPanelComponents::COMPONENT_TABPANEL_AUTHORCATEGORYPOSTS01],
            self::COMPONENT_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS02 => [CPP_Module_Processor_AuthorSectionTabPanelComponents::class, CPP_Module_Processor_AuthorSectionTabPanelComponents::COMPONENT_TABPANEL_AUTHORCATEGORYPOSTS02],
            self::COMPONENT_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS03 => [CPP_Module_Processor_AuthorSectionTabPanelComponents::class, CPP_Module_Processor_AuthorSectionTabPanelComponents::COMPONENT_TABPANEL_AUTHORCATEGORYPOSTS03],
            self::COMPONENT_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS04 => [CPP_Module_Processor_AuthorSectionTabPanelComponents::class, CPP_Module_Processor_AuthorSectionTabPanelComponents::COMPONENT_TABPANEL_AUTHORCATEGORYPOSTS04],
            self::COMPONENT_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS05 => [CPP_Module_Processor_AuthorSectionTabPanelComponents::class, CPP_Module_Processor_AuthorSectionTabPanelComponents::COMPONENT_TABPANEL_AUTHORCATEGORYPOSTS05],
            self::COMPONENT_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS06 => [CPP_Module_Processor_AuthorSectionTabPanelComponents::class, CPP_Module_Processor_AuthorSectionTabPanelComponents::COMPONENT_TABPANEL_AUTHORCATEGORYPOSTS06],
            self::COMPONENT_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS07 => [CPP_Module_Processor_AuthorSectionTabPanelComponents::class, CPP_Module_Processor_AuthorSectionTabPanelComponents::COMPONENT_TABPANEL_AUTHORCATEGORYPOSTS07],
            self::COMPONENT_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS08 => [CPP_Module_Processor_AuthorSectionTabPanelComponents::class, CPP_Module_Processor_AuthorSectionTabPanelComponents::COMPONENT_TABPANEL_AUTHORCATEGORYPOSTS08],
            self::COMPONENT_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS09 => [CPP_Module_Processor_AuthorSectionTabPanelComponents::class, CPP_Module_Processor_AuthorSectionTabPanelComponents::COMPONENT_TABPANEL_AUTHORCATEGORYPOSTS09],
            self::COMPONENT_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS10 => [CPP_Module_Processor_AuthorSectionTabPanelComponents::class, CPP_Module_Processor_AuthorSectionTabPanelComponents::COMPONENT_TABPANEL_AUTHORCATEGORYPOSTS10],
            self::COMPONENT_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS11 => [CPP_Module_Processor_AuthorSectionTabPanelComponents::class, CPP_Module_Processor_AuthorSectionTabPanelComponents::COMPONENT_TABPANEL_AUTHORCATEGORYPOSTS11],
            self::COMPONENT_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS12 => [CPP_Module_Processor_AuthorSectionTabPanelComponents::class, CPP_Module_Processor_AuthorSectionTabPanelComponents::COMPONENT_TABPANEL_AUTHORCATEGORYPOSTS12],
            self::COMPONENT_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS13 => [CPP_Module_Processor_AuthorSectionTabPanelComponents::class, CPP_Module_Processor_AuthorSectionTabPanelComponents::COMPONENT_TABPANEL_AUTHORCATEGORYPOSTS13],
            self::COMPONENT_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS14 => [CPP_Module_Processor_AuthorSectionTabPanelComponents::class, CPP_Module_Processor_AuthorSectionTabPanelComponents::COMPONENT_TABPANEL_AUTHORCATEGORYPOSTS14],
            self::COMPONENT_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS15 => [CPP_Module_Processor_AuthorSectionTabPanelComponents::class, CPP_Module_Processor_AuthorSectionTabPanelComponents::COMPONENT_TABPANEL_AUTHORCATEGORYPOSTS15],
            self::COMPONENT_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS16 => [CPP_Module_Processor_AuthorSectionTabPanelComponents::class, CPP_Module_Processor_AuthorSectionTabPanelComponents::COMPONENT_TABPANEL_AUTHORCATEGORYPOSTS16],
            self::COMPONENT_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS17 => [CPP_Module_Processor_AuthorSectionTabPanelComponents::class, CPP_Module_Processor_AuthorSectionTabPanelComponents::COMPONENT_TABPANEL_AUTHORCATEGORYPOSTS17],
            self::COMPONENT_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS18 => [CPP_Module_Processor_AuthorSectionTabPanelComponents::class, CPP_Module_Processor_AuthorSectionTabPanelComponents::COMPONENT_TABPANEL_AUTHORCATEGORYPOSTS18],
            self::COMPONENT_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS19 => [CPP_Module_Processor_AuthorSectionTabPanelComponents::class, CPP_Module_Processor_AuthorSectionTabPanelComponents::COMPONENT_TABPANEL_AUTHORCATEGORYPOSTS19],
        );
        if ($inner = $inners[$component[1]] ?? null) {
            $ret[] = $inner;
        }

        return $ret;
    }

    public function getDelegatorfilterSubcomponent(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS00:
            case self::COMPONENT_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS01:
            case self::COMPONENT_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS02:
            case self::COMPONENT_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS03:
            case self::COMPONENT_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS04:
            case self::COMPONENT_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS05:
            case self::COMPONENT_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS06:
            case self::COMPONENT_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS07:
            case self::COMPONENT_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS08:
            case self::COMPONENT_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS09:
            case self::COMPONENT_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS10:
            case self::COMPONENT_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS11:
            case self::COMPONENT_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS12:
            case self::COMPONENT_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS13:
            case self::COMPONENT_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS14:
            case self::COMPONENT_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS15:
            case self::COMPONENT_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS16:
            case self::COMPONENT_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS17:
            case self::COMPONENT_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS18:
            case self::COMPONENT_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS19:
                return [PoP_Module_Processor_CustomFilters::class, PoP_Module_Processor_CustomFilters::COMPONENT_FILTER_AUTHORCATEGORYPOSTS];
        }

        return parent::getDelegatorfilterSubcomponent($component);
    }
}


