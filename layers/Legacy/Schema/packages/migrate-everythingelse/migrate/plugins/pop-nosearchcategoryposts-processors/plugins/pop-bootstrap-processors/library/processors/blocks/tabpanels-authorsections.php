<?php

use PoP\ComponentModel\State\ApplicationState;

class NSCPP_Module_Processor_AuthorSectionTabPanelBlocks extends PoP_Module_Processor_AuthorTabPanelSectionBlocksBase
{
    public final const MODULE_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS00 = 'block-tabpanel-authornosearchcategoryposts00';
    public final const MODULE_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS01 = 'block-tabpanel-authornosearchcategoryposts01';
    public final const MODULE_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS02 = 'block-tabpanel-authornosearchcategoryposts02';
    public final const MODULE_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS03 = 'block-tabpanel-authornosearchcategoryposts03';
    public final const MODULE_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS04 = 'block-tabpanel-authornosearchcategoryposts04';
    public final const MODULE_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS05 = 'block-tabpanel-authornosearchcategoryposts05';
    public final const MODULE_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS06 = 'block-tabpanel-authornosearchcategoryposts06';
    public final const MODULE_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS07 = 'block-tabpanel-authornosearchcategoryposts07';
    public final const MODULE_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS08 = 'block-tabpanel-authornosearchcategoryposts08';
    public final const MODULE_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS09 = 'block-tabpanel-authornosearchcategoryposts09';
    public final const MODULE_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS10 = 'block-tabpanel-authornosearchcategoryposts10';
    public final const MODULE_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS11 = 'block-tabpanel-authornosearchcategoryposts11';
    public final const MODULE_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS12 = 'block-tabpanel-authornosearchcategoryposts12';
    public final const MODULE_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS13 = 'block-tabpanel-authornosearchcategoryposts13';
    public final const MODULE_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS14 = 'block-tabpanel-authornosearchcategoryposts14';
    public final const MODULE_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS15 = 'block-tabpanel-authornosearchcategoryposts15';
    public final const MODULE_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS16 = 'block-tabpanel-authornosearchcategoryposts16';
    public final const MODULE_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS17 = 'block-tabpanel-authornosearchcategoryposts17';
    public final const MODULE_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS18 = 'block-tabpanel-authornosearchcategoryposts18';
    public final const MODULE_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS19 = 'block-tabpanel-authornosearchcategoryposts19';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS00],
            [self::class, self::MODULE_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS01],
            [self::class, self::MODULE_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS02],
            [self::class, self::MODULE_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS03],
            [self::class, self::MODULE_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS04],
            [self::class, self::MODULE_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS05],
            [self::class, self::MODULE_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS06],
            [self::class, self::MODULE_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS07],
            [self::class, self::MODULE_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS08],
            [self::class, self::MODULE_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS09],
            [self::class, self::MODULE_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS10],
            [self::class, self::MODULE_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS11],
            [self::class, self::MODULE_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS12],
            [self::class, self::MODULE_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS13],
            [self::class, self::MODULE_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS14],
            [self::class, self::MODULE_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS15],
            [self::class, self::MODULE_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS16],
            [self::class, self::MODULE_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS17],
            [self::class, self::MODULE_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS18],
            [self::class, self::MODULE_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS19],
        );
    }

    protected function getInnerSubmodules(array $module): array
    {
        $ret = parent::getInnerSubmodules($module);

        if (defined('POP_USERCOMMUNITIESPROCESSORS_INITIALIZED')) {
            $author = \PoP\Root\App::getState(['routing', 'queried-object-id']);
            if (gdUreIsCommunity($author)) {
                switch ($module[1]) {
                    case self::MODULE_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS00:
                    case self::MODULE_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS01:
                    case self::MODULE_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS02:
                    case self::MODULE_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS03:
                    case self::MODULE_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS04:
                    case self::MODULE_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS05:
                    case self::MODULE_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS06:
                    case self::MODULE_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS07:
                    case self::MODULE_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS08:
                    case self::MODULE_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS09:
                    case self::MODULE_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS10:
                    case self::MODULE_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS11:
                    case self::MODULE_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS12:
                    case self::MODULE_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS13:
                    case self::MODULE_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS14:
                    case self::MODULE_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS15:
                    case self::MODULE_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS16:
                    case self::MODULE_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS17:
                    case self::MODULE_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS18:
                    case self::MODULE_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS19:
                        $ret[] = [GD_URE_Module_Processor_ControlGroups::class, GD_URE_Module_Processor_ControlGroups::MODULE_URE_CONTROLGROUP_CONTENTSOURCE];
                        break;
                }
            }
        }

        $inners = array(
            self::MODULE_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS00 => [NSCPP_Module_Processor_AuthorSectionTabPanelComponents::class, NSCPP_Module_Processor_AuthorSectionTabPanelComponents::MODULE_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS00],
            self::MODULE_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS01 => [NSCPP_Module_Processor_AuthorSectionTabPanelComponents::class, NSCPP_Module_Processor_AuthorSectionTabPanelComponents::MODULE_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS01],
            self::MODULE_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS02 => [NSCPP_Module_Processor_AuthorSectionTabPanelComponents::class, NSCPP_Module_Processor_AuthorSectionTabPanelComponents::MODULE_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS02],
            self::MODULE_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS03 => [NSCPP_Module_Processor_AuthorSectionTabPanelComponents::class, NSCPP_Module_Processor_AuthorSectionTabPanelComponents::MODULE_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS03],
            self::MODULE_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS04 => [NSCPP_Module_Processor_AuthorSectionTabPanelComponents::class, NSCPP_Module_Processor_AuthorSectionTabPanelComponents::MODULE_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS04],
            self::MODULE_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS05 => [NSCPP_Module_Processor_AuthorSectionTabPanelComponents::class, NSCPP_Module_Processor_AuthorSectionTabPanelComponents::MODULE_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS05],
            self::MODULE_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS06 => [NSCPP_Module_Processor_AuthorSectionTabPanelComponents::class, NSCPP_Module_Processor_AuthorSectionTabPanelComponents::MODULE_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS06],
            self::MODULE_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS07 => [NSCPP_Module_Processor_AuthorSectionTabPanelComponents::class, NSCPP_Module_Processor_AuthorSectionTabPanelComponents::MODULE_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS07],
            self::MODULE_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS08 => [NSCPP_Module_Processor_AuthorSectionTabPanelComponents::class, NSCPP_Module_Processor_AuthorSectionTabPanelComponents::MODULE_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS08],
            self::MODULE_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS09 => [NSCPP_Module_Processor_AuthorSectionTabPanelComponents::class, NSCPP_Module_Processor_AuthorSectionTabPanelComponents::MODULE_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS09],
            self::MODULE_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS10 => [NSCPP_Module_Processor_AuthorSectionTabPanelComponents::class, NSCPP_Module_Processor_AuthorSectionTabPanelComponents::MODULE_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS10],
            self::MODULE_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS11 => [NSCPP_Module_Processor_AuthorSectionTabPanelComponents::class, NSCPP_Module_Processor_AuthorSectionTabPanelComponents::MODULE_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS11],
            self::MODULE_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS12 => [NSCPP_Module_Processor_AuthorSectionTabPanelComponents::class, NSCPP_Module_Processor_AuthorSectionTabPanelComponents::MODULE_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS12],
            self::MODULE_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS13 => [NSCPP_Module_Processor_AuthorSectionTabPanelComponents::class, NSCPP_Module_Processor_AuthorSectionTabPanelComponents::MODULE_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS13],
            self::MODULE_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS14 => [NSCPP_Module_Processor_AuthorSectionTabPanelComponents::class, NSCPP_Module_Processor_AuthorSectionTabPanelComponents::MODULE_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS14],
            self::MODULE_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS15 => [NSCPP_Module_Processor_AuthorSectionTabPanelComponents::class, NSCPP_Module_Processor_AuthorSectionTabPanelComponents::MODULE_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS15],
            self::MODULE_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS16 => [NSCPP_Module_Processor_AuthorSectionTabPanelComponents::class, NSCPP_Module_Processor_AuthorSectionTabPanelComponents::MODULE_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS16],
            self::MODULE_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS17 => [NSCPP_Module_Processor_AuthorSectionTabPanelComponents::class, NSCPP_Module_Processor_AuthorSectionTabPanelComponents::MODULE_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS17],
            self::MODULE_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS18 => [NSCPP_Module_Processor_AuthorSectionTabPanelComponents::class, NSCPP_Module_Processor_AuthorSectionTabPanelComponents::MODULE_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS18],
            self::MODULE_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS19 => [NSCPP_Module_Processor_AuthorSectionTabPanelComponents::class, NSCPP_Module_Processor_AuthorSectionTabPanelComponents::MODULE_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS19],
        );
        if ($inner = $inners[$module[1]] ?? null) {
            $ret[] = $inner;
        }

        return $ret;
    }

    public function getDelegatorfilterSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS00:
            case self::MODULE_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS01:
            case self::MODULE_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS02:
            case self::MODULE_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS03:
            case self::MODULE_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS04:
            case self::MODULE_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS05:
            case self::MODULE_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS06:
            case self::MODULE_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS07:
            case self::MODULE_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS08:
            case self::MODULE_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS09:
                return [PoP_Module_Processor_CustomFilters::class, PoP_Module_Processor_CustomFilters::MODULE_FILTER_AUTHORCATEGORYPOSTS];
        }

        return parent::getDelegatorfilterSubmodule($module);
    }
}


