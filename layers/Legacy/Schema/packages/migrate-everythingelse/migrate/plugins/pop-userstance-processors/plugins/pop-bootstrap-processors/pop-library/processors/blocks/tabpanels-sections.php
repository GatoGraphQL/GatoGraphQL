<?php

class UserStance_Module_Processor_SectionTabPanelBlocks extends PoP_Module_Processor_TabPanelSectionBlocksBase
{
    public final const MODULE_BLOCK_TABPANEL_STANCES = 'block-stances-tabpanel';
    public final const MODULE_BLOCK_TABPANEL_STANCES_PRO = 'block-stances-pro-tabpanel';
    public final const MODULE_BLOCK_TABPANEL_STANCES_AGAINST = 'block-stances-against-tabpanel';
    public final const MODULE_BLOCK_TABPANEL_STANCES_NEUTRAL = 'block-stances-neutral-tabpanel';
    public final const MODULE_BLOCK_TABPANEL_STANCES_PRO_GENERAL = 'block-stances-pro-general-tabpanel';
    public final const MODULE_BLOCK_TABPANEL_STANCES_AGAINST_GENERAL = 'block-stances-against-general-tabpanel';
    public final const MODULE_BLOCK_TABPANEL_STANCES_NEUTRAL_GENERAL = 'block-stances-neutral-general-tabpanel';
    public final const MODULE_BLOCK_TABPANEL_STANCES_PRO_ARTICLE = 'block-stances-pro-article-tabpanel';
    public final const MODULE_BLOCK_TABPANEL_STANCES_AGAINST_ARTICLE = 'block-stances-against-article-tabpanel';
    public final const MODULE_BLOCK_TABPANEL_STANCES_NEUTRAL_ARTICLE = 'block-stances-neutral-article-tabpanel';
    public final const MODULE_BLOCK_TABPANEL_MYSTANCES = 'block-mystances-tabpanel';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BLOCK_TABPANEL_STANCES],
            [self::class, self::MODULE_BLOCK_TABPANEL_STANCES_PRO],
            [self::class, self::MODULE_BLOCK_TABPANEL_STANCES_AGAINST],
            [self::class, self::MODULE_BLOCK_TABPANEL_STANCES_NEUTRAL],
            [self::class, self::MODULE_BLOCK_TABPANEL_STANCES_PRO_GENERAL],
            [self::class, self::MODULE_BLOCK_TABPANEL_STANCES_AGAINST_GENERAL],
            [self::class, self::MODULE_BLOCK_TABPANEL_STANCES_NEUTRAL_GENERAL],
            [self::class, self::MODULE_BLOCK_TABPANEL_STANCES_PRO_ARTICLE],
            [self::class, self::MODULE_BLOCK_TABPANEL_STANCES_AGAINST_ARTICLE],
            [self::class, self::MODULE_BLOCK_TABPANEL_STANCES_NEUTRAL_ARTICLE],
            [self::class, self::MODULE_BLOCK_TABPANEL_MYSTANCES],
        );
    }

    protected function getInnerSubmodules(array $componentVariation): array
    {
        $ret = parent::getInnerSubmodules($componentVariation);

        $inners = array(
            self::MODULE_BLOCK_TABPANEL_STANCES => [UserStance_Module_Processor_SectionTabPanelComponents::class, UserStance_Module_Processor_SectionTabPanelComponents::MODULE_TABPANEL_STANCES],
            self::MODULE_BLOCK_TABPANEL_STANCES_PRO => [UserStance_Module_Processor_SectionTabPanelComponents::class, UserStance_Module_Processor_SectionTabPanelComponents::MODULE_TABPANEL_STANCES_PRO],
            self::MODULE_BLOCK_TABPANEL_STANCES_AGAINST => [UserStance_Module_Processor_SectionTabPanelComponents::class, UserStance_Module_Processor_SectionTabPanelComponents::MODULE_TABPANEL_STANCES_AGAINST],
            self::MODULE_BLOCK_TABPANEL_STANCES_NEUTRAL => [UserStance_Module_Processor_SectionTabPanelComponents::class, UserStance_Module_Processor_SectionTabPanelComponents::MODULE_TABPANEL_STANCES_NEUTRAL],
            self::MODULE_BLOCK_TABPANEL_STANCES_PRO_GENERAL => [UserStance_Module_Processor_SectionTabPanelComponents::class, UserStance_Module_Processor_SectionTabPanelComponents::MODULE_TABPANEL_STANCES_PRO_GENERAL],
            self::MODULE_BLOCK_TABPANEL_STANCES_AGAINST_GENERAL => [UserStance_Module_Processor_SectionTabPanelComponents::class, UserStance_Module_Processor_SectionTabPanelComponents::MODULE_TABPANEL_STANCES_AGAINST_GENERAL],
            self::MODULE_BLOCK_TABPANEL_STANCES_NEUTRAL_GENERAL => [UserStance_Module_Processor_SectionTabPanelComponents::class, UserStance_Module_Processor_SectionTabPanelComponents::MODULE_TABPANEL_STANCES_NEUTRAL_GENERAL],
            self::MODULE_BLOCK_TABPANEL_STANCES_PRO_ARTICLE => [UserStance_Module_Processor_SectionTabPanelComponents::class, UserStance_Module_Processor_SectionTabPanelComponents::MODULE_TABPANEL_STANCES_PRO_ARTICLE],
            self::MODULE_BLOCK_TABPANEL_STANCES_AGAINST_ARTICLE => [UserStance_Module_Processor_SectionTabPanelComponents::class, UserStance_Module_Processor_SectionTabPanelComponents::MODULE_TABPANEL_STANCES_AGAINST_ARTICLE],
            self::MODULE_BLOCK_TABPANEL_STANCES_NEUTRAL_ARTICLE => [UserStance_Module_Processor_SectionTabPanelComponents::class, UserStance_Module_Processor_SectionTabPanelComponents::MODULE_TABPANEL_STANCES_NEUTRAL_ARTICLE],
            self::MODULE_BLOCK_TABPANEL_MYSTANCES => [UserStance_Module_Processor_SectionTabPanelComponents::class, UserStance_Module_Processor_SectionTabPanelComponents::MODULE_TABPANEL_MYSTANCES],
        );
        if ($inner = $inners[$componentVariation[1]] ?? null) {
            $ret[] = $inner;
        }

        return $ret;
    }

    protected function getControlgroupTopSubmodule(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_BLOCK_TABPANEL_STANCES:
            case self::MODULE_BLOCK_TABPANEL_STANCES_PRO:
            case self::MODULE_BLOCK_TABPANEL_STANCES_AGAINST:
            case self::MODULE_BLOCK_TABPANEL_STANCES_NEUTRAL:
            case self::MODULE_BLOCK_TABPANEL_STANCES_PRO_GENERAL:
            case self::MODULE_BLOCK_TABPANEL_STANCES_AGAINST_GENERAL:
            case self::MODULE_BLOCK_TABPANEL_STANCES_NEUTRAL_GENERAL:
            case self::MODULE_BLOCK_TABPANEL_STANCES_PRO_ARTICLE:
            case self::MODULE_BLOCK_TABPANEL_STANCES_AGAINST_ARTICLE:
            case self::MODULE_BLOCK_TABPANEL_STANCES_NEUTRAL_ARTICLE:
                return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::MODULE_CONTROLGROUP_POSTLIST];

            case self::MODULE_BLOCK_TABPANEL_MYSTANCES:
                return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::MODULE_CONTROLGROUP_MYCUSTOMPOSTLIST];
        }

        return parent::getControlgroupTopSubmodule($componentVariation);
    }

    public function getDelegatorfilterSubmodule(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_BLOCK_TABPANEL_STANCES:
                return [UserStance_Module_Processor_CustomFilters::class, UserStance_Module_Processor_CustomFilters::MODULE_FILTER_STANCES];

            case self::MODULE_BLOCK_TABPANEL_STANCES_PRO:
            case self::MODULE_BLOCK_TABPANEL_STANCES_AGAINST:
            case self::MODULE_BLOCK_TABPANEL_STANCES_NEUTRAL:
            case self::MODULE_BLOCK_TABPANEL_STANCES_PRO_ARTICLE:
            case self::MODULE_BLOCK_TABPANEL_STANCES_AGAINST_ARTICLE:
            case self::MODULE_BLOCK_TABPANEL_STANCES_NEUTRAL_ARTICLE:
                return [UserStance_Module_Processor_CustomFilters::class, UserStance_Module_Processor_CustomFilters::MODULE_FILTER_STANCES_STANCE];

            case self::MODULE_BLOCK_TABPANEL_STANCES_PRO_GENERAL:
            case self::MODULE_BLOCK_TABPANEL_STANCES_AGAINST_GENERAL:
            case self::MODULE_BLOCK_TABPANEL_STANCES_NEUTRAL_GENERAL:
                return [UserStance_Module_Processor_CustomFilters::class, UserStance_Module_Processor_CustomFilters::MODULE_FILTER_STANCES_GENERALSTANCE];

            case self::MODULE_BLOCK_TABPANEL_MYSTANCES:
                return [UserStance_Module_Processor_CustomFilters::class, UserStance_Module_Processor_CustomFilters::MODULE_FILTER_MYSTANCES];
        }

        return parent::getDelegatorfilterSubmodule($componentVariation);
    }
}


