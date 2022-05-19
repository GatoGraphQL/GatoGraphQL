<?php

class UserStance_Module_Processor_SectionTabPanelBlocks extends PoP_Module_Processor_TabPanelSectionBlocksBase
{
    public final const COMPONENT_BLOCK_TABPANEL_STANCES = 'block-stances-tabpanel';
    public final const COMPONENT_BLOCK_TABPANEL_STANCES_PRO = 'block-stances-pro-tabpanel';
    public final const COMPONENT_BLOCK_TABPANEL_STANCES_AGAINST = 'block-stances-against-tabpanel';
    public final const COMPONENT_BLOCK_TABPANEL_STANCES_NEUTRAL = 'block-stances-neutral-tabpanel';
    public final const COMPONENT_BLOCK_TABPANEL_STANCES_PRO_GENERAL = 'block-stances-pro-general-tabpanel';
    public final const COMPONENT_BLOCK_TABPANEL_STANCES_AGAINST_GENERAL = 'block-stances-against-general-tabpanel';
    public final const COMPONENT_BLOCK_TABPANEL_STANCES_NEUTRAL_GENERAL = 'block-stances-neutral-general-tabpanel';
    public final const COMPONENT_BLOCK_TABPANEL_STANCES_PRO_ARTICLE = 'block-stances-pro-article-tabpanel';
    public final const COMPONENT_BLOCK_TABPANEL_STANCES_AGAINST_ARTICLE = 'block-stances-against-article-tabpanel';
    public final const COMPONENT_BLOCK_TABPANEL_STANCES_NEUTRAL_ARTICLE = 'block-stances-neutral-article-tabpanel';
    public final const COMPONENT_BLOCK_TABPANEL_MYSTANCES = 'block-mystances-tabpanel';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_BLOCK_TABPANEL_STANCES],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_STANCES_PRO],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_STANCES_AGAINST],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_STANCES_NEUTRAL],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_STANCES_PRO_GENERAL],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_STANCES_AGAINST_GENERAL],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_STANCES_NEUTRAL_GENERAL],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_STANCES_PRO_ARTICLE],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_STANCES_AGAINST_ARTICLE],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_STANCES_NEUTRAL_ARTICLE],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_MYSTANCES],
        );
    }

    protected function getInnerSubcomponents(array $component): array
    {
        $ret = parent::getInnerSubcomponents($component);

        $inners = array(
            self::COMPONENT_BLOCK_TABPANEL_STANCES => [UserStance_Module_Processor_SectionTabPanelComponents::class, UserStance_Module_Processor_SectionTabPanelComponents::COMPONENT_TABPANEL_STANCES],
            self::COMPONENT_BLOCK_TABPANEL_STANCES_PRO => [UserStance_Module_Processor_SectionTabPanelComponents::class, UserStance_Module_Processor_SectionTabPanelComponents::COMPONENT_TABPANEL_STANCES_PRO],
            self::COMPONENT_BLOCK_TABPANEL_STANCES_AGAINST => [UserStance_Module_Processor_SectionTabPanelComponents::class, UserStance_Module_Processor_SectionTabPanelComponents::COMPONENT_TABPANEL_STANCES_AGAINST],
            self::COMPONENT_BLOCK_TABPANEL_STANCES_NEUTRAL => [UserStance_Module_Processor_SectionTabPanelComponents::class, UserStance_Module_Processor_SectionTabPanelComponents::COMPONENT_TABPANEL_STANCES_NEUTRAL],
            self::COMPONENT_BLOCK_TABPANEL_STANCES_PRO_GENERAL => [UserStance_Module_Processor_SectionTabPanelComponents::class, UserStance_Module_Processor_SectionTabPanelComponents::COMPONENT_TABPANEL_STANCES_PRO_GENERAL],
            self::COMPONENT_BLOCK_TABPANEL_STANCES_AGAINST_GENERAL => [UserStance_Module_Processor_SectionTabPanelComponents::class, UserStance_Module_Processor_SectionTabPanelComponents::COMPONENT_TABPANEL_STANCES_AGAINST_GENERAL],
            self::COMPONENT_BLOCK_TABPANEL_STANCES_NEUTRAL_GENERAL => [UserStance_Module_Processor_SectionTabPanelComponents::class, UserStance_Module_Processor_SectionTabPanelComponents::COMPONENT_TABPANEL_STANCES_NEUTRAL_GENERAL],
            self::COMPONENT_BLOCK_TABPANEL_STANCES_PRO_ARTICLE => [UserStance_Module_Processor_SectionTabPanelComponents::class, UserStance_Module_Processor_SectionTabPanelComponents::COMPONENT_TABPANEL_STANCES_PRO_ARTICLE],
            self::COMPONENT_BLOCK_TABPANEL_STANCES_AGAINST_ARTICLE => [UserStance_Module_Processor_SectionTabPanelComponents::class, UserStance_Module_Processor_SectionTabPanelComponents::COMPONENT_TABPANEL_STANCES_AGAINST_ARTICLE],
            self::COMPONENT_BLOCK_TABPANEL_STANCES_NEUTRAL_ARTICLE => [UserStance_Module_Processor_SectionTabPanelComponents::class, UserStance_Module_Processor_SectionTabPanelComponents::COMPONENT_TABPANEL_STANCES_NEUTRAL_ARTICLE],
            self::COMPONENT_BLOCK_TABPANEL_MYSTANCES => [UserStance_Module_Processor_SectionTabPanelComponents::class, UserStance_Module_Processor_SectionTabPanelComponents::COMPONENT_TABPANEL_MYSTANCES],
        );
        if ($inner = $inners[$component[1]] ?? null) {
            $ret[] = $inner;
        }

        return $ret;
    }

    protected function getControlgroupTopSubcomponent(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_BLOCK_TABPANEL_STANCES:
            case self::COMPONENT_BLOCK_TABPANEL_STANCES_PRO:
            case self::COMPONENT_BLOCK_TABPANEL_STANCES_AGAINST:
            case self::COMPONENT_BLOCK_TABPANEL_STANCES_NEUTRAL:
            case self::COMPONENT_BLOCK_TABPANEL_STANCES_PRO_GENERAL:
            case self::COMPONENT_BLOCK_TABPANEL_STANCES_AGAINST_GENERAL:
            case self::COMPONENT_BLOCK_TABPANEL_STANCES_NEUTRAL_GENERAL:
            case self::COMPONENT_BLOCK_TABPANEL_STANCES_PRO_ARTICLE:
            case self::COMPONENT_BLOCK_TABPANEL_STANCES_AGAINST_ARTICLE:
            case self::COMPONENT_BLOCK_TABPANEL_STANCES_NEUTRAL_ARTICLE:
                return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::COMPONENT_CONTROLGROUP_POSTLIST];

            case self::COMPONENT_BLOCK_TABPANEL_MYSTANCES:
                return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::COMPONENT_CONTROLGROUP_MYCUSTOMPOSTLIST];
        }

        return parent::getControlgroupTopSubcomponent($component);
    }

    public function getDelegatorfilterSubcomponent(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_BLOCK_TABPANEL_STANCES:
                return [UserStance_Module_Processor_CustomFilters::class, UserStance_Module_Processor_CustomFilters::COMPONENT_FILTER_STANCES];

            case self::COMPONENT_BLOCK_TABPANEL_STANCES_PRO:
            case self::COMPONENT_BLOCK_TABPANEL_STANCES_AGAINST:
            case self::COMPONENT_BLOCK_TABPANEL_STANCES_NEUTRAL:
            case self::COMPONENT_BLOCK_TABPANEL_STANCES_PRO_ARTICLE:
            case self::COMPONENT_BLOCK_TABPANEL_STANCES_AGAINST_ARTICLE:
            case self::COMPONENT_BLOCK_TABPANEL_STANCES_NEUTRAL_ARTICLE:
                return [UserStance_Module_Processor_CustomFilters::class, UserStance_Module_Processor_CustomFilters::COMPONENT_FILTER_STANCES_STANCE];

            case self::COMPONENT_BLOCK_TABPANEL_STANCES_PRO_GENERAL:
            case self::COMPONENT_BLOCK_TABPANEL_STANCES_AGAINST_GENERAL:
            case self::COMPONENT_BLOCK_TABPANEL_STANCES_NEUTRAL_GENERAL:
                return [UserStance_Module_Processor_CustomFilters::class, UserStance_Module_Processor_CustomFilters::COMPONENT_FILTER_STANCES_GENERALSTANCE];

            case self::COMPONENT_BLOCK_TABPANEL_MYSTANCES:
                return [UserStance_Module_Processor_CustomFilters::class, UserStance_Module_Processor_CustomFilters::COMPONENT_FILTER_MYSTANCES];
        }

        return parent::getDelegatorfilterSubcomponent($component);
    }
}


