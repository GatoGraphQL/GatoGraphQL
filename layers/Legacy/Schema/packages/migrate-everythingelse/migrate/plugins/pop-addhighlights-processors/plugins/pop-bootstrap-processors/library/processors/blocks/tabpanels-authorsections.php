<?php

use PoP\ComponentModel\State\ApplicationState;

class PoP_AddHighlights_Module_Processor_AuthorSectionTabPanelBlocks extends PoP_Module_Processor_AuthorTabPanelSectionBlocksBase
{
    public const MODULE_BLOCK_TABPANEL_AUTHORHIGHLIGHTS = 'block-tabpanel-authorhighlights';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BLOCK_TABPANEL_AUTHORHIGHLIGHTS],
        );
    }

    protected function getInnerSubmodules(array $module): array
    {
        $ret = parent::getInnerSubmodules($module);

        if (defined('POP_USERCOMMUNITIESPROCESSORS_INITIALIZED')) {
            $vars = ApplicationState::getVars();
            $author = \PoP\Root\App::getState(['routing', 'queried-object-id']);
            if (gdUreIsCommunity($author)) {
                switch ($module[1]) {
                    case self::MODULE_BLOCK_TABPANEL_AUTHORHIGHLIGHTS:
                        $ret[] = [GD_URE_Module_Processor_ControlGroups::class, GD_URE_Module_Processor_ControlGroups::MODULE_URE_CONTROLGROUP_CONTENTSOURCE];
                        break;
                }
            }
        }

        $inners = array(
            self::MODULE_BLOCK_TABPANEL_AUTHORHIGHLIGHTS => [PoP_AddHighlights_Module_Processor_AuthorSectionTabPanelComponents::class, PoP_AddHighlights_Module_Processor_AuthorSectionTabPanelComponents::MODULE_TABPANEL_AUTHORHIGHLIGHTS],
        );
        if ($inner = $inners[$module[1]] ?? null) {
            $ret[] = $inner;
        }

        return $ret;
    }

    public function getDelegatorfilterSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_BLOCK_TABPANEL_AUTHORHIGHLIGHTS:
                return [PoP_AddHighlights_Module_Processor_CustomFilters::class, PoP_AddHighlights_Module_Processor_CustomFilters::MODULE_FILTER_AUTHORHIGHLIGHTS];
        }

        return parent::getDelegatorfilterSubmodule($module);
    }
}


