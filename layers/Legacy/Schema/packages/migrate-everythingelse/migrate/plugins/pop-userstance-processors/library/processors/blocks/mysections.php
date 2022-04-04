<?php

class UserStance_Module_Processor_MySectionBlocks extends PoP_Module_Processor_MySectionBlocksBase
{
    public final const MODULE_BLOCK_MYSTANCES_TABLE_EDIT = 'block-mystances-table-edit';
    public final const MODULE_BLOCK_MYSTANCES_SCROLL_FULLVIEWPREVIEW = 'block-mystances-scroll-fullviewpreview';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BLOCK_MYSTANCES_TABLE_EDIT],
            [self::class, self::MODULE_BLOCK_MYSTANCES_SCROLL_FULLVIEWPREVIEW],
        );
    }

    public function getRelevantRoute(array $module, array &$props): ?string
    {
        return match($module[1]) {
            self::MODULE_BLOCK_MYSTANCES_SCROLL_FULLVIEWPREVIEW => POP_USERSTANCE_ROUTE_MYSTANCES,
            self::MODULE_BLOCK_MYSTANCES_TABLE_EDIT => POP_USERSTANCE_ROUTE_MYSTANCES,
            default => parent::getRelevantRoute($module, $props),
        };
    }

    protected function getInnerSubmodule(array $module)
    {
        $inner_modules = array(

            self::MODULE_BLOCK_MYSTANCES_TABLE_EDIT => [UserStance_Module_Processor_MySectionDataloads::class, UserStance_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYSTANCES_TABLE_EDIT],
            self::MODULE_BLOCK_MYSTANCES_SCROLL_FULLVIEWPREVIEW => [UserStance_Module_Processor_MySectionDataloads::class, UserStance_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYSTANCES_SCROLL_FULLVIEWPREVIEW],
        );

        return $inner_modules[$module[1]] ?? null;
    }

    protected function getControlgroupTopSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_BLOCK_MYSTANCES_TABLE_EDIT:
            case self::MODULE_BLOCK_MYSTANCES_SCROLL_FULLVIEWPREVIEW:
                return [UserStance_Module_Processor_CustomControlGroups::class, UserStance_Module_Processor_CustomControlGroups::MODULE_CONTROLGROUP_MYSTANCELIST];
        }

        return parent::getControlgroupTopSubmodule($module);
    }
}



