<?php

class PoP_LocationPosts_Module_Processor_SectionTabPanelBlocks extends PoP_Module_Processor_TabPanelSectionBlocksBase
{
    public const MODULE_BLOCK_TABPANEL_LOCATIONPOSTS = 'block-locationposts-tabpanel';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BLOCK_TABPANEL_LOCATIONPOSTS],
        );
    }

    protected function getInnerSubmodules(array $module): array
    {
        $ret = parent::getInnerSubmodules($module);

        $inners = array(
            self::MODULE_BLOCK_TABPANEL_LOCATIONPOSTS => [PoP_LocationPosts_Module_Processor_SectionTabPanelComponents::class, PoP_LocationPosts_Module_Processor_SectionTabPanelComponents::MODULE_TABPANEL_LOCATIONPOSTS],
        );
        if ($inner = $inners[$module[1]] ?? null) {
            $ret[] = $inner;
        }

        return $ret;
    }

    protected function getControlgroupTopSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_BLOCK_TABPANEL_LOCATIONPOSTS:
                return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::MODULE_CONTROLGROUP_POSTLIST];
        }

        return parent::getControlgroupTopSubmodule($module);
    }

    public function getDelegatorfilterSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_BLOCK_TABPANEL_LOCATIONPOSTS:
                return [PoP_LocationPosts_Module_Processor_CustomFilters::class, PoP_LocationPosts_Module_Processor_CustomFilters::MODULE_FILTER_LOCATIONPOSTS];
        }

        return parent::getDelegatorfilterSubmodule($module);
    }
}


