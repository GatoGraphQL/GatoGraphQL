<?php

class UserStance_URE_Module_Processor_SectionTabPanelBlocks extends PoP_Module_Processor_TabPanelSectionBlocksBase
{
    public final const COMPONENT_BLOCK_TABPANEL_STANCES_BYORGANIZATIONS = 'block-stances-byorganizations-tabpanel';
    public final const COMPONENT_BLOCK_TABPANEL_STANCES_BYINDIVIDUALS = 'block-stances-byindividuals-tabpanel';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_BLOCK_TABPANEL_STANCES_BYORGANIZATIONS],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_STANCES_BYINDIVIDUALS],
        );
    }

    public function getRelevantRoute(\PoP\ComponentModel\Component\Component $component, array &$props): ?string
    {
        return match($component[1]) {
            self::COMPONENT_BLOCK_TABPANEL_STANCES_BYINDIVIDUALS => POP_USERSTANCE_ROUTE_STANCES_BYINDIVIDUALS,
            self::COMPONENT_BLOCK_TABPANEL_STANCES_BYORGANIZATIONS => POP_USERSTANCE_ROUTE_STANCES_BYORGANIZATIONS,
            default => parent::getRelevantRoute($component, $props),
        };
    }

    protected function getInnerSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getInnerSubcomponents($component);

        $inners = array(
            self::COMPONENT_BLOCK_TABPANEL_STANCES_BYINDIVIDUALS => [UserStance_URE_Module_Processor_SectionTabPanelComponents::class, UserStance_URE_Module_Processor_SectionTabPanelComponents::COMPONENT_TABPANEL_STANCES_BYINDIVIDUALS],
            self::COMPONENT_BLOCK_TABPANEL_STANCES_BYORGANIZATIONS => [UserStance_URE_Module_Processor_SectionTabPanelComponents::class, UserStance_URE_Module_Processor_SectionTabPanelComponents::COMPONENT_TABPANEL_STANCES_BYORGANIZATIONS],
        );
        if ($inner = $inners[$component[1]] ?? null) {
            $ret[] = $inner;
        }

        return $ret;
    }

    protected function getControlgroupTopSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_BLOCK_TABPANEL_STANCES_BYORGANIZATIONS:
            case self::COMPONENT_BLOCK_TABPANEL_STANCES_BYINDIVIDUALS:
                return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::COMPONENT_CONTROLGROUP_POSTLIST];
        }

        return parent::getControlgroupTopSubcomponent($component);
    }

    public function getDelegatorfilterSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_BLOCK_TABPANEL_STANCES_BYORGANIZATIONS:
            case self::COMPONENT_BLOCK_TABPANEL_STANCES_BYINDIVIDUALS:
                return [UserStance_Module_Processor_CustomFilters::class, UserStance_Module_Processor_CustomFilters::COMPONENT_FILTER_STANCES_AUTHORROLE];
        }

        return parent::getDelegatorfilterSubcomponent($component);
    }
}


