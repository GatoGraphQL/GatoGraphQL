<?php

class UserStance_URE_Module_Processor_SectionTabPanelBlocks extends PoP_Module_Processor_TabPanelSectionBlocksBase
{
    public final const MODULE_BLOCK_TABPANEL_STANCES_BYORGANIZATIONS = 'block-stances-byorganizations-tabpanel';
    public final const MODULE_BLOCK_TABPANEL_STANCES_BYINDIVIDUALS = 'block-stances-byindividuals-tabpanel';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BLOCK_TABPANEL_STANCES_BYORGANIZATIONS],
            [self::class, self::MODULE_BLOCK_TABPANEL_STANCES_BYINDIVIDUALS],
        );
    }

    public function getRelevantRoute(array $componentVariation, array &$props): ?string
    {
        return match($componentVariation[1]) {
            self::MODULE_BLOCK_TABPANEL_STANCES_BYINDIVIDUALS => POP_USERSTANCE_ROUTE_STANCES_BYINDIVIDUALS,
            self::MODULE_BLOCK_TABPANEL_STANCES_BYORGANIZATIONS => POP_USERSTANCE_ROUTE_STANCES_BYORGANIZATIONS,
            default => parent::getRelevantRoute($componentVariation, $props),
        };
    }

    protected function getInnerSubmodules(array $componentVariation): array
    {
        $ret = parent::getInnerSubmodules($componentVariation);

        $inners = array(
            self::MODULE_BLOCK_TABPANEL_STANCES_BYINDIVIDUALS => [UserStance_URE_Module_Processor_SectionTabPanelComponents::class, UserStance_URE_Module_Processor_SectionTabPanelComponents::MODULE_TABPANEL_STANCES_BYINDIVIDUALS],
            self::MODULE_BLOCK_TABPANEL_STANCES_BYORGANIZATIONS => [UserStance_URE_Module_Processor_SectionTabPanelComponents::class, UserStance_URE_Module_Processor_SectionTabPanelComponents::MODULE_TABPANEL_STANCES_BYORGANIZATIONS],
        );
        if ($inner = $inners[$componentVariation[1]] ?? null) {
            $ret[] = $inner;
        }

        return $ret;
    }

    protected function getControlgroupTopSubmodule(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_BLOCK_TABPANEL_STANCES_BYORGANIZATIONS:
            case self::MODULE_BLOCK_TABPANEL_STANCES_BYINDIVIDUALS:
                return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::MODULE_CONTROLGROUP_POSTLIST];
        }

        return parent::getControlgroupTopSubmodule($componentVariation);
    }

    public function getDelegatorfilterSubmodule(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_BLOCK_TABPANEL_STANCES_BYORGANIZATIONS:
            case self::MODULE_BLOCK_TABPANEL_STANCES_BYINDIVIDUALS:
                return [UserStance_Module_Processor_CustomFilters::class, UserStance_Module_Processor_CustomFilters::MODULE_FILTER_STANCES_AUTHORROLE];
        }

        return parent::getDelegatorfilterSubmodule($componentVariation);
    }
}


