<?php

use PoP\ComponentModel\State\ApplicationState;

class PoP_LocationPosts_Module_Processor_AuthorSectionTabPanelBlocks extends PoP_Module_Processor_AuthorTabPanelSectionBlocksBase
{
    public final const MODULE_BLOCK_TABPANEL_AUTHORLOCATIONPOSTS = 'block-tabpanel-authorlocationposts';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BLOCK_TABPANEL_AUTHORLOCATIONPOSTS],
        );
    }

    protected function getInnerSubmodules(array $component): array
    {
        $ret = parent::getInnerSubmodules($component);

        if (defined('POP_USERCOMMUNITIESPROCESSORS_INITIALIZED')) {
            $author = \PoP\Root\App::getState(['routing', 'queried-object-id']);
            if (gdUreIsCommunity($author)) {
                switch ($component[1]) {
                    case self::MODULE_BLOCK_TABPANEL_AUTHORLOCATIONPOSTS:
                        $ret[] = [GD_URE_Module_Processor_ControlGroups::class, GD_URE_Module_Processor_ControlGroups::MODULE_URE_CONTROLGROUP_CONTENTSOURCE];
                        break;
                }
            }
        }

        $inners = array(
            self::MODULE_BLOCK_TABPANEL_AUTHORLOCATIONPOSTS => [PoP_LocationPosts_Module_Processor_AuthorSectionTabPanelComponents::class, PoP_LocationPosts_Module_Processor_AuthorSectionTabPanelComponents::MODULE_TABPANEL_AUTHORLOCATIONPOSTS],
        );
        if ($inner = $inners[$component[1]] ?? null) {
            $ret[] = $inner;
        }

        return $ret;
    }

    public function getDelegatorfilterSubmodule(array $component)
    {
        switch ($component[1]) {
            case self::MODULE_BLOCK_TABPANEL_AUTHORLOCATIONPOSTS:
                return [PoP_LocationPosts_Module_Processor_CustomFilters::class, PoP_LocationPosts_Module_Processor_CustomFilters::MODULE_FILTER_AUTHORLOCATIONPOSTS];
        }

        return parent::getDelegatorfilterSubmodule($component);
    }
}


