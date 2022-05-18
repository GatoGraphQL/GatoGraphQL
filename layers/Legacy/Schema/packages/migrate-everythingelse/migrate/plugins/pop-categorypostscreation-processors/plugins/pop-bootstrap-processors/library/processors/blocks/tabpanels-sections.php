<?php

class LPPC_Module_Processor_SectionTabPanelBlocks extends PoP_Module_Processor_TabPanelSectionBlocksBase
{
    public final const MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS00 = 'block-mycategoryposts00-tabpanel';
    public final const MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS01 = 'block-mycategoryposts01-tabpanel';
    public final const MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS02 = 'block-mycategoryposts02-tabpanel';
    public final const MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS03 = 'block-mycategoryposts03-tabpanel';
    public final const MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS04 = 'block-mycategoryposts04-tabpanel';
    public final const MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS05 = 'block-mycategoryposts05-tabpanel';
    public final const MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS06 = 'block-mycategoryposts06-tabpanel';
    public final const MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS07 = 'block-mycategoryposts07-tabpanel';
    public final const MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS08 = 'block-mycategoryposts08-tabpanel';
    public final const MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS09 = 'block-mycategoryposts09-tabpanel';
    public final const MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS10 = 'block-mycategoryposts10-tabpanel';
    public final const MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS11 = 'block-mycategoryposts11-tabpanel';
    public final const MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS12 = 'block-mycategoryposts12-tabpanel';
    public final const MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS13 = 'block-mycategoryposts13-tabpanel';
    public final const MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS14 = 'block-mycategoryposts14-tabpanel';
    public final const MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS15 = 'block-mycategoryposts15-tabpanel';
    public final const MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS16 = 'block-mycategoryposts16-tabpanel';
    public final const MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS17 = 'block-mycategoryposts17-tabpanel';
    public final const MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS18 = 'block-mycategoryposts18-tabpanel';
    public final const MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS19 = 'block-mycategoryposts19-tabpanel';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS00],
            [self::class, self::MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS01],
            [self::class, self::MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS02],
            [self::class, self::MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS03],
            [self::class, self::MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS04],
            [self::class, self::MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS05],
            [self::class, self::MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS06],
            [self::class, self::MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS07],
            [self::class, self::MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS08],
            [self::class, self::MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS09],
            [self::class, self::MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS10],
            [self::class, self::MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS11],
            [self::class, self::MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS12],
            [self::class, self::MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS13],
            [self::class, self::MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS14],
            [self::class, self::MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS15],
            [self::class, self::MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS16],
            [self::class, self::MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS17],
            [self::class, self::MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS18],
            [self::class, self::MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS19],
        );
    }

    protected function getInnerSubmodules(array $componentVariation): array
    {
        $ret = parent::getInnerSubmodules($componentVariation);

        $inners = array(
            self::MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS00 => [LPPC_Module_Processor_SectionTabPanelComponents::class, LPPC_Module_Processor_SectionTabPanelComponents::MODULE_TABPANEL_MYCATEGORYPOSTS00],
            self::MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS01 => [LPPC_Module_Processor_SectionTabPanelComponents::class, LPPC_Module_Processor_SectionTabPanelComponents::MODULE_TABPANEL_MYCATEGORYPOSTS01],
            self::MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS02 => [LPPC_Module_Processor_SectionTabPanelComponents::class, LPPC_Module_Processor_SectionTabPanelComponents::MODULE_TABPANEL_MYCATEGORYPOSTS02],
            self::MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS03 => [LPPC_Module_Processor_SectionTabPanelComponents::class, LPPC_Module_Processor_SectionTabPanelComponents::MODULE_TABPANEL_MYCATEGORYPOSTS03],
            self::MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS04 => [LPPC_Module_Processor_SectionTabPanelComponents::class, LPPC_Module_Processor_SectionTabPanelComponents::MODULE_TABPANEL_MYCATEGORYPOSTS04],
            self::MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS05 => [LPPC_Module_Processor_SectionTabPanelComponents::class, LPPC_Module_Processor_SectionTabPanelComponents::MODULE_TABPANEL_MYCATEGORYPOSTS05],
            self::MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS06 => [LPPC_Module_Processor_SectionTabPanelComponents::class, LPPC_Module_Processor_SectionTabPanelComponents::MODULE_TABPANEL_MYCATEGORYPOSTS06],
            self::MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS07 => [LPPC_Module_Processor_SectionTabPanelComponents::class, LPPC_Module_Processor_SectionTabPanelComponents::MODULE_TABPANEL_MYCATEGORYPOSTS07],
            self::MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS08 => [LPPC_Module_Processor_SectionTabPanelComponents::class, LPPC_Module_Processor_SectionTabPanelComponents::MODULE_TABPANEL_MYCATEGORYPOSTS08],
            self::MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS09 => [LPPC_Module_Processor_SectionTabPanelComponents::class, LPPC_Module_Processor_SectionTabPanelComponents::MODULE_TABPANEL_MYCATEGORYPOSTS09],
            self::MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS10 => [LPPC_Module_Processor_SectionTabPanelComponents::class, LPPC_Module_Processor_SectionTabPanelComponents::MODULE_TABPANEL_MYCATEGORYPOSTS10],
            self::MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS11 => [LPPC_Module_Processor_SectionTabPanelComponents::class, LPPC_Module_Processor_SectionTabPanelComponents::MODULE_TABPANEL_MYCATEGORYPOSTS11],
            self::MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS12 => [LPPC_Module_Processor_SectionTabPanelComponents::class, LPPC_Module_Processor_SectionTabPanelComponents::MODULE_TABPANEL_MYCATEGORYPOSTS12],
            self::MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS13 => [LPPC_Module_Processor_SectionTabPanelComponents::class, LPPC_Module_Processor_SectionTabPanelComponents::MODULE_TABPANEL_MYCATEGORYPOSTS13],
            self::MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS14 => [LPPC_Module_Processor_SectionTabPanelComponents::class, LPPC_Module_Processor_SectionTabPanelComponents::MODULE_TABPANEL_MYCATEGORYPOSTS14],
            self::MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS15 => [LPPC_Module_Processor_SectionTabPanelComponents::class, LPPC_Module_Processor_SectionTabPanelComponents::MODULE_TABPANEL_MYCATEGORYPOSTS15],
            self::MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS16 => [LPPC_Module_Processor_SectionTabPanelComponents::class, LPPC_Module_Processor_SectionTabPanelComponents::MODULE_TABPANEL_MYCATEGORYPOSTS16],
            self::MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS17 => [LPPC_Module_Processor_SectionTabPanelComponents::class, LPPC_Module_Processor_SectionTabPanelComponents::MODULE_TABPANEL_MYCATEGORYPOSTS17],
            self::MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS18 => [LPPC_Module_Processor_SectionTabPanelComponents::class, LPPC_Module_Processor_SectionTabPanelComponents::MODULE_TABPANEL_MYCATEGORYPOSTS18],
            self::MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS19 => [LPPC_Module_Processor_SectionTabPanelComponents::class, LPPC_Module_Processor_SectionTabPanelComponents::MODULE_TABPANEL_MYCATEGORYPOSTS19],
        );
        if ($inner = $inners[$componentVariation[1]] ?? null) {
            $ret[] = $inner;
        }

        return $ret;
    }

    protected function getControlgroupTopSubmodule(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS00:
            case self::MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS01:
            case self::MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS02:
            case self::MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS03:
            case self::MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS04:
            case self::MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS05:
            case self::MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS06:
            case self::MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS07:
            case self::MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS08:
            case self::MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS09:
            case self::MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS10:
            case self::MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS11:
            case self::MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS12:
            case self::MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS13:
            case self::MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS14:
            case self::MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS15:
            case self::MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS16:
            case self::MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS17:
            case self::MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS18:
            case self::MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS19:
                return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::MODULE_CONTROLGROUP_MYPOSTLIST];
        }

        return parent::getControlgroupTopSubmodule($componentVariation);
    }

    public function getDelegatorfilterSubmodule(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS00:
            case self::MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS01:
            case self::MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS02:
            case self::MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS03:
            case self::MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS04:
            case self::MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS05:
            case self::MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS06:
            case self::MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS07:
            case self::MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS08:
            case self::MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS09:
            case self::MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS10:
            case self::MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS11:
            case self::MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS12:
            case self::MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS13:
            case self::MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS14:
            case self::MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS15:
            case self::MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS16:
            case self::MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS17:
            case self::MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS18:
            case self::MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS19:
                return [PoP_Module_Processor_CustomFilters::class, PoP_Module_Processor_CustomFilters::MODULE_FILTER_MYCATEGORYPOSTS];
        }

        return parent::getDelegatorfilterSubmodule($componentVariation);
    }
}


