<?php

class PoP_LocationPosts_Module_Processor_TagSectionTabPanelBlocks extends PoP_Module_Processor_TagTabPanelSectionBlocksBase
{
    public final const COMPONENT_BLOCK_TABPANEL_TAGLOCATIONPOSTS = 'block-tabpanel-taglocationposts';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_BLOCK_TABPANEL_TAGLOCATIONPOSTS],
        );
    }

    protected function getInnerSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getInnerSubcomponents($component);

        $inners = array(
            self::COMPONENT_BLOCK_TABPANEL_TAGLOCATIONPOSTS => [PoP_LocationPosts_Module_Processor_TagSectionTabPanelComponents::class, PoP_LocationPosts_Module_Processor_TagSectionTabPanelComponents::COMPONENT_TABPANEL_TAGLOCATIONPOSTS],
        );
        if ($inner = $inners[$component->name] ?? null) {
            $ret[] = $inner;
        }

        return $ret;
    }

    public function getDelegatorfilterSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_BLOCK_TABPANEL_TAGLOCATIONPOSTS:
                return [PoP_LocationPosts_Module_Processor_CustomFilters::class, PoP_LocationPosts_Module_Processor_CustomFilters::COMPONENT_FILTER_TAGLOCATIONPOSTS];
        }

        return parent::getDelegatorfilterSubcomponent($component);
    }
}


