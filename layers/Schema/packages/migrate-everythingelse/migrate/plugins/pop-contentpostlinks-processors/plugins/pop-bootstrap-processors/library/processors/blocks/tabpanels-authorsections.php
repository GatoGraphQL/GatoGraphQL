<?php

use PoP\ComponentModel\State\ApplicationState;

class PoP_ContentPostLinks_Module_Processor_AuthorSectionTabPanelBlocks extends PoP_Module_Processor_AuthorTabPanelSectionBlocksBase
{
    public const MODULE_BLOCK_TABPANEL_AUTHORLINKS = 'block-tabpanel-authorlinks';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BLOCK_TABPANEL_AUTHORLINKS],
        );
    }

    protected function getInnerSubmodules(array $module): array
    {
        $ret = parent::getInnerSubmodules($module);

        if (defined('POP_USERCOMMUNITIESPROCESSORS_INITIALIZED')) {
            $vars = ApplicationState::getVars();
            $author = $vars['routing-state']['queried-object-id'];
            if (gdUreIsCommunity($author)) {
                switch ($module[1]) {
                    case self::MODULE_BLOCK_TABPANEL_AUTHORLINKS:
                        $ret[] = [GD_URE_Module_Processor_ControlGroups::class, GD_URE_Module_Processor_ControlGroups::MODULE_URE_CONTROLGROUP_CONTENTSOURCE];
                        break;
                }
            }
        }

        $inners = array(
            self::MODULE_BLOCK_TABPANEL_AUTHORLINKS => [PoP_ContentPostLinks_Module_Processor_AuthorSectionTabPanelComponents::class, PoP_ContentPostLinks_Module_Processor_AuthorSectionTabPanelComponents::MODULE_TABPANEL_AUTHORLINKS],
        );
        if ($inner = $inners[$module[1]]) {
            $ret[] = $inner;
        }
        
        return $ret;
    }

    public function getDelegatorfilterSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_BLOCK_TABPANEL_AUTHORLINKS:
                return [PoP_ContentPostLinks_Module_Processor_CustomFilters::class, PoP_ContentPostLinks_Module_Processor_CustomFilters::MODULE_FILTER_AUTHORLINKS];
        }
        
        return parent::getDelegatorfilterSubmodule($module);
    }
}


