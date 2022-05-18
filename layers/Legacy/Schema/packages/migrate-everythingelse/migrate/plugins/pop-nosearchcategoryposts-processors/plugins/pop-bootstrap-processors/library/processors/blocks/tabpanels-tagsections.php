<?php

class NSCPP_Module_Processor_TagSectionTabPanelBlocks extends PoP_Module_Processor_TagTabPanelSectionBlocksBase
{
    public final const MODULE_BLOCK_TABPANEL_TAGNOSEARCHCATEGORYPOSTS00 = 'block-tabpanel-tagnosearchcategoryposts00';
    public final const MODULE_BLOCK_TABPANEL_TAGNOSEARCHCATEGORYPOSTS01 = 'block-tabpanel-tagnosearchcategoryposts01';
    public final const MODULE_BLOCK_TABPANEL_TAGNOSEARCHCATEGORYPOSTS02 = 'block-tabpanel-tagnosearchcategoryposts02';
    public final const MODULE_BLOCK_TABPANEL_TAGNOSEARCHCATEGORYPOSTS03 = 'block-tabpanel-tagnosearchcategoryposts03';
    public final const MODULE_BLOCK_TABPANEL_TAGNOSEARCHCATEGORYPOSTS04 = 'block-tabpanel-tagnosearchcategoryposts04';
    public final const MODULE_BLOCK_TABPANEL_TAGNOSEARCHCATEGORYPOSTS05 = 'block-tabpanel-tagnosearchcategoryposts05';
    public final const MODULE_BLOCK_TABPANEL_TAGNOSEARCHCATEGORYPOSTS06 = 'block-tabpanel-tagnosearchcategoryposts06';
    public final const MODULE_BLOCK_TABPANEL_TAGNOSEARCHCATEGORYPOSTS07 = 'block-tabpanel-tagnosearchcategoryposts07';
    public final const MODULE_BLOCK_TABPANEL_TAGNOSEARCHCATEGORYPOSTS08 = 'block-tabpanel-tagnosearchcategoryposts08';
    public final const MODULE_BLOCK_TABPANEL_TAGNOSEARCHCATEGORYPOSTS09 = 'block-tabpanel-tagnosearchcategoryposts09';
    public final const MODULE_BLOCK_TABPANEL_TAGNOSEARCHCATEGORYPOSTS10 = 'block-tabpanel-tagnosearchcategoryposts10';
    public final const MODULE_BLOCK_TABPANEL_TAGNOSEARCHCATEGORYPOSTS11 = 'block-tabpanel-tagnosearchcategoryposts11';
    public final const MODULE_BLOCK_TABPANEL_TAGNOSEARCHCATEGORYPOSTS12 = 'block-tabpanel-tagnosearchcategoryposts12';
    public final const MODULE_BLOCK_TABPANEL_TAGNOSEARCHCATEGORYPOSTS13 = 'block-tabpanel-tagnosearchcategoryposts13';
    public final const MODULE_BLOCK_TABPANEL_TAGNOSEARCHCATEGORYPOSTS14 = 'block-tabpanel-tagnosearchcategoryposts14';
    public final const MODULE_BLOCK_TABPANEL_TAGNOSEARCHCATEGORYPOSTS15 = 'block-tabpanel-tagnosearchcategoryposts15';
    public final const MODULE_BLOCK_TABPANEL_TAGNOSEARCHCATEGORYPOSTS16 = 'block-tabpanel-tagnosearchcategoryposts16';
    public final const MODULE_BLOCK_TABPANEL_TAGNOSEARCHCATEGORYPOSTS17 = 'block-tabpanel-tagnosearchcategoryposts17';
    public final const MODULE_BLOCK_TABPANEL_TAGNOSEARCHCATEGORYPOSTS18 = 'block-tabpanel-tagnosearchcategoryposts18';
    public final const MODULE_BLOCK_TABPANEL_TAGNOSEARCHCATEGORYPOSTS19 = 'block-tabpanel-tagnosearchcategoryposts19';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BLOCK_TABPANEL_TAGNOSEARCHCATEGORYPOSTS00],
            [self::class, self::MODULE_BLOCK_TABPANEL_TAGNOSEARCHCATEGORYPOSTS01],
            [self::class, self::MODULE_BLOCK_TABPANEL_TAGNOSEARCHCATEGORYPOSTS02],
            [self::class, self::MODULE_BLOCK_TABPANEL_TAGNOSEARCHCATEGORYPOSTS03],
            [self::class, self::MODULE_BLOCK_TABPANEL_TAGNOSEARCHCATEGORYPOSTS04],
            [self::class, self::MODULE_BLOCK_TABPANEL_TAGNOSEARCHCATEGORYPOSTS05],
            [self::class, self::MODULE_BLOCK_TABPANEL_TAGNOSEARCHCATEGORYPOSTS06],
            [self::class, self::MODULE_BLOCK_TABPANEL_TAGNOSEARCHCATEGORYPOSTS07],
            [self::class, self::MODULE_BLOCK_TABPANEL_TAGNOSEARCHCATEGORYPOSTS08],
            [self::class, self::MODULE_BLOCK_TABPANEL_TAGNOSEARCHCATEGORYPOSTS09],
            [self::class, self::MODULE_BLOCK_TABPANEL_TAGNOSEARCHCATEGORYPOSTS10],
            [self::class, self::MODULE_BLOCK_TABPANEL_TAGNOSEARCHCATEGORYPOSTS11],
            [self::class, self::MODULE_BLOCK_TABPANEL_TAGNOSEARCHCATEGORYPOSTS12],
            [self::class, self::MODULE_BLOCK_TABPANEL_TAGNOSEARCHCATEGORYPOSTS13],
            [self::class, self::MODULE_BLOCK_TABPANEL_TAGNOSEARCHCATEGORYPOSTS14],
            [self::class, self::MODULE_BLOCK_TABPANEL_TAGNOSEARCHCATEGORYPOSTS15],
            [self::class, self::MODULE_BLOCK_TABPANEL_TAGNOSEARCHCATEGORYPOSTS16],
            [self::class, self::MODULE_BLOCK_TABPANEL_TAGNOSEARCHCATEGORYPOSTS17],
            [self::class, self::MODULE_BLOCK_TABPANEL_TAGNOSEARCHCATEGORYPOSTS18],
            [self::class, self::MODULE_BLOCK_TABPANEL_TAGNOSEARCHCATEGORYPOSTS19],
        );
    }

    public function getSubComponentVariations(array $componentVariation): array
    {
        $ret = parent::getSubComponentVariations($componentVariation);

        $inners = array(
            self::MODULE_BLOCK_TABPANEL_TAGNOSEARCHCATEGORYPOSTS00 => [NSCPP_Module_Processor_TagSectionTabPanelComponents::class, NSCPP_Module_Processor_TagSectionTabPanelComponents::MODULE_TABPANEL_TAGNOSEARCHCATEGORYPOSTS00],
            self::MODULE_BLOCK_TABPANEL_TAGNOSEARCHCATEGORYPOSTS01 => [NSCPP_Module_Processor_TagSectionTabPanelComponents::class, NSCPP_Module_Processor_TagSectionTabPanelComponents::MODULE_TABPANEL_TAGNOSEARCHCATEGORYPOSTS01],
            self::MODULE_BLOCK_TABPANEL_TAGNOSEARCHCATEGORYPOSTS02 => [NSCPP_Module_Processor_TagSectionTabPanelComponents::class, NSCPP_Module_Processor_TagSectionTabPanelComponents::MODULE_TABPANEL_TAGNOSEARCHCATEGORYPOSTS02],
            self::MODULE_BLOCK_TABPANEL_TAGNOSEARCHCATEGORYPOSTS03 => [NSCPP_Module_Processor_TagSectionTabPanelComponents::class, NSCPP_Module_Processor_TagSectionTabPanelComponents::MODULE_TABPANEL_TAGNOSEARCHCATEGORYPOSTS03],
            self::MODULE_BLOCK_TABPANEL_TAGNOSEARCHCATEGORYPOSTS04 => [NSCPP_Module_Processor_TagSectionTabPanelComponents::class, NSCPP_Module_Processor_TagSectionTabPanelComponents::MODULE_TABPANEL_TAGNOSEARCHCATEGORYPOSTS04],
            self::MODULE_BLOCK_TABPANEL_TAGNOSEARCHCATEGORYPOSTS05 => [NSCPP_Module_Processor_TagSectionTabPanelComponents::class, NSCPP_Module_Processor_TagSectionTabPanelComponents::MODULE_TABPANEL_TAGNOSEARCHCATEGORYPOSTS05],
            self::MODULE_BLOCK_TABPANEL_TAGNOSEARCHCATEGORYPOSTS06 => [NSCPP_Module_Processor_TagSectionTabPanelComponents::class, NSCPP_Module_Processor_TagSectionTabPanelComponents::MODULE_TABPANEL_TAGNOSEARCHCATEGORYPOSTS06],
            self::MODULE_BLOCK_TABPANEL_TAGNOSEARCHCATEGORYPOSTS07 => [NSCPP_Module_Processor_TagSectionTabPanelComponents::class, NSCPP_Module_Processor_TagSectionTabPanelComponents::MODULE_TABPANEL_TAGNOSEARCHCATEGORYPOSTS07],
            self::MODULE_BLOCK_TABPANEL_TAGNOSEARCHCATEGORYPOSTS08 => [NSCPP_Module_Processor_TagSectionTabPanelComponents::class, NSCPP_Module_Processor_TagSectionTabPanelComponents::MODULE_TABPANEL_TAGNOSEARCHCATEGORYPOSTS08],
            self::MODULE_BLOCK_TABPANEL_TAGNOSEARCHCATEGORYPOSTS09 => [NSCPP_Module_Processor_TagSectionTabPanelComponents::class, NSCPP_Module_Processor_TagSectionTabPanelComponents::MODULE_TABPANEL_TAGNOSEARCHCATEGORYPOSTS09],
            self::MODULE_BLOCK_TABPANEL_TAGNOSEARCHCATEGORYPOSTS10 => [NSCPP_Module_Processor_TagSectionTabPanelComponents::class, NSCPP_Module_Processor_TagSectionTabPanelComponents::MODULE_TABPANEL_TAGNOSEARCHCATEGORYPOSTS10],
            self::MODULE_BLOCK_TABPANEL_TAGNOSEARCHCATEGORYPOSTS11 => [NSCPP_Module_Processor_TagSectionTabPanelComponents::class, NSCPP_Module_Processor_TagSectionTabPanelComponents::MODULE_TABPANEL_TAGNOSEARCHCATEGORYPOSTS11],
            self::MODULE_BLOCK_TABPANEL_TAGNOSEARCHCATEGORYPOSTS12 => [NSCPP_Module_Processor_TagSectionTabPanelComponents::class, NSCPP_Module_Processor_TagSectionTabPanelComponents::MODULE_TABPANEL_TAGNOSEARCHCATEGORYPOSTS12],
            self::MODULE_BLOCK_TABPANEL_TAGNOSEARCHCATEGORYPOSTS13 => [NSCPP_Module_Processor_TagSectionTabPanelComponents::class, NSCPP_Module_Processor_TagSectionTabPanelComponents::MODULE_TABPANEL_TAGNOSEARCHCATEGORYPOSTS13],
            self::MODULE_BLOCK_TABPANEL_TAGNOSEARCHCATEGORYPOSTS14 => [NSCPP_Module_Processor_TagSectionTabPanelComponents::class, NSCPP_Module_Processor_TagSectionTabPanelComponents::MODULE_TABPANEL_TAGNOSEARCHCATEGORYPOSTS14],
            self::MODULE_BLOCK_TABPANEL_TAGNOSEARCHCATEGORYPOSTS15 => [NSCPP_Module_Processor_TagSectionTabPanelComponents::class, NSCPP_Module_Processor_TagSectionTabPanelComponents::MODULE_TABPANEL_TAGNOSEARCHCATEGORYPOSTS15],
            self::MODULE_BLOCK_TABPANEL_TAGNOSEARCHCATEGORYPOSTS16 => [NSCPP_Module_Processor_TagSectionTabPanelComponents::class, NSCPP_Module_Processor_TagSectionTabPanelComponents::MODULE_TABPANEL_TAGNOSEARCHCATEGORYPOSTS16],
            self::MODULE_BLOCK_TABPANEL_TAGNOSEARCHCATEGORYPOSTS17 => [NSCPP_Module_Processor_TagSectionTabPanelComponents::class, NSCPP_Module_Processor_TagSectionTabPanelComponents::MODULE_TABPANEL_TAGNOSEARCHCATEGORYPOSTS17],
            self::MODULE_BLOCK_TABPANEL_TAGNOSEARCHCATEGORYPOSTS18 => [NSCPP_Module_Processor_TagSectionTabPanelComponents::class, NSCPP_Module_Processor_TagSectionTabPanelComponents::MODULE_TABPANEL_TAGNOSEARCHCATEGORYPOSTS18],
            self::MODULE_BLOCK_TABPANEL_TAGNOSEARCHCATEGORYPOSTS19 => [NSCPP_Module_Processor_TagSectionTabPanelComponents::class, NSCPP_Module_Processor_TagSectionTabPanelComponents::MODULE_TABPANEL_TAGNOSEARCHCATEGORYPOSTS19],
        );
        if ($inner = $inners[$componentVariation[1]] ?? null) {
            $ret[] = $inner;
        }

        return $ret;
    }

    public function getDelegatorfilterSubmodule(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_BLOCK_TABPANEL_TAGNOSEARCHCATEGORYPOSTS00:
            case self::MODULE_BLOCK_TABPANEL_TAGNOSEARCHCATEGORYPOSTS01:
            case self::MODULE_BLOCK_TABPANEL_TAGNOSEARCHCATEGORYPOSTS02:
            case self::MODULE_BLOCK_TABPANEL_TAGNOSEARCHCATEGORYPOSTS03:
            case self::MODULE_BLOCK_TABPANEL_TAGNOSEARCHCATEGORYPOSTS04:
            case self::MODULE_BLOCK_TABPANEL_TAGNOSEARCHCATEGORYPOSTS05:
            case self::MODULE_BLOCK_TABPANEL_TAGNOSEARCHCATEGORYPOSTS06:
            case self::MODULE_BLOCK_TABPANEL_TAGNOSEARCHCATEGORYPOSTS07:
            case self::MODULE_BLOCK_TABPANEL_TAGNOSEARCHCATEGORYPOSTS08:
            case self::MODULE_BLOCK_TABPANEL_TAGNOSEARCHCATEGORYPOSTS09:
            case self::MODULE_BLOCK_TABPANEL_TAGNOSEARCHCATEGORYPOSTS10:
            case self::MODULE_BLOCK_TABPANEL_TAGNOSEARCHCATEGORYPOSTS11:
            case self::MODULE_BLOCK_TABPANEL_TAGNOSEARCHCATEGORYPOSTS12:
            case self::MODULE_BLOCK_TABPANEL_TAGNOSEARCHCATEGORYPOSTS13:
            case self::MODULE_BLOCK_TABPANEL_TAGNOSEARCHCATEGORYPOSTS14:
            case self::MODULE_BLOCK_TABPANEL_TAGNOSEARCHCATEGORYPOSTS15:
            case self::MODULE_BLOCK_TABPANEL_TAGNOSEARCHCATEGORYPOSTS16:
            case self::MODULE_BLOCK_TABPANEL_TAGNOSEARCHCATEGORYPOSTS17:
            case self::MODULE_BLOCK_TABPANEL_TAGNOSEARCHCATEGORYPOSTS18:
            case self::MODULE_BLOCK_TABPANEL_TAGNOSEARCHCATEGORYPOSTS19:
                return [PoP_Module_Processor_CustomFilters::class, PoP_Module_Processor_CustomFilters::MODULE_FILTER_TAGCONTENT];
        }

        return parent::getDelegatorfilterSubmodule($componentVariation);
    }
}


