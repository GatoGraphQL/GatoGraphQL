<?php

use PoP\ComponentModel\State\ApplicationState;

class CPP_Module_Processor_AuthorTabPanelSectionBlocks extends PoP_Module_Processor_AuthorTabPanelSectionBlocksBase
{
    public const MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS00 = 'block-tabpanel-authorcategoryposts00';
    public const MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS01 = 'block-tabpanel-authorcategoryposts01';
    public const MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS02 = 'block-tabpanel-authorcategoryposts02';
    public const MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS03 = 'block-tabpanel-authorcategoryposts03';
    public const MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS04 = 'block-tabpanel-authorcategoryposts04';
    public const MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS05 = 'block-tabpanel-authorcategoryposts05';
    public const MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS06 = 'block-tabpanel-authorcategoryposts06';
    public const MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS07 = 'block-tabpanel-authorcategoryposts07';
    public const MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS08 = 'block-tabpanel-authorcategoryposts08';
    public const MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS09 = 'block-tabpanel-authorcategoryposts09';
    public const MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS10 = 'block-tabpanel-authorcategoryposts10';
    public const MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS11 = 'block-tabpanel-authorcategoryposts11';
    public const MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS12 = 'block-tabpanel-authorcategoryposts12';
    public const MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS13 = 'block-tabpanel-authorcategoryposts13';
    public const MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS14 = 'block-tabpanel-authorcategoryposts14';
    public const MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS15 = 'block-tabpanel-authorcategoryposts15';
    public const MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS16 = 'block-tabpanel-authorcategoryposts16';
    public const MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS17 = 'block-tabpanel-authorcategoryposts17';
    public const MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS18 = 'block-tabpanel-authorcategoryposts18';
    public const MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS19 = 'block-tabpanel-authorcategoryposts19';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS00],
            [self::class, self::MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS01],
            [self::class, self::MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS02],
            [self::class, self::MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS03],
            [self::class, self::MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS04],
            [self::class, self::MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS05],
            [self::class, self::MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS06],
            [self::class, self::MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS07],
            [self::class, self::MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS08],
            [self::class, self::MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS09],
            [self::class, self::MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS10],
            [self::class, self::MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS11],
            [self::class, self::MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS12],
            [self::class, self::MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS13],
            [self::class, self::MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS14],
            [self::class, self::MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS15],
            [self::class, self::MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS16],
            [self::class, self::MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS17],
            [self::class, self::MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS18],
            [self::class, self::MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS19],
        );
    }

    public function getRelevantRoute(array $module, array &$props): ?string
    {
        return match($module[1]) {
            self::MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS00 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS00,
            self::MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS01 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS01,
            self::MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS02 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS02,
            self::MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS03 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS03,
            self::MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS04 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS04,
            self::MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS05 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS05,
            self::MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS06 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS06,
            self::MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS07 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS07,
            self::MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS08 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS08,
            self::MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS09 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS09,
            self::MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS10 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS10,
            self::MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS11 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS11,
            self::MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS12 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS12,
            self::MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS13 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS13,
            self::MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS14 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS14,
            self::MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS15 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS15,
            self::MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS16 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS16,
            self::MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS17 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS17,
            self::MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS18 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS18,
            self::MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS19 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS19,
            default => parent::getRelevantRoute($module, $props),
        };
    }

    protected function getInnerSubmodules(array $module): array
    {
        $ret = parent::getInnerSubmodules($module);

        if (defined('POP_USERCOMMUNITIESPROCESSORS_INITIALIZED')) {
            $author = \PoP\Root\App::getState(['routing', 'queried-object-id']);
            if (gdUreIsCommunity($author)) {
                switch ($module[1]) {
                    case self::MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS00:
                    case self::MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS01:
                    case self::MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS02:
                    case self::MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS03:
                    case self::MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS04:
                    case self::MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS05:
                    case self::MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS06:
                    case self::MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS07:
                    case self::MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS08:
                    case self::MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS09:
                    case self::MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS10:
                    case self::MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS11:
                    case self::MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS12:
                    case self::MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS13:
                    case self::MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS14:
                    case self::MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS15:
                    case self::MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS16:
                    case self::MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS17:
                    case self::MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS18:
                    case self::MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS19:
                        $ret[] = [GD_URE_Module_Processor_ControlGroups::class, GD_URE_Module_Processor_ControlGroups::MODULE_URE_CONTROLGROUP_CONTENTSOURCE];
                        break;
                }
            }
        }

        $inners = array(
            self::MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS00 => [CPP_Module_Processor_AuthorSectionTabPanelComponents::class, CPP_Module_Processor_AuthorSectionTabPanelComponents::MODULE_TABPANEL_AUTHORCATEGORYPOSTS00],
            self::MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS01 => [CPP_Module_Processor_AuthorSectionTabPanelComponents::class, CPP_Module_Processor_AuthorSectionTabPanelComponents::MODULE_TABPANEL_AUTHORCATEGORYPOSTS01],
            self::MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS02 => [CPP_Module_Processor_AuthorSectionTabPanelComponents::class, CPP_Module_Processor_AuthorSectionTabPanelComponents::MODULE_TABPANEL_AUTHORCATEGORYPOSTS02],
            self::MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS03 => [CPP_Module_Processor_AuthorSectionTabPanelComponents::class, CPP_Module_Processor_AuthorSectionTabPanelComponents::MODULE_TABPANEL_AUTHORCATEGORYPOSTS03],
            self::MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS04 => [CPP_Module_Processor_AuthorSectionTabPanelComponents::class, CPP_Module_Processor_AuthorSectionTabPanelComponents::MODULE_TABPANEL_AUTHORCATEGORYPOSTS04],
            self::MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS05 => [CPP_Module_Processor_AuthorSectionTabPanelComponents::class, CPP_Module_Processor_AuthorSectionTabPanelComponents::MODULE_TABPANEL_AUTHORCATEGORYPOSTS05],
            self::MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS06 => [CPP_Module_Processor_AuthorSectionTabPanelComponents::class, CPP_Module_Processor_AuthorSectionTabPanelComponents::MODULE_TABPANEL_AUTHORCATEGORYPOSTS06],
            self::MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS07 => [CPP_Module_Processor_AuthorSectionTabPanelComponents::class, CPP_Module_Processor_AuthorSectionTabPanelComponents::MODULE_TABPANEL_AUTHORCATEGORYPOSTS07],
            self::MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS08 => [CPP_Module_Processor_AuthorSectionTabPanelComponents::class, CPP_Module_Processor_AuthorSectionTabPanelComponents::MODULE_TABPANEL_AUTHORCATEGORYPOSTS08],
            self::MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS09 => [CPP_Module_Processor_AuthorSectionTabPanelComponents::class, CPP_Module_Processor_AuthorSectionTabPanelComponents::MODULE_TABPANEL_AUTHORCATEGORYPOSTS09],
            self::MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS10 => [CPP_Module_Processor_AuthorSectionTabPanelComponents::class, CPP_Module_Processor_AuthorSectionTabPanelComponents::MODULE_TABPANEL_AUTHORCATEGORYPOSTS10],
            self::MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS11 => [CPP_Module_Processor_AuthorSectionTabPanelComponents::class, CPP_Module_Processor_AuthorSectionTabPanelComponents::MODULE_TABPANEL_AUTHORCATEGORYPOSTS11],
            self::MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS12 => [CPP_Module_Processor_AuthorSectionTabPanelComponents::class, CPP_Module_Processor_AuthorSectionTabPanelComponents::MODULE_TABPANEL_AUTHORCATEGORYPOSTS12],
            self::MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS13 => [CPP_Module_Processor_AuthorSectionTabPanelComponents::class, CPP_Module_Processor_AuthorSectionTabPanelComponents::MODULE_TABPANEL_AUTHORCATEGORYPOSTS13],
            self::MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS14 => [CPP_Module_Processor_AuthorSectionTabPanelComponents::class, CPP_Module_Processor_AuthorSectionTabPanelComponents::MODULE_TABPANEL_AUTHORCATEGORYPOSTS14],
            self::MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS15 => [CPP_Module_Processor_AuthorSectionTabPanelComponents::class, CPP_Module_Processor_AuthorSectionTabPanelComponents::MODULE_TABPANEL_AUTHORCATEGORYPOSTS15],
            self::MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS16 => [CPP_Module_Processor_AuthorSectionTabPanelComponents::class, CPP_Module_Processor_AuthorSectionTabPanelComponents::MODULE_TABPANEL_AUTHORCATEGORYPOSTS16],
            self::MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS17 => [CPP_Module_Processor_AuthorSectionTabPanelComponents::class, CPP_Module_Processor_AuthorSectionTabPanelComponents::MODULE_TABPANEL_AUTHORCATEGORYPOSTS17],
            self::MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS18 => [CPP_Module_Processor_AuthorSectionTabPanelComponents::class, CPP_Module_Processor_AuthorSectionTabPanelComponents::MODULE_TABPANEL_AUTHORCATEGORYPOSTS18],
            self::MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS19 => [CPP_Module_Processor_AuthorSectionTabPanelComponents::class, CPP_Module_Processor_AuthorSectionTabPanelComponents::MODULE_TABPANEL_AUTHORCATEGORYPOSTS19],
        );
        if ($inner = $inners[$module[1]] ?? null) {
            $ret[] = $inner;
        }

        return $ret;
    }

    public function getDelegatorfilterSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS00:
            case self::MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS01:
            case self::MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS02:
            case self::MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS03:
            case self::MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS04:
            case self::MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS05:
            case self::MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS06:
            case self::MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS07:
            case self::MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS08:
            case self::MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS09:
            case self::MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS10:
            case self::MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS11:
            case self::MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS12:
            case self::MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS13:
            case self::MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS14:
            case self::MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS15:
            case self::MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS16:
            case self::MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS17:
            case self::MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS18:
            case self::MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS19:
                return [PoP_Module_Processor_CustomFilters::class, PoP_Module_Processor_CustomFilters::MODULE_FILTER_AUTHORCATEGORYPOSTS];
        }

        return parent::getDelegatorfilterSubmodule($module);
    }
}


