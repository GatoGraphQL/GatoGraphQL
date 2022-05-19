<?php

class PoP_UserCommunities_Module_Processor_MySectionBlocks extends PoP_Module_Processor_SectionBlocksBase
{
    public final const COMPONENT_BLOCK_MYMEMBERS_TABLE_EDIT = 'block-mymembers-table-edit';
    public final const COMPONENT_BLOCK_MYMEMBERS_SCROLL_FULLVIEW = 'block-mymembers-scroll-fullviewpreview';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_BLOCK_MYMEMBERS_TABLE_EDIT],
            [self::class, self::COMPONENT_BLOCK_MYMEMBERS_SCROLL_FULLVIEW],
        );
    }

    public function getRelevantRoute(array $component, array &$props): ?string
    {
        return match($component[1]) {
            self::COMPONENT_BLOCK_MYMEMBERS_SCROLL_FULLVIEW => POP_USERCOMMUNITIES_ROUTE_MYMEMBERS,
            self::COMPONENT_BLOCK_MYMEMBERS_TABLE_EDIT => POP_USERCOMMUNITIES_ROUTE_MYMEMBERS,
            default => parent::getRelevantRoute($component, $props),
        };
    }

    protected function getInnerSubcomponent(array $component)
    {
        $inners = array(
            self::COMPONENT_BLOCK_MYMEMBERS_TABLE_EDIT => [PoP_UserCommunities_Module_Processor_MySectionDataloads::class, PoP_UserCommunities_Module_Processor_MySectionDataloads::COMPONENT_DATALOAD_MYMEMBERS_TABLE_EDIT],
            self::COMPONENT_BLOCK_MYMEMBERS_SCROLL_FULLVIEW => [PoP_UserCommunities_Module_Processor_MySectionDataloads::class, PoP_UserCommunities_Module_Processor_MySectionDataloads::COMPONENT_DATALOAD_MYMEMBERS_SCROLL_FULLVIEW],
        );
        return $inners[$component[1]] ?? null;
    }

    protected function showDisabledLayerIfCheckpointFailed(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_BLOCK_MYMEMBERS_TABLE_EDIT:
            case self::COMPONENT_BLOCK_MYMEMBERS_SCROLL_FULLVIEW:
                return true;
        }

        return parent::showDisabledLayerIfCheckpointFailed($component, $props);
        ;
    }

    protected function getControlgroupTopSubcomponent(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_BLOCK_MYMEMBERS_TABLE_EDIT:
            case self::COMPONENT_BLOCK_MYMEMBERS_SCROLL_FULLVIEW:
                return [GD_URE_Module_Processor_CustomControlGroups::class, GD_URE_Module_Processor_CustomControlGroups::COMPONENT_CONTROLGROUP_MYBLOCKMEMBERS];
        }

        return parent::getControlgroupTopSubcomponent($component);
    }
}



