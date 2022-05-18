<?php

class LPPC_Module_Processor_SectionTabPanelBlocks extends PoP_Module_Processor_TabPanelSectionBlocksBase
{
    public final const COMPONENT_BLOCK_TABPANEL_MYCATEGORYPOSTS00 = 'block-mycategoryposts00-tabpanel';
    public final const COMPONENT_BLOCK_TABPANEL_MYCATEGORYPOSTS01 = 'block-mycategoryposts01-tabpanel';
    public final const COMPONENT_BLOCK_TABPANEL_MYCATEGORYPOSTS02 = 'block-mycategoryposts02-tabpanel';
    public final const COMPONENT_BLOCK_TABPANEL_MYCATEGORYPOSTS03 = 'block-mycategoryposts03-tabpanel';
    public final const COMPONENT_BLOCK_TABPANEL_MYCATEGORYPOSTS04 = 'block-mycategoryposts04-tabpanel';
    public final const COMPONENT_BLOCK_TABPANEL_MYCATEGORYPOSTS05 = 'block-mycategoryposts05-tabpanel';
    public final const COMPONENT_BLOCK_TABPANEL_MYCATEGORYPOSTS06 = 'block-mycategoryposts06-tabpanel';
    public final const COMPONENT_BLOCK_TABPANEL_MYCATEGORYPOSTS07 = 'block-mycategoryposts07-tabpanel';
    public final const COMPONENT_BLOCK_TABPANEL_MYCATEGORYPOSTS08 = 'block-mycategoryposts08-tabpanel';
    public final const COMPONENT_BLOCK_TABPANEL_MYCATEGORYPOSTS09 = 'block-mycategoryposts09-tabpanel';
    public final const COMPONENT_BLOCK_TABPANEL_MYCATEGORYPOSTS10 = 'block-mycategoryposts10-tabpanel';
    public final const COMPONENT_BLOCK_TABPANEL_MYCATEGORYPOSTS11 = 'block-mycategoryposts11-tabpanel';
    public final const COMPONENT_BLOCK_TABPANEL_MYCATEGORYPOSTS12 = 'block-mycategoryposts12-tabpanel';
    public final const COMPONENT_BLOCK_TABPANEL_MYCATEGORYPOSTS13 = 'block-mycategoryposts13-tabpanel';
    public final const COMPONENT_BLOCK_TABPANEL_MYCATEGORYPOSTS14 = 'block-mycategoryposts14-tabpanel';
    public final const COMPONENT_BLOCK_TABPANEL_MYCATEGORYPOSTS15 = 'block-mycategoryposts15-tabpanel';
    public final const COMPONENT_BLOCK_TABPANEL_MYCATEGORYPOSTS16 = 'block-mycategoryposts16-tabpanel';
    public final const COMPONENT_BLOCK_TABPANEL_MYCATEGORYPOSTS17 = 'block-mycategoryposts17-tabpanel';
    public final const COMPONENT_BLOCK_TABPANEL_MYCATEGORYPOSTS18 = 'block-mycategoryposts18-tabpanel';
    public final const COMPONENT_BLOCK_TABPANEL_MYCATEGORYPOSTS19 = 'block-mycategoryposts19-tabpanel';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_BLOCK_TABPANEL_MYCATEGORYPOSTS00],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_MYCATEGORYPOSTS01],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_MYCATEGORYPOSTS02],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_MYCATEGORYPOSTS03],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_MYCATEGORYPOSTS04],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_MYCATEGORYPOSTS05],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_MYCATEGORYPOSTS06],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_MYCATEGORYPOSTS07],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_MYCATEGORYPOSTS08],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_MYCATEGORYPOSTS09],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_MYCATEGORYPOSTS10],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_MYCATEGORYPOSTS11],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_MYCATEGORYPOSTS12],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_MYCATEGORYPOSTS13],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_MYCATEGORYPOSTS14],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_MYCATEGORYPOSTS15],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_MYCATEGORYPOSTS16],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_MYCATEGORYPOSTS17],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_MYCATEGORYPOSTS18],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_MYCATEGORYPOSTS19],
        );
    }

    protected function getInnerSubmodules(array $component): array
    {
        $ret = parent::getInnerSubmodules($component);

        $inners = array(
            self::COMPONENT_BLOCK_TABPANEL_MYCATEGORYPOSTS00 => [LPPC_Module_Processor_SectionTabPanelComponents::class, LPPC_Module_Processor_SectionTabPanelComponents::COMPONENT_TABPANEL_MYCATEGORYPOSTS00],
            self::COMPONENT_BLOCK_TABPANEL_MYCATEGORYPOSTS01 => [LPPC_Module_Processor_SectionTabPanelComponents::class, LPPC_Module_Processor_SectionTabPanelComponents::COMPONENT_TABPANEL_MYCATEGORYPOSTS01],
            self::COMPONENT_BLOCK_TABPANEL_MYCATEGORYPOSTS02 => [LPPC_Module_Processor_SectionTabPanelComponents::class, LPPC_Module_Processor_SectionTabPanelComponents::COMPONENT_TABPANEL_MYCATEGORYPOSTS02],
            self::COMPONENT_BLOCK_TABPANEL_MYCATEGORYPOSTS03 => [LPPC_Module_Processor_SectionTabPanelComponents::class, LPPC_Module_Processor_SectionTabPanelComponents::COMPONENT_TABPANEL_MYCATEGORYPOSTS03],
            self::COMPONENT_BLOCK_TABPANEL_MYCATEGORYPOSTS04 => [LPPC_Module_Processor_SectionTabPanelComponents::class, LPPC_Module_Processor_SectionTabPanelComponents::COMPONENT_TABPANEL_MYCATEGORYPOSTS04],
            self::COMPONENT_BLOCK_TABPANEL_MYCATEGORYPOSTS05 => [LPPC_Module_Processor_SectionTabPanelComponents::class, LPPC_Module_Processor_SectionTabPanelComponents::COMPONENT_TABPANEL_MYCATEGORYPOSTS05],
            self::COMPONENT_BLOCK_TABPANEL_MYCATEGORYPOSTS06 => [LPPC_Module_Processor_SectionTabPanelComponents::class, LPPC_Module_Processor_SectionTabPanelComponents::COMPONENT_TABPANEL_MYCATEGORYPOSTS06],
            self::COMPONENT_BLOCK_TABPANEL_MYCATEGORYPOSTS07 => [LPPC_Module_Processor_SectionTabPanelComponents::class, LPPC_Module_Processor_SectionTabPanelComponents::COMPONENT_TABPANEL_MYCATEGORYPOSTS07],
            self::COMPONENT_BLOCK_TABPANEL_MYCATEGORYPOSTS08 => [LPPC_Module_Processor_SectionTabPanelComponents::class, LPPC_Module_Processor_SectionTabPanelComponents::COMPONENT_TABPANEL_MYCATEGORYPOSTS08],
            self::COMPONENT_BLOCK_TABPANEL_MYCATEGORYPOSTS09 => [LPPC_Module_Processor_SectionTabPanelComponents::class, LPPC_Module_Processor_SectionTabPanelComponents::COMPONENT_TABPANEL_MYCATEGORYPOSTS09],
            self::COMPONENT_BLOCK_TABPANEL_MYCATEGORYPOSTS10 => [LPPC_Module_Processor_SectionTabPanelComponents::class, LPPC_Module_Processor_SectionTabPanelComponents::COMPONENT_TABPANEL_MYCATEGORYPOSTS10],
            self::COMPONENT_BLOCK_TABPANEL_MYCATEGORYPOSTS11 => [LPPC_Module_Processor_SectionTabPanelComponents::class, LPPC_Module_Processor_SectionTabPanelComponents::COMPONENT_TABPANEL_MYCATEGORYPOSTS11],
            self::COMPONENT_BLOCK_TABPANEL_MYCATEGORYPOSTS12 => [LPPC_Module_Processor_SectionTabPanelComponents::class, LPPC_Module_Processor_SectionTabPanelComponents::COMPONENT_TABPANEL_MYCATEGORYPOSTS12],
            self::COMPONENT_BLOCK_TABPANEL_MYCATEGORYPOSTS13 => [LPPC_Module_Processor_SectionTabPanelComponents::class, LPPC_Module_Processor_SectionTabPanelComponents::COMPONENT_TABPANEL_MYCATEGORYPOSTS13],
            self::COMPONENT_BLOCK_TABPANEL_MYCATEGORYPOSTS14 => [LPPC_Module_Processor_SectionTabPanelComponents::class, LPPC_Module_Processor_SectionTabPanelComponents::COMPONENT_TABPANEL_MYCATEGORYPOSTS14],
            self::COMPONENT_BLOCK_TABPANEL_MYCATEGORYPOSTS15 => [LPPC_Module_Processor_SectionTabPanelComponents::class, LPPC_Module_Processor_SectionTabPanelComponents::COMPONENT_TABPANEL_MYCATEGORYPOSTS15],
            self::COMPONENT_BLOCK_TABPANEL_MYCATEGORYPOSTS16 => [LPPC_Module_Processor_SectionTabPanelComponents::class, LPPC_Module_Processor_SectionTabPanelComponents::COMPONENT_TABPANEL_MYCATEGORYPOSTS16],
            self::COMPONENT_BLOCK_TABPANEL_MYCATEGORYPOSTS17 => [LPPC_Module_Processor_SectionTabPanelComponents::class, LPPC_Module_Processor_SectionTabPanelComponents::COMPONENT_TABPANEL_MYCATEGORYPOSTS17],
            self::COMPONENT_BLOCK_TABPANEL_MYCATEGORYPOSTS18 => [LPPC_Module_Processor_SectionTabPanelComponents::class, LPPC_Module_Processor_SectionTabPanelComponents::COMPONENT_TABPANEL_MYCATEGORYPOSTS18],
            self::COMPONENT_BLOCK_TABPANEL_MYCATEGORYPOSTS19 => [LPPC_Module_Processor_SectionTabPanelComponents::class, LPPC_Module_Processor_SectionTabPanelComponents::COMPONENT_TABPANEL_MYCATEGORYPOSTS19],
        );
        if ($inner = $inners[$component[1]] ?? null) {
            $ret[] = $inner;
        }

        return $ret;
    }

    protected function getControlgroupTopSubmodule(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_BLOCK_TABPANEL_MYCATEGORYPOSTS00:
            case self::COMPONENT_BLOCK_TABPANEL_MYCATEGORYPOSTS01:
            case self::COMPONENT_BLOCK_TABPANEL_MYCATEGORYPOSTS02:
            case self::COMPONENT_BLOCK_TABPANEL_MYCATEGORYPOSTS03:
            case self::COMPONENT_BLOCK_TABPANEL_MYCATEGORYPOSTS04:
            case self::COMPONENT_BLOCK_TABPANEL_MYCATEGORYPOSTS05:
            case self::COMPONENT_BLOCK_TABPANEL_MYCATEGORYPOSTS06:
            case self::COMPONENT_BLOCK_TABPANEL_MYCATEGORYPOSTS07:
            case self::COMPONENT_BLOCK_TABPANEL_MYCATEGORYPOSTS08:
            case self::COMPONENT_BLOCK_TABPANEL_MYCATEGORYPOSTS09:
            case self::COMPONENT_BLOCK_TABPANEL_MYCATEGORYPOSTS10:
            case self::COMPONENT_BLOCK_TABPANEL_MYCATEGORYPOSTS11:
            case self::COMPONENT_BLOCK_TABPANEL_MYCATEGORYPOSTS12:
            case self::COMPONENT_BLOCK_TABPANEL_MYCATEGORYPOSTS13:
            case self::COMPONENT_BLOCK_TABPANEL_MYCATEGORYPOSTS14:
            case self::COMPONENT_BLOCK_TABPANEL_MYCATEGORYPOSTS15:
            case self::COMPONENT_BLOCK_TABPANEL_MYCATEGORYPOSTS16:
            case self::COMPONENT_BLOCK_TABPANEL_MYCATEGORYPOSTS17:
            case self::COMPONENT_BLOCK_TABPANEL_MYCATEGORYPOSTS18:
            case self::COMPONENT_BLOCK_TABPANEL_MYCATEGORYPOSTS19:
                return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::COMPONENT_CONTROLGROUP_MYPOSTLIST];
        }

        return parent::getControlgroupTopSubmodule($component);
    }

    public function getDelegatorfilterSubmodule(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_BLOCK_TABPANEL_MYCATEGORYPOSTS00:
            case self::COMPONENT_BLOCK_TABPANEL_MYCATEGORYPOSTS01:
            case self::COMPONENT_BLOCK_TABPANEL_MYCATEGORYPOSTS02:
            case self::COMPONENT_BLOCK_TABPANEL_MYCATEGORYPOSTS03:
            case self::COMPONENT_BLOCK_TABPANEL_MYCATEGORYPOSTS04:
            case self::COMPONENT_BLOCK_TABPANEL_MYCATEGORYPOSTS05:
            case self::COMPONENT_BLOCK_TABPANEL_MYCATEGORYPOSTS06:
            case self::COMPONENT_BLOCK_TABPANEL_MYCATEGORYPOSTS07:
            case self::COMPONENT_BLOCK_TABPANEL_MYCATEGORYPOSTS08:
            case self::COMPONENT_BLOCK_TABPANEL_MYCATEGORYPOSTS09:
            case self::COMPONENT_BLOCK_TABPANEL_MYCATEGORYPOSTS10:
            case self::COMPONENT_BLOCK_TABPANEL_MYCATEGORYPOSTS11:
            case self::COMPONENT_BLOCK_TABPANEL_MYCATEGORYPOSTS12:
            case self::COMPONENT_BLOCK_TABPANEL_MYCATEGORYPOSTS13:
            case self::COMPONENT_BLOCK_TABPANEL_MYCATEGORYPOSTS14:
            case self::COMPONENT_BLOCK_TABPANEL_MYCATEGORYPOSTS15:
            case self::COMPONENT_BLOCK_TABPANEL_MYCATEGORYPOSTS16:
            case self::COMPONENT_BLOCK_TABPANEL_MYCATEGORYPOSTS17:
            case self::COMPONENT_BLOCK_TABPANEL_MYCATEGORYPOSTS18:
            case self::COMPONENT_BLOCK_TABPANEL_MYCATEGORYPOSTS19:
                return [PoP_Module_Processor_CustomFilters::class, PoP_Module_Processor_CustomFilters::COMPONENT_FILTER_MYCATEGORYPOSTS];
        }

        return parent::getDelegatorfilterSubmodule($component);
    }
}


