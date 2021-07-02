<?php

class UserStance_Module_Processor_TagSectionTabPanelBlocks extends PoP_Module_Processor_TagTabPanelSectionBlocksBase
{
    public const MODULE_BLOCK_TABPANEL_TAGSTANCES = 'block-tabpanel-tagstances';
    public const MODULE_BLOCK_TABPANEL_TAGSTANCES_PRO = 'block-tabpanel-tagstances-pro';
    public const MODULE_BLOCK_TABPANEL_TAGSTANCES_NEUTRAL = 'block-tabpanel-tagstances-neutral';
    public const MODULE_BLOCK_TABPANEL_TAGSTANCES_AGAINST = 'block-tabpanel-tagstances-against';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BLOCK_TABPANEL_TAGSTANCES],
            [self::class, self::MODULE_BLOCK_TABPANEL_TAGSTANCES_PRO],
            [self::class, self::MODULE_BLOCK_TABPANEL_TAGSTANCES_NEUTRAL],
            [self::class, self::MODULE_BLOCK_TABPANEL_TAGSTANCES_AGAINST],
        );
    }

    protected function getInnerSubmodules(array $module): array
    {
        $ret = parent::getInnerSubmodules($module);

        $inners = array(
            self::MODULE_BLOCK_TABPANEL_TAGSTANCES => [UserStance_Module_Processor_TagSectionTabPanelComponents::class, UserStance_Module_Processor_TagSectionTabPanelComponents::MODULE_TABPANEL_TAGSTANCES],
            self::MODULE_BLOCK_TABPANEL_TAGSTANCES_PRO => [UserStance_Module_Processor_TagSectionTabPanelComponents::class, UserStance_Module_Processor_TagSectionTabPanelComponents::MODULE_TABPANEL_TAGSTANCES_PRO],
            self::MODULE_BLOCK_TABPANEL_TAGSTANCES_NEUTRAL => [UserStance_Module_Processor_TagSectionTabPanelComponents::class, UserStance_Module_Processor_TagSectionTabPanelComponents::MODULE_TABPANEL_TAGSTANCES_NEUTRAL],
            self::MODULE_BLOCK_TABPANEL_TAGSTANCES_AGAINST => [UserStance_Module_Processor_TagSectionTabPanelComponents::class, UserStance_Module_Processor_TagSectionTabPanelComponents::MODULE_TABPANEL_TAGSTANCES_AGAINST],
        );
        if ($inner = $inners[$module[1]] ?? null) {
            $ret[] = $inner;
        }

        return $ret;
    }

    public function getDelegatorfilterSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_BLOCK_TABPANEL_TAGSTANCES:
                return [UserStance_Module_Processor_CustomFilters::class, UserStance_Module_Processor_CustomFilters::MODULE_FILTER_TAGSTANCES];

            case self::MODULE_BLOCK_TABPANEL_TAGSTANCES_PRO:
            case self::MODULE_BLOCK_TABPANEL_TAGSTANCES_NEUTRAL:
            case self::MODULE_BLOCK_TABPANEL_TAGSTANCES_AGAINST:
                return [UserStance_Module_Processor_CustomFilters::class, UserStance_Module_Processor_CustomFilters::MODULE_FILTER_TAGSTANCES_STANCE];
        }

        return parent::getDelegatorfilterSubmodule($module);
    }
}


