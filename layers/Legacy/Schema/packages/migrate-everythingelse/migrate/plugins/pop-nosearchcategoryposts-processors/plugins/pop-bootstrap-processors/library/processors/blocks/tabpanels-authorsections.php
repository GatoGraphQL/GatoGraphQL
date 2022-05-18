<?php

use PoP\ComponentModel\State\ApplicationState;

class NSCPP_Module_Processor_AuthorSectionTabPanelBlocks extends PoP_Module_Processor_AuthorTabPanelSectionBlocksBase
{
    public final const COMPONENT_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS00 = 'block-tabpanel-authornosearchcategoryposts00';
    public final const COMPONENT_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS01 = 'block-tabpanel-authornosearchcategoryposts01';
    public final const COMPONENT_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS02 = 'block-tabpanel-authornosearchcategoryposts02';
    public final const COMPONENT_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS03 = 'block-tabpanel-authornosearchcategoryposts03';
    public final const COMPONENT_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS04 = 'block-tabpanel-authornosearchcategoryposts04';
    public final const COMPONENT_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS05 = 'block-tabpanel-authornosearchcategoryposts05';
    public final const COMPONENT_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS06 = 'block-tabpanel-authornosearchcategoryposts06';
    public final const COMPONENT_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS07 = 'block-tabpanel-authornosearchcategoryposts07';
    public final const COMPONENT_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS08 = 'block-tabpanel-authornosearchcategoryposts08';
    public final const COMPONENT_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS09 = 'block-tabpanel-authornosearchcategoryposts09';
    public final const COMPONENT_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS10 = 'block-tabpanel-authornosearchcategoryposts10';
    public final const COMPONENT_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS11 = 'block-tabpanel-authornosearchcategoryposts11';
    public final const COMPONENT_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS12 = 'block-tabpanel-authornosearchcategoryposts12';
    public final const COMPONENT_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS13 = 'block-tabpanel-authornosearchcategoryposts13';
    public final const COMPONENT_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS14 = 'block-tabpanel-authornosearchcategoryposts14';
    public final const COMPONENT_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS15 = 'block-tabpanel-authornosearchcategoryposts15';
    public final const COMPONENT_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS16 = 'block-tabpanel-authornosearchcategoryposts16';
    public final const COMPONENT_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS17 = 'block-tabpanel-authornosearchcategoryposts17';
    public final const COMPONENT_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS18 = 'block-tabpanel-authornosearchcategoryposts18';
    public final const COMPONENT_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS19 = 'block-tabpanel-authornosearchcategoryposts19';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS00],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS01],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS02],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS03],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS04],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS05],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS06],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS07],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS08],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS09],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS10],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS11],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS12],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS13],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS14],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS15],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS16],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS17],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS18],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS19],
        );
    }

    protected function getInnerSubmodules(array $component): array
    {
        $ret = parent::getInnerSubmodules($component);

        if (defined('POP_USERCOMMUNITIESPROCESSORS_INITIALIZED')) {
            $author = \PoP\Root\App::getState(['routing', 'queried-object-id']);
            if (gdUreIsCommunity($author)) {
                switch ($component[1]) {
                    case self::COMPONENT_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS00:
                    case self::COMPONENT_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS01:
                    case self::COMPONENT_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS02:
                    case self::COMPONENT_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS03:
                    case self::COMPONENT_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS04:
                    case self::COMPONENT_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS05:
                    case self::COMPONENT_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS06:
                    case self::COMPONENT_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS07:
                    case self::COMPONENT_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS08:
                    case self::COMPONENT_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS09:
                    case self::COMPONENT_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS10:
                    case self::COMPONENT_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS11:
                    case self::COMPONENT_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS12:
                    case self::COMPONENT_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS13:
                    case self::COMPONENT_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS14:
                    case self::COMPONENT_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS15:
                    case self::COMPONENT_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS16:
                    case self::COMPONENT_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS17:
                    case self::COMPONENT_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS18:
                    case self::COMPONENT_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS19:
                        $ret[] = [GD_URE_Module_Processor_ControlGroups::class, GD_URE_Module_Processor_ControlGroups::COMPONENT_URE_CONTROLGROUP_CONTENTSOURCE];
                        break;
                }
            }
        }

        $inners = array(
            self::COMPONENT_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS00 => [NSCPP_Module_Processor_AuthorSectionTabPanelComponents::class, NSCPP_Module_Processor_AuthorSectionTabPanelComponents::COMPONENT_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS00],
            self::COMPONENT_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS01 => [NSCPP_Module_Processor_AuthorSectionTabPanelComponents::class, NSCPP_Module_Processor_AuthorSectionTabPanelComponents::COMPONENT_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS01],
            self::COMPONENT_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS02 => [NSCPP_Module_Processor_AuthorSectionTabPanelComponents::class, NSCPP_Module_Processor_AuthorSectionTabPanelComponents::COMPONENT_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS02],
            self::COMPONENT_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS03 => [NSCPP_Module_Processor_AuthorSectionTabPanelComponents::class, NSCPP_Module_Processor_AuthorSectionTabPanelComponents::COMPONENT_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS03],
            self::COMPONENT_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS04 => [NSCPP_Module_Processor_AuthorSectionTabPanelComponents::class, NSCPP_Module_Processor_AuthorSectionTabPanelComponents::COMPONENT_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS04],
            self::COMPONENT_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS05 => [NSCPP_Module_Processor_AuthorSectionTabPanelComponents::class, NSCPP_Module_Processor_AuthorSectionTabPanelComponents::COMPONENT_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS05],
            self::COMPONENT_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS06 => [NSCPP_Module_Processor_AuthorSectionTabPanelComponents::class, NSCPP_Module_Processor_AuthorSectionTabPanelComponents::COMPONENT_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS06],
            self::COMPONENT_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS07 => [NSCPP_Module_Processor_AuthorSectionTabPanelComponents::class, NSCPP_Module_Processor_AuthorSectionTabPanelComponents::COMPONENT_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS07],
            self::COMPONENT_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS08 => [NSCPP_Module_Processor_AuthorSectionTabPanelComponents::class, NSCPP_Module_Processor_AuthorSectionTabPanelComponents::COMPONENT_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS08],
            self::COMPONENT_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS09 => [NSCPP_Module_Processor_AuthorSectionTabPanelComponents::class, NSCPP_Module_Processor_AuthorSectionTabPanelComponents::COMPONENT_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS09],
            self::COMPONENT_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS10 => [NSCPP_Module_Processor_AuthorSectionTabPanelComponents::class, NSCPP_Module_Processor_AuthorSectionTabPanelComponents::COMPONENT_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS10],
            self::COMPONENT_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS11 => [NSCPP_Module_Processor_AuthorSectionTabPanelComponents::class, NSCPP_Module_Processor_AuthorSectionTabPanelComponents::COMPONENT_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS11],
            self::COMPONENT_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS12 => [NSCPP_Module_Processor_AuthorSectionTabPanelComponents::class, NSCPP_Module_Processor_AuthorSectionTabPanelComponents::COMPONENT_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS12],
            self::COMPONENT_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS13 => [NSCPP_Module_Processor_AuthorSectionTabPanelComponents::class, NSCPP_Module_Processor_AuthorSectionTabPanelComponents::COMPONENT_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS13],
            self::COMPONENT_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS14 => [NSCPP_Module_Processor_AuthorSectionTabPanelComponents::class, NSCPP_Module_Processor_AuthorSectionTabPanelComponents::COMPONENT_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS14],
            self::COMPONENT_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS15 => [NSCPP_Module_Processor_AuthorSectionTabPanelComponents::class, NSCPP_Module_Processor_AuthorSectionTabPanelComponents::COMPONENT_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS15],
            self::COMPONENT_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS16 => [NSCPP_Module_Processor_AuthorSectionTabPanelComponents::class, NSCPP_Module_Processor_AuthorSectionTabPanelComponents::COMPONENT_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS16],
            self::COMPONENT_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS17 => [NSCPP_Module_Processor_AuthorSectionTabPanelComponents::class, NSCPP_Module_Processor_AuthorSectionTabPanelComponents::COMPONENT_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS17],
            self::COMPONENT_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS18 => [NSCPP_Module_Processor_AuthorSectionTabPanelComponents::class, NSCPP_Module_Processor_AuthorSectionTabPanelComponents::COMPONENT_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS18],
            self::COMPONENT_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS19 => [NSCPP_Module_Processor_AuthorSectionTabPanelComponents::class, NSCPP_Module_Processor_AuthorSectionTabPanelComponents::COMPONENT_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS19],
        );
        if ($inner = $inners[$component[1]] ?? null) {
            $ret[] = $inner;
        }

        return $ret;
    }

    public function getDelegatorfilterSubmodule(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS00:
            case self::COMPONENT_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS01:
            case self::COMPONENT_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS02:
            case self::COMPONENT_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS03:
            case self::COMPONENT_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS04:
            case self::COMPONENT_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS05:
            case self::COMPONENT_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS06:
            case self::COMPONENT_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS07:
            case self::COMPONENT_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS08:
            case self::COMPONENT_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS09:
                return [PoP_Module_Processor_CustomFilters::class, PoP_Module_Processor_CustomFilters::COMPONENT_FILTER_AUTHORCATEGORYPOSTS];
        }

        return parent::getDelegatorfilterSubmodule($component);
    }
}


