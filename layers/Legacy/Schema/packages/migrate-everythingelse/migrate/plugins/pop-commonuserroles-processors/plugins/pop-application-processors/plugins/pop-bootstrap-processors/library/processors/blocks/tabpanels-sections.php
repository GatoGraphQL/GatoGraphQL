<?php

class GD_URE_Module_Processor_SectionTabPanelBlocks extends PoP_Module_Processor_TabPanelSectionBlocksBase
{
    public final const MODULE_BLOCK_TABPANEL_ORGANIZATIONS = 'block-organizations-tabpanel';
    public final const MODULE_BLOCK_TABPANEL_INDIVIDUALS = 'block-individuals-tabpanel';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BLOCK_TABPANEL_ORGANIZATIONS],
            [self::class, self::MODULE_BLOCK_TABPANEL_INDIVIDUALS],
        );
    }

    public function getRelevantRoute(array $module, array &$props): ?string
    {
        return match($module[1]) {
            self::MODULE_BLOCK_TABPANEL_INDIVIDUALS => POP_COMMONUSERROLES_ROUTE_INDIVIDUALS,
            self::MODULE_BLOCK_TABPANEL_ORGANIZATIONS => POP_COMMONUSERROLES_ROUTE_ORGANIZATIONS,
            default => parent::getRelevantRoute($module, $props),
        };
    }

    protected function getInnerSubmodules(array $module): array
    {
        $ret = parent::getInnerSubmodules($module);

        $inners = array(
            self::MODULE_BLOCK_TABPANEL_INDIVIDUALS => [GD_URE_Module_Processor_SectionTabPanelComponents::class, GD_URE_Module_Processor_SectionTabPanelComponents::MODULE_TABPANEL_INDIVIDUALS],
            self::MODULE_BLOCK_TABPANEL_ORGANIZATIONS => [GD_URE_Module_Processor_SectionTabPanelComponents::class, GD_URE_Module_Processor_SectionTabPanelComponents::MODULE_TABPANEL_ORGANIZATIONS],
        );
        if ($inner = $inners[$module[1]] ?? null) {
            $ret[] = $inner;
        }

        return $ret;
    }

    protected function getControlgroupTopSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_BLOCK_TABPANEL_ORGANIZATIONS:
            case self::MODULE_BLOCK_TABPANEL_INDIVIDUALS:
                return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::MODULE_CONTROLGROUP_USERLIST];
        }

        return parent::getControlgroupTopSubmodule($module);
    }

    public function getDelegatorfilterSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_BLOCK_TABPANEL_ORGANIZATIONS:
                return [PoP_CommonUserRoles_Module_Processor_CustomFilters::class, PoP_CommonUserRoles_Module_Processor_CustomFilters::MODULE_FILTER_ORGANIZATIONS];

            case self::MODULE_BLOCK_TABPANEL_INDIVIDUALS:
                return [PoP_CommonUserRoles_Module_Processor_CustomFilters::class, PoP_CommonUserRoles_Module_Processor_CustomFilters::MODULE_FILTER_INDIVIDUALS];
        }

        return parent::getDelegatorfilterSubmodule($module);
    }
}


