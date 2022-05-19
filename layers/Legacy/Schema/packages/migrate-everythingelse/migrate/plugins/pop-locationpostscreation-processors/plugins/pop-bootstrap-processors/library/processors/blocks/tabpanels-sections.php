<?php

class PoP_LocationPostsCreation_Module_Processor_SectionTabPanelBlock extends PoP_Module_Processor_TabPanelSectionBlocksBase
{
    public final const COMPONENT_BLOCK_TABPANEL_MYLOCATIONPOSTS = 'block-mylocationposts-tabpanel';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_BLOCK_TABPANEL_MYLOCATIONPOSTS],
        );
    }

    protected function getInnerSubcomponents(array $component): array
    {
        $ret = parent::getInnerSubcomponents($component);

        $inners = array(
            self::COMPONENT_BLOCK_TABPANEL_MYLOCATIONPOSTS => [PoP_LocationPostsCreation_Module_Processor_SectionTabPanelComponents::class, PoP_LocationPostsCreation_Module_Processor_SectionTabPanelComponents::COMPONENT_TABPANEL_MYLOCATIONPOSTS],
        );
        if ($inner = $inners[$component[1]] ?? null) {
            $ret[] = $inner;
        }

        return $ret;
    }

    protected function getControlgroupTopSubcomponent(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_BLOCK_TABPANEL_MYLOCATIONPOSTS:
                return [CommonPages_EM_Module_Processor_ControlGroups::class, CommonPages_EM_Module_Processor_ControlGroups::COMPONENT_CONTROLGROUP_MYLOCATIONPOSTLIST];
        }

        return parent::getControlgroupTopSubcomponent($component);
    }

    public function getDelegatorfilterSubcomponent(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_BLOCK_TABPANEL_MYLOCATIONPOSTS:
                return [GD_Custom_EM_Module_Processor_CustomFilters::class, GD_Custom_EM_Module_Processor_CustomFilters::COMPONENT_FILTER_MYLOCATIONPOSTS];
        }

        return parent::getDelegatorfilterSubcomponent($component);
    }
}


