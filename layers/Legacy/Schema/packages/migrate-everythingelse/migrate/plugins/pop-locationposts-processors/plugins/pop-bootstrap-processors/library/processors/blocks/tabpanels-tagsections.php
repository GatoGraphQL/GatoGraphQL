<?php

class PoP_LocationPosts_Module_Processor_TagSectionTabPanelBlocks extends PoP_Module_Processor_TagTabPanelSectionBlocksBase
{
    public final const MODULE_BLOCK_TABPANEL_TAGLOCATIONPOSTS = 'block-tabpanel-taglocationposts';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BLOCK_TABPANEL_TAGLOCATIONPOSTS],
        );
    }

    protected function getInnerSubmodules(array $component): array
    {
        $ret = parent::getInnerSubmodules($component);

        $inners = array(
            self::MODULE_BLOCK_TABPANEL_TAGLOCATIONPOSTS => [PoP_LocationPosts_Module_Processor_TagSectionTabPanelComponents::class, PoP_LocationPosts_Module_Processor_TagSectionTabPanelComponents::MODULE_TABPANEL_TAGLOCATIONPOSTS],
        );
        if ($inner = $inners[$component[1]] ?? null) {
            $ret[] = $inner;
        }

        return $ret;
    }

    public function getDelegatorfilterSubmodule(array $component)
    {
        switch ($component[1]) {
            case self::MODULE_BLOCK_TABPANEL_TAGLOCATIONPOSTS:
                return [PoP_LocationPosts_Module_Processor_CustomFilters::class, PoP_LocationPosts_Module_Processor_CustomFilters::MODULE_FILTER_TAGLOCATIONPOSTS];
        }

        return parent::getDelegatorfilterSubmodule($component);
    }
}


