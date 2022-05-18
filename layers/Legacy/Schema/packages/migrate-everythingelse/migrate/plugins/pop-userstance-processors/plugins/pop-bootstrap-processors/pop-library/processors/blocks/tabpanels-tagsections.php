<?php

class UserStance_Module_Processor_TagSectionTabPanelBlocks extends PoP_Module_Processor_TagTabPanelSectionBlocksBase
{
    public final const MODULE_BLOCK_TABPANEL_TAGSTANCES = 'block-tabpanel-tagstances';
    public final const MODULE_BLOCK_TABPANEL_TAGSTANCES_PRO = 'block-tabpanel-tagstances-pro';
    public final const MODULE_BLOCK_TABPANEL_TAGSTANCES_NEUTRAL = 'block-tabpanel-tagstances-neutral';
    public final const MODULE_BLOCK_TABPANEL_TAGSTANCES_AGAINST = 'block-tabpanel-tagstances-against';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BLOCK_TABPANEL_TAGSTANCES],
            [self::class, self::MODULE_BLOCK_TABPANEL_TAGSTANCES_PRO],
            [self::class, self::MODULE_BLOCK_TABPANEL_TAGSTANCES_NEUTRAL],
            [self::class, self::MODULE_BLOCK_TABPANEL_TAGSTANCES_AGAINST],
        );
    }

    protected function getInnerSubmodules(array $componentVariation): array
    {
        $ret = parent::getInnerSubmodules($componentVariation);

        $inners = array(
            self::MODULE_BLOCK_TABPANEL_TAGSTANCES => [UserStance_Module_Processor_TagSectionTabPanelComponents::class, UserStance_Module_Processor_TagSectionTabPanelComponents::MODULE_TABPANEL_TAGSTANCES],
            self::MODULE_BLOCK_TABPANEL_TAGSTANCES_PRO => [UserStance_Module_Processor_TagSectionTabPanelComponents::class, UserStance_Module_Processor_TagSectionTabPanelComponents::MODULE_TABPANEL_TAGSTANCES_PRO],
            self::MODULE_BLOCK_TABPANEL_TAGSTANCES_NEUTRAL => [UserStance_Module_Processor_TagSectionTabPanelComponents::class, UserStance_Module_Processor_TagSectionTabPanelComponents::MODULE_TABPANEL_TAGSTANCES_NEUTRAL],
            self::MODULE_BLOCK_TABPANEL_TAGSTANCES_AGAINST => [UserStance_Module_Processor_TagSectionTabPanelComponents::class, UserStance_Module_Processor_TagSectionTabPanelComponents::MODULE_TABPANEL_TAGSTANCES_AGAINST],
        );
        if ($inner = $inners[$componentVariation[1]] ?? null) {
            $ret[] = $inner;
        }

        return $ret;
    }

    public function getDelegatorfilterSubmodule(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_BLOCK_TABPANEL_TAGSTANCES:
                return [UserStance_Module_Processor_CustomFilters::class, UserStance_Module_Processor_CustomFilters::MODULE_FILTER_TAGSTANCES];

            case self::MODULE_BLOCK_TABPANEL_TAGSTANCES_PRO:
            case self::MODULE_BLOCK_TABPANEL_TAGSTANCES_NEUTRAL:
            case self::MODULE_BLOCK_TABPANEL_TAGSTANCES_AGAINST:
                return [UserStance_Module_Processor_CustomFilters::class, UserStance_Module_Processor_CustomFilters::MODULE_FILTER_TAGSTANCES_STANCE];
        }

        return parent::getDelegatorfilterSubmodule($componentVariation);
    }
}


