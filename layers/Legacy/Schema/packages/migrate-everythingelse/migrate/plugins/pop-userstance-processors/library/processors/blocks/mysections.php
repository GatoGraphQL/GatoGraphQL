<?php

class UserStance_Module_Processor_MySectionBlocks extends PoP_Module_Processor_MySectionBlocksBase
{
    public final const MODULE_BLOCK_MYSTANCES_TABLE_EDIT = 'block-mystances-table-edit';
    public final const MODULE_BLOCK_MYSTANCES_SCROLL_FULLVIEWPREVIEW = 'block-mystances-scroll-fullviewpreview';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BLOCK_MYSTANCES_TABLE_EDIT],
            [self::class, self::MODULE_BLOCK_MYSTANCES_SCROLL_FULLVIEWPREVIEW],
        );
    }

    public function getRelevantRoute(array $componentVariation, array &$props): ?string
    {
        return match($componentVariation[1]) {
            self::MODULE_BLOCK_MYSTANCES_SCROLL_FULLVIEWPREVIEW => POP_USERSTANCE_ROUTE_MYSTANCES,
            self::MODULE_BLOCK_MYSTANCES_TABLE_EDIT => POP_USERSTANCE_ROUTE_MYSTANCES,
            default => parent::getRelevantRoute($componentVariation, $props),
        };
    }

    protected function getInnerSubmodule(array $componentVariation)
    {
        $inner_componentVariations = array(

            self::MODULE_BLOCK_MYSTANCES_TABLE_EDIT => [UserStance_Module_Processor_MySectionDataloads::class, UserStance_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYSTANCES_TABLE_EDIT],
            self::MODULE_BLOCK_MYSTANCES_SCROLL_FULLVIEWPREVIEW => [UserStance_Module_Processor_MySectionDataloads::class, UserStance_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYSTANCES_SCROLL_FULLVIEWPREVIEW],
        );

        return $inner_componentVariations[$componentVariation[1]] ?? null;
    }

    protected function getControlgroupTopSubmodule(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_BLOCK_MYSTANCES_TABLE_EDIT:
            case self::MODULE_BLOCK_MYSTANCES_SCROLL_FULLVIEWPREVIEW:
                return [UserStance_Module_Processor_CustomControlGroups::class, UserStance_Module_Processor_CustomControlGroups::MODULE_CONTROLGROUP_MYSTANCELIST];
        }

        return parent::getControlgroupTopSubmodule($componentVariation);
    }
}



