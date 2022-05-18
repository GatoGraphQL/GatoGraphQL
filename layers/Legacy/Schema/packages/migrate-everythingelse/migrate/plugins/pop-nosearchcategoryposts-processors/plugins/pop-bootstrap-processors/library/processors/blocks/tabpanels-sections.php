<?php

class NSCPP_Module_Processor_SectionTabPanelBlocks extends PoP_Module_Processor_TabPanelSectionBlocksBase
{
    public final const MODULE_BLOCK_TABPANEL_NOSEARCHCATEGORYPOSTS00 = 'block-nosearchcategoryposts00-tabpanel';
    public final const MODULE_BLOCK_TABPANEL_NOSEARCHCATEGORYPOSTS01 = 'block-nosearchcategoryposts01-tabpanel';
    public final const MODULE_BLOCK_TABPANEL_NOSEARCHCATEGORYPOSTS02 = 'block-nosearchcategoryposts02-tabpanel';
    public final const MODULE_BLOCK_TABPANEL_NOSEARCHCATEGORYPOSTS03 = 'block-nosearchcategoryposts03-tabpanel';
    public final const MODULE_BLOCK_TABPANEL_NOSEARCHCATEGORYPOSTS04 = 'block-nosearchcategoryposts04-tabpanel';
    public final const MODULE_BLOCK_TABPANEL_NOSEARCHCATEGORYPOSTS05 = 'block-nosearchcategoryposts05-tabpanel';
    public final const MODULE_BLOCK_TABPANEL_NOSEARCHCATEGORYPOSTS06 = 'block-nosearchcategoryposts06-tabpanel';
    public final const MODULE_BLOCK_TABPANEL_NOSEARCHCATEGORYPOSTS07 = 'block-nosearchcategoryposts07-tabpanel';
    public final const MODULE_BLOCK_TABPANEL_NOSEARCHCATEGORYPOSTS08 = 'block-nosearchcategoryposts08-tabpanel';
    public final const MODULE_BLOCK_TABPANEL_NOSEARCHCATEGORYPOSTS09 = 'block-nosearchcategoryposts09-tabpanel';
    public final const MODULE_BLOCK_TABPANEL_NOSEARCHCATEGORYPOSTS10 = 'block-nosearchcategoryposts10-tabpanel';
    public final const MODULE_BLOCK_TABPANEL_NOSEARCHCATEGORYPOSTS11 = 'block-nosearchcategoryposts11-tabpanel';
    public final const MODULE_BLOCK_TABPANEL_NOSEARCHCATEGORYPOSTS12 = 'block-nosearchcategoryposts12-tabpanel';
    public final const MODULE_BLOCK_TABPANEL_NOSEARCHCATEGORYPOSTS13 = 'block-nosearchcategoryposts13-tabpanel';
    public final const MODULE_BLOCK_TABPANEL_NOSEARCHCATEGORYPOSTS14 = 'block-nosearchcategoryposts14-tabpanel';
    public final const MODULE_BLOCK_TABPANEL_NOSEARCHCATEGORYPOSTS15 = 'block-nosearchcategoryposts15-tabpanel';
    public final const MODULE_BLOCK_TABPANEL_NOSEARCHCATEGORYPOSTS16 = 'block-nosearchcategoryposts16-tabpanel';
    public final const MODULE_BLOCK_TABPANEL_NOSEARCHCATEGORYPOSTS17 = 'block-nosearchcategoryposts17-tabpanel';
    public final const MODULE_BLOCK_TABPANEL_NOSEARCHCATEGORYPOSTS18 = 'block-nosearchcategoryposts18-tabpanel';
    public final const MODULE_BLOCK_TABPANEL_NOSEARCHCATEGORYPOSTS19 = 'block-nosearchcategoryposts19-tabpanel';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_BLOCK_TABPANEL_NOSEARCHCATEGORYPOSTS00],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_NOSEARCHCATEGORYPOSTS01],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_NOSEARCHCATEGORYPOSTS02],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_NOSEARCHCATEGORYPOSTS03],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_NOSEARCHCATEGORYPOSTS04],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_NOSEARCHCATEGORYPOSTS05],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_NOSEARCHCATEGORYPOSTS06],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_NOSEARCHCATEGORYPOSTS07],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_NOSEARCHCATEGORYPOSTS08],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_NOSEARCHCATEGORYPOSTS09],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_NOSEARCHCATEGORYPOSTS10],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_NOSEARCHCATEGORYPOSTS11],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_NOSEARCHCATEGORYPOSTS12],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_NOSEARCHCATEGORYPOSTS13],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_NOSEARCHCATEGORYPOSTS14],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_NOSEARCHCATEGORYPOSTS15],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_NOSEARCHCATEGORYPOSTS16],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_NOSEARCHCATEGORYPOSTS17],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_NOSEARCHCATEGORYPOSTS18],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_NOSEARCHCATEGORYPOSTS19],
        );
    }

    public function getSubComponents(array $component): array
    {
        $ret = parent::getSubComponents($component);

        $inners = array(
            self::COMPONENT_BLOCK_TABPANEL_NOSEARCHCATEGORYPOSTS00 => [NSCPP_Module_Processor_SectionTabPanelComponents::class, NSCPP_Module_Processor_SectionTabPanelComponents::COMPONENT_TABPANEL_NOSEARCHCATEGORYPOSTS00],
            self::COMPONENT_BLOCK_TABPANEL_NOSEARCHCATEGORYPOSTS01 => [NSCPP_Module_Processor_SectionTabPanelComponents::class, NSCPP_Module_Processor_SectionTabPanelComponents::COMPONENT_TABPANEL_NOSEARCHCATEGORYPOSTS01],
            self::COMPONENT_BLOCK_TABPANEL_NOSEARCHCATEGORYPOSTS02 => [NSCPP_Module_Processor_SectionTabPanelComponents::class, NSCPP_Module_Processor_SectionTabPanelComponents::COMPONENT_TABPANEL_NOSEARCHCATEGORYPOSTS02],
            self::COMPONENT_BLOCK_TABPANEL_NOSEARCHCATEGORYPOSTS03 => [NSCPP_Module_Processor_SectionTabPanelComponents::class, NSCPP_Module_Processor_SectionTabPanelComponents::COMPONENT_TABPANEL_NOSEARCHCATEGORYPOSTS03],
            self::COMPONENT_BLOCK_TABPANEL_NOSEARCHCATEGORYPOSTS04 => [NSCPP_Module_Processor_SectionTabPanelComponents::class, NSCPP_Module_Processor_SectionTabPanelComponents::COMPONENT_TABPANEL_NOSEARCHCATEGORYPOSTS04],
            self::COMPONENT_BLOCK_TABPANEL_NOSEARCHCATEGORYPOSTS05 => [NSCPP_Module_Processor_SectionTabPanelComponents::class, NSCPP_Module_Processor_SectionTabPanelComponents::COMPONENT_TABPANEL_NOSEARCHCATEGORYPOSTS05],
            self::COMPONENT_BLOCK_TABPANEL_NOSEARCHCATEGORYPOSTS06 => [NSCPP_Module_Processor_SectionTabPanelComponents::class, NSCPP_Module_Processor_SectionTabPanelComponents::COMPONENT_TABPANEL_NOSEARCHCATEGORYPOSTS06],
            self::COMPONENT_BLOCK_TABPANEL_NOSEARCHCATEGORYPOSTS07 => [NSCPP_Module_Processor_SectionTabPanelComponents::class, NSCPP_Module_Processor_SectionTabPanelComponents::COMPONENT_TABPANEL_NOSEARCHCATEGORYPOSTS07],
            self::COMPONENT_BLOCK_TABPANEL_NOSEARCHCATEGORYPOSTS08 => [NSCPP_Module_Processor_SectionTabPanelComponents::class, NSCPP_Module_Processor_SectionTabPanelComponents::COMPONENT_TABPANEL_NOSEARCHCATEGORYPOSTS08],
            self::COMPONENT_BLOCK_TABPANEL_NOSEARCHCATEGORYPOSTS09 => [NSCPP_Module_Processor_SectionTabPanelComponents::class, NSCPP_Module_Processor_SectionTabPanelComponents::COMPONENT_TABPANEL_NOSEARCHCATEGORYPOSTS09],
            self::COMPONENT_BLOCK_TABPANEL_NOSEARCHCATEGORYPOSTS10 => [NSCPP_Module_Processor_SectionTabPanelComponents::class, NSCPP_Module_Processor_SectionTabPanelComponents::COMPONENT_TABPANEL_NOSEARCHCATEGORYPOSTS10],
            self::COMPONENT_BLOCK_TABPANEL_NOSEARCHCATEGORYPOSTS11 => [NSCPP_Module_Processor_SectionTabPanelComponents::class, NSCPP_Module_Processor_SectionTabPanelComponents::COMPONENT_TABPANEL_NOSEARCHCATEGORYPOSTS11],
            self::COMPONENT_BLOCK_TABPANEL_NOSEARCHCATEGORYPOSTS12 => [NSCPP_Module_Processor_SectionTabPanelComponents::class, NSCPP_Module_Processor_SectionTabPanelComponents::COMPONENT_TABPANEL_NOSEARCHCATEGORYPOSTS12],
            self::COMPONENT_BLOCK_TABPANEL_NOSEARCHCATEGORYPOSTS13 => [NSCPP_Module_Processor_SectionTabPanelComponents::class, NSCPP_Module_Processor_SectionTabPanelComponents::COMPONENT_TABPANEL_NOSEARCHCATEGORYPOSTS13],
            self::COMPONENT_BLOCK_TABPANEL_NOSEARCHCATEGORYPOSTS14 => [NSCPP_Module_Processor_SectionTabPanelComponents::class, NSCPP_Module_Processor_SectionTabPanelComponents::COMPONENT_TABPANEL_NOSEARCHCATEGORYPOSTS14],
            self::COMPONENT_BLOCK_TABPANEL_NOSEARCHCATEGORYPOSTS15 => [NSCPP_Module_Processor_SectionTabPanelComponents::class, NSCPP_Module_Processor_SectionTabPanelComponents::COMPONENT_TABPANEL_NOSEARCHCATEGORYPOSTS15],
            self::COMPONENT_BLOCK_TABPANEL_NOSEARCHCATEGORYPOSTS16 => [NSCPP_Module_Processor_SectionTabPanelComponents::class, NSCPP_Module_Processor_SectionTabPanelComponents::COMPONENT_TABPANEL_NOSEARCHCATEGORYPOSTS16],
            self::COMPONENT_BLOCK_TABPANEL_NOSEARCHCATEGORYPOSTS17 => [NSCPP_Module_Processor_SectionTabPanelComponents::class, NSCPP_Module_Processor_SectionTabPanelComponents::COMPONENT_TABPANEL_NOSEARCHCATEGORYPOSTS17],
            self::COMPONENT_BLOCK_TABPANEL_NOSEARCHCATEGORYPOSTS18 => [NSCPP_Module_Processor_SectionTabPanelComponents::class, NSCPP_Module_Processor_SectionTabPanelComponents::COMPONENT_TABPANEL_NOSEARCHCATEGORYPOSTS18],
            self::COMPONENT_BLOCK_TABPANEL_NOSEARCHCATEGORYPOSTS19 => [NSCPP_Module_Processor_SectionTabPanelComponents::class, NSCPP_Module_Processor_SectionTabPanelComponents::COMPONENT_TABPANEL_NOSEARCHCATEGORYPOSTS19],
        );
        if ($inner = $inners[$component[1]] ?? null) {
            $ret[] = $inner;
        }

        return $ret;
    }

    protected function getControlgroupTopSubmodule(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_BLOCK_TABPANEL_NOSEARCHCATEGORYPOSTS00:
            case self::COMPONENT_BLOCK_TABPANEL_NOSEARCHCATEGORYPOSTS01:
            case self::COMPONENT_BLOCK_TABPANEL_NOSEARCHCATEGORYPOSTS02:
            case self::COMPONENT_BLOCK_TABPANEL_NOSEARCHCATEGORYPOSTS03:
            case self::COMPONENT_BLOCK_TABPANEL_NOSEARCHCATEGORYPOSTS04:
            case self::COMPONENT_BLOCK_TABPANEL_NOSEARCHCATEGORYPOSTS05:
            case self::COMPONENT_BLOCK_TABPANEL_NOSEARCHCATEGORYPOSTS06:
            case self::COMPONENT_BLOCK_TABPANEL_NOSEARCHCATEGORYPOSTS07:
            case self::COMPONENT_BLOCK_TABPANEL_NOSEARCHCATEGORYPOSTS08:
            case self::COMPONENT_BLOCK_TABPANEL_NOSEARCHCATEGORYPOSTS09:
            case self::COMPONENT_BLOCK_TABPANEL_NOSEARCHCATEGORYPOSTS10:
            case self::COMPONENT_BLOCK_TABPANEL_NOSEARCHCATEGORYPOSTS11:
            case self::COMPONENT_BLOCK_TABPANEL_NOSEARCHCATEGORYPOSTS12:
            case self::COMPONENT_BLOCK_TABPANEL_NOSEARCHCATEGORYPOSTS13:
            case self::COMPONENT_BLOCK_TABPANEL_NOSEARCHCATEGORYPOSTS14:
            case self::COMPONENT_BLOCK_TABPANEL_NOSEARCHCATEGORYPOSTS15:
            case self::COMPONENT_BLOCK_TABPANEL_NOSEARCHCATEGORYPOSTS16:
            case self::COMPONENT_BLOCK_TABPANEL_NOSEARCHCATEGORYPOSTS17:
            case self::COMPONENT_BLOCK_TABPANEL_NOSEARCHCATEGORYPOSTS18:
            case self::COMPONENT_BLOCK_TABPANEL_NOSEARCHCATEGORYPOSTS19:
                return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::COMPONENT_CONTROLGROUP_POSTLIST];
        }

        return parent::getControlgroupTopSubmodule($component);
    }

    public function getDelegatorfilterSubmodule(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_BLOCK_TABPANEL_NOSEARCHCATEGORYPOSTS00:
            case self::COMPONENT_BLOCK_TABPANEL_NOSEARCHCATEGORYPOSTS01:
            case self::COMPONENT_BLOCK_TABPANEL_NOSEARCHCATEGORYPOSTS02:
            case self::COMPONENT_BLOCK_TABPANEL_NOSEARCHCATEGORYPOSTS03:
            case self::COMPONENT_BLOCK_TABPANEL_NOSEARCHCATEGORYPOSTS04:
            case self::COMPONENT_BLOCK_TABPANEL_NOSEARCHCATEGORYPOSTS05:
            case self::COMPONENT_BLOCK_TABPANEL_NOSEARCHCATEGORYPOSTS06:
            case self::COMPONENT_BLOCK_TABPANEL_NOSEARCHCATEGORYPOSTS07:
            case self::COMPONENT_BLOCK_TABPANEL_NOSEARCHCATEGORYPOSTS08:
            case self::COMPONENT_BLOCK_TABPANEL_NOSEARCHCATEGORYPOSTS09:
            case self::COMPONENT_BLOCK_TABPANEL_NOSEARCHCATEGORYPOSTS10:
            case self::COMPONENT_BLOCK_TABPANEL_NOSEARCHCATEGORYPOSTS11:
            case self::COMPONENT_BLOCK_TABPANEL_NOSEARCHCATEGORYPOSTS12:
            case self::COMPONENT_BLOCK_TABPANEL_NOSEARCHCATEGORYPOSTS13:
            case self::COMPONENT_BLOCK_TABPANEL_NOSEARCHCATEGORYPOSTS14:
            case self::COMPONENT_BLOCK_TABPANEL_NOSEARCHCATEGORYPOSTS15:
            case self::COMPONENT_BLOCK_TABPANEL_NOSEARCHCATEGORYPOSTS16:
            case self::COMPONENT_BLOCK_TABPANEL_NOSEARCHCATEGORYPOSTS17:
            case self::COMPONENT_BLOCK_TABPANEL_NOSEARCHCATEGORYPOSTS18:
            case self::COMPONENT_BLOCK_TABPANEL_NOSEARCHCATEGORYPOSTS19:
                return [PoP_Module_Processor_CustomFilters::class, PoP_Module_Processor_CustomFilters::COMPONENT_FILTER_CATEGORYPOSTS];
        }

        return parent::getDelegatorfilterSubmodule($component);
    }
}


