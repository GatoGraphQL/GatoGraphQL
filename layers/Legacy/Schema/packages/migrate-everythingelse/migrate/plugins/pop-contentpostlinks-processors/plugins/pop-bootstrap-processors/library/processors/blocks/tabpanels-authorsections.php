<?php

use PoP\ComponentModel\State\ApplicationState;

class PoP_ContentPostLinks_Module_Processor_AuthorSectionTabPanelBlocks extends PoP_Module_Processor_AuthorTabPanelSectionBlocksBase
{
    public final const MODULE_BLOCK_TABPANEL_AUTHORLINKS = 'block-tabpanel-authorlinks';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_BLOCK_TABPANEL_AUTHORLINKS],
        );
    }

    protected function getInnerSubmodules(array $component): array
    {
        $ret = parent::getInnerSubmodules($component);

        if (defined('POP_USERCOMMUNITIESPROCESSORS_INITIALIZED')) {
            $author = \PoP\Root\App::getState(['routing', 'queried-object-id']);
            if (gdUreIsCommunity($author)) {
                switch ($component[1]) {
                    case self::COMPONENT_BLOCK_TABPANEL_AUTHORLINKS:
                        $ret[] = [GD_URE_Module_Processor_ControlGroups::class, GD_URE_Module_Processor_ControlGroups::COMPONENT_URE_CONTROLGROUP_CONTENTSOURCE];
                        break;
                }
            }
        }

        $inners = array(
            self::COMPONENT_BLOCK_TABPANEL_AUTHORLINKS => [PoP_ContentPostLinks_Module_Processor_AuthorSectionTabPanelComponents::class, PoP_ContentPostLinks_Module_Processor_AuthorSectionTabPanelComponents::COMPONENT_TABPANEL_AUTHORLINKS],
        );
        if ($inner = $inners[$component[1]] ?? null) {
            $ret[] = $inner;
        }

        return $ret;
    }

    public function getDelegatorfilterSubmodule(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_BLOCK_TABPANEL_AUTHORLINKS:
                return [PoP_ContentPostLinks_Module_Processor_CustomFilters::class, PoP_ContentPostLinks_Module_Processor_CustomFilters::COMPONENT_FILTER_AUTHORLINKS];
        }

        return parent::getDelegatorfilterSubmodule($component);
    }
}


